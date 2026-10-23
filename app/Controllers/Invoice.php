<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Invoice as InvoiceModel;
use App\Models\InvoiceItem as ItemModel;
use App\Models\Client as ClientModel;

class Invoice extends BaseController
{
    protected $invoiceModel, $itemModel, $clientModel;

    public function __construct()
    {
        // Auth guard
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $this->invoiceModel = new InvoiceModel();
        $this->itemModel    = new ItemModel();
        $this->clientModel  = new ClientModel();
    }

    /* ─────────────────── Dashboard / Index ─────────────────── */
    public function index()
    {
        $userId = session()->get('user_id');
        $invoices = $this->invoiceModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Summary stats
        $totalAmount     = 0;
        $pendingCount    = 0;
        $paidCount       = 0;
        $pendingAmount   = 0;
        $paidAmount      = 0;

        foreach ($invoices as $inv) {
            $totalAmount += (float)$inv['total_amount'];
            if ($inv['status'] === 'unpaid') {
                $pendingCount++;
                $pendingAmount += (float)$inv['total_amount'];
            } elseif ($inv['status'] === 'paid') {
                $paidCount++;
                $paidAmount  += (float)$inv['total_amount'];
            }
        }

        // Enrich with client name
        $enriched = [];
        foreach ($invoices as $inv) {
            $client = $this->clientModel->find($inv['client_id']);
            $enriched[] = [
                'invoice'   => $inv,
                'clientName'=> $client ? htmlspecialchars($client['client_name']) : '-',
            ];
        }

        return view('invoices/index', [
            'invoices'      => $enriched,
            'totalAmount'   => $totalAmount,
            'pendingCount'  => $pendingCount,
            'paidCount'     => $paidCount,
            'pendingAmount' => $pendingAmount,
            'paidAmount'    => $paidAmount,
        ]);
    }

    /* ──────────────── Create Invoice Form ─────────────────── */
    public function create()
    {
        $clients = $this->clientModel
            ->where('user_id', session()->get('user_id'))
            ->findAll();

        return view('invoices/create', [
            'clients' => $clients,
        ]);
    }

    /* ─────────────────── Store Invoice (transaction) ─────── */
    public function store()
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        $post   = $this->request->getPost();

        // ---- 1. Resolve client: existing or inline create ──
        $clientId = (int)($post['client_id'] ?? 0);

        if ($clientId > 0) {
            $existingClient = $this->clientModel
                ->where('id', $clientId)
                ->where('user_id', $userId)
                ->first();
            if (!$existingClient) {
                return redirect()->back()->withInput()
                    ->with('error', 'Klien tidak ditemukan atau bukan milik Anda.');
            }
        } else {
            // Validate inline client fields
            $clientName  = trim($post['client_name'] ?? '');
            $clientPhone = trim($post['client_phone'] ?? '');
            $clientEmail = trim($post['client_email'] ?? '');

            if (empty($clientName) || empty($clientPhone)) {
                return redirect()->back()->withInput()
                    ->with('error', 'Nama klien dan nomor WA wajib diisi.');
            }

            try {
                $clientId = $this->clientModel->insert([
                    'user_id'      => $userId,
                    'client_name'  => $clientName,
                    'client_phone' => $clientPhone,
                    'client_email' => $clientEmail ?: null,
                ]);

                if (!$clientId) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Gagal menyimpan data klien.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Insert client error: ' . $e->getMessage());
                return redirect()->back()->withInput()
                    ->with('error', 'Gagal menyimpan data klien. Silakan coba lagi.');
            }
        }

        // ---- 2. Parse line items & compute total ──
        $descriptions = $post['item_description'] ?? [];
        $prices       = $post['item_price']     ?? [];
        $itemsToSave  = [];
        $totalAmount  = 0;

        for ($i = 0; $i < count($descriptions); $i++) {
            $desc = trim((string)$descriptions[$i]);
            $priceStr = trim((string)($prices[$i] ?? '0'));
            // Strip non-numeric chars: dots, commas, "Rp", spaces
            $price = floatval(preg_replace('/[^\d]/', '', $priceStr));

            if (!empty($desc) && $price > 0) {
                $subtotal   = $price;     // quantity fixed at 1
                $itemsToSave[] = [
                    'description' => $desc,
                    'quantity'    => 1,
                    'price'       => $price,
                    'subtotal'    => $subtotal,
                ];
                $totalAmount += $subtotal;
            }
        }

        if (empty($itemsToSave)) {
            return redirect()->back()->withInput()
                ->with('error', 'Tambahkan minimal satu item dengan deskripsi dan harga.');
        }

        // ---- 3. Due date ──
        $dueDate = !empty($post['due_date']) && (date_parse($post['due_date'])['error_count'] === 0)
            ? date('Y-m-d', strtotime($post['due_date']))
            : date('Y-m-d', strtotime('+30 days'));

        // ---- 4. Generate invoice code ──
        $invoiceCode = 'INV-' . strtoupper(substr(md5(time() . random_int(1000, 9999)), 0, 12));

        // ---- 5. DB Transaction — insert invoice + items atomically ──
        try {
            $db    = \Config\Database::connect();
            $trans = $db->transStart();

            $invoiceId = $this->invoiceModel->insert([
                'invoice_code' => $invoiceCode,
                'user_id'      => $userId,
                'client_id'    => $clientId,
                'total_amount' => $totalAmount,
                'status'       => 'unpaid',
                'due_date'     => $dueDate,
            ]);

            if (!$invoiceId) {
                throw new \RuntimeException('Insert invoice gagal.');
            }

            foreach ($itemsToSave as &$item) {
                $item['invoice_id'] = $invoiceId;
            }
            $this->itemModel->insertBatch($itemsToSave);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Transaksi invoice gagal.');
            }

        } catch (\Exception $e) {
            log_message('error', 'Invoice store error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Gagal menyimpan invoice. Silakan coba lagi.');
        }

        return redirect()->to('/dashboard')
            ->with('success', "Invoice {$invoiceCode} berhasil dibuat!");
    }

    /* ───────────── Update Status (one-click toggle) ──────── */
    public function updateStatus($id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        $invoice = $this->invoiceModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        // Toggle between unpaid and paid
        $newStatus = ($invoice['status'] === 'unpaid') ? 'paid' : 'unpaid';

        try {
            $this->invoiceModel->update($id, ['status' => $newStatus]);
        } catch (\Exception $e) {
            log_message('error', 'Update status error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status.');
        }

        $msg = ($newStatus === 'paid') ? 'Invoice ditandai Lunas.' : 'Invoice diubah menjadi Belum Dibayar.';
        return redirect()->back()->with('success', $msg);
    }

    /* ────────────── WhatsApp Link Generator ─────────────── */
    public function sendWhatsApp($id)
    {
        if (strtolower($this->request->getMethod()) !== 'post') {
            return redirect()->back();
        }

        $userId = session()->get('user_id');
        $invoice = $this->invoiceModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$invoice) {
            return redirect()->back()->with('error', 'Invoice tidak ditemukan.');
        }

        $client = $this->clientModel->find($invoice['client_id']);
        $phone  = $client ? ($client['client_phone'] ?? '') : '';

        if (empty($phone)) {
            return redirect()->back()
                ->with('error', 'Nomor WA klien belum diatur.');
        }

        // Normalize: ensure +62 prefix
        $phone = ltrim($phone, '+0');
        $phone = substr($phone, 0, 3) === '62' ? $phone : '62' . ltrim($phone, '0');

        $publicUrl = site_url('i/' . urlencode($invoice['invoice_code']));
        $dateFmt   = ($invoice['due_date'])
            ? date('d/m/Y', strtotime($invoice['due_date']))
            : '-';

        $message = rawurlencode(
            "*Tagihan Invoice*\n"
          . "No. Invoice: {$invoice['invoice_code']}\n"
          . "Total: Rp " . number_format((int)$invoice['total_amount'], 0, ',', '.') . "\n"
          . "Jatuh Tempo: {$dateFmt}\n"
          . "Link Pembayaran: {$publicUrl}\n\n"
          . "Mohon segera melakukan pembayaran. Terima kasih."
        );

        $waUrl = "https://wa.me/{$phone}?text={$message}";
        return redirect()->to($waUrl);
    }
}
