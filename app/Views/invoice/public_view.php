<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tagihan <?= esc($invoice['invoice_code']) ?> - InvoiseLokalis</title>

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font sans-serif yang bersih -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen py-8 px-4">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <!-- Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Tagihan dari</p>
                        <h1 class="text-xl font-bold text-slate-900 mt-0.5">
                            <?= esc($owner['business_name'] ?? '-') ?>
                        </h1>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide <?= esc($statusCls) ?>">
                        <?= esc($statusLabel) ?>
                    </span>
                </div>
            </div>

            <!-- Invoice meta -->
            <div class="p-6 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">No. Invoice</span>
                    <span class="font-medium text-slate-900"><?= esc($invoice['invoice_code']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Jatuh Tempo</span>
                    <span class="font-medium text-slate-900"><?= date('d/m/Y', strtotime($invoice['due_date'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tagihan Untuk</span>
                    <span class="font-medium text-slate-900"><?= esc($client['client_name'] ?? '-') ?></span>
                </div>
            </div>

            <!-- Items -->
            <div class="px-6 pb-2">
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold mb-3">Rincian</p>
                    <?php if (! empty($items)): ?>
                        <ul class="divide-y divide-slate-100">
                            <?php foreach ($items as $item): ?>
                                <li class="flex justify-between py-2.5 text-sm">
                                    <span class="text-slate-700">
                                        <?= esc($item['description']) ?>
                                        <?php if ((int)$item['quantity'] > 1): ?>
                                            <span class="text-slate-400">×<?= (int)$item['quantity'] ?></span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="font-medium text-slate-900">
                                        Rp <?= number_format((int)$item['subtotal'], 0, ',', '.') ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-sm text-slate-400">Tidak ada rincian item.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Total -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 font-semibold">Total Bayar</span>
                    <span class="text-2xl font-bold text-blue-700">
                        Rp <?= number_format((int)$invoice['total_amount'], 0, ',', '.') ?>
                    </span>
                </div>
            </div>

            <!-- Payment methods -->
            <div class="p-6 space-y-5 border-t border-slate-100">
                <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Metode Pembayaran</p>

                <?php if (! empty($owner['qris_image'])): ?>
                    <div class="text-center">
                        <p class="text-sm text-slate-600 mb-2">Scan QRIS Statis</p>
                        <img src="<?= base_url('uploads/qris/' . esc($owner['qris_image'], 'url')) ?>"
                             alt="QRIS" class="mx-auto max-w-[240px] w-full rounded-lg border border-slate-200">
                    </div>
                <?php endif; ?>

                <?php if (! empty($owner['bank_details'])): ?>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">Atau transfer bank:</p>
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm text-slate-800 whitespace-pre-line">
                            <?= nl2br(esc($owner['bank_details'])) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (empty($owner['qris_image']) && empty($owner['bank_details'])): ?>
                    <p class="text-sm text-slate-400">Metode pembayaran belum diatur oleh pengirim.</p>
                <?php endif; ?>
            </div>

            <!-- Confirm button -->
            <div class="p-6 border-t border-slate-100">
                <?php if ($confirmUrl): ?>
                    <a href="<?= esc($confirmUrl) ?>" target="_blank" rel="noopener"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                        Konfirmasi Pembayaran via WhatsApp
                    </a>
                <?php else: ?>
                    <button disabled class="block w-full text-center bg-slate-300 text-white font-semibold py-3 px-4 rounded-lg cursor-not-allowed">
                        Konfirmasi Pembayaran via WhatsApp
                    </button>
                <?php endif; ?>
                <p class="text-xs text-slate-400 text-center mt-3">
                    Sudah bayar? Klik tombol di atas untuk konfirmasi ke pengirim.
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; <?= date('Y') ?> InvoiseLokalis
        </p>
    </div>
</body>
</html>