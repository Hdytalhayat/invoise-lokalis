<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-5xl mx-auto">

    <!-- ── Header + Quick Actions ── -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
                <p class="text-slate-700 mt-1">Kelola semua invoice Anda.</p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <a href="/profile" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">Profil & QRIS</a>
                <span class="text-slate-300">|</span>
                <form action="/logout" method="get">
                    <?= csrf_field() ?>
                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium hover:underline">Logout</button>
                </form>
            </div>
        </div>

        <!-- ── Summary Cards ── -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <!-- Total Tagihan -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <h3 class="font-semibold text-blue-900 uppercase text-sm tracking-wide">Total Tagihan</h3>
                <p class="text-2xl font-bold text-blue-700 mt-1">Rp <?= number_format($totalAmount, 0, ',', '.') ?></p>
            </div>
            <!-- Pending -->
            <div class="bg-amber-50 rounded-lg p-4 border border-amber-200">
                <h3 class="font-semibold text-amber-900 uppercase text-sm tracking-wide">Belum Dibayar</h3>
                <p class="text-2xl font-bold text-amber-700 mt-1"><?= $pendingCount ?> item (Rp <?= number_format($pendingAmount, 0, ',', '.') ?>)</p>
            </div>
            <!-- Paid -->
            <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                <h3 class="font-semibold text-emerald-900 uppercase text-sm tracking-wide">Lunas</h3>
                <p class="text-2xl font-bold text-emerald-700 mt-1"><?= $paidCount ?> item (Rp <?= number_format($paidAmount, 0, ',', '.') ?>)</p>
            </div>
        </div>

        <!-- ── CTA ── -->
        <div class="mt-6">
            <a href="/invoices/create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-md transition-colors inline-flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Invoice Baru
            </a>
        </div>
    </div>

    <!-- ── Invoice List Table ── -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Daftar Invoice</h2>

        <?php if (empty($invoices)): ?>
            <p class="text-slate-500 py-8 text-center">Belum ada invoice yang dibuat. <a href="/invoices/create" class="text-blue-600 hover:underline font-medium">Buat invoice pertama</a>.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">No Invoice</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Klien</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <?php foreach ($invoices as $row): ?>
                            <?php
                                $inv = $row['invoice'];
                                $clientName = $row['clientName'];
                                $status     = $inv['status'];
                                $badgeCls   = '';
                                $btnCls     = '';
                                $btnText    = '';

                                switch ($status) {
                                    case 'paid':
                                        $badgeCls  = 'bg-emerald-100 text-emerald-800';
                                        $btnCls    = 'bg-slate-800 hover:bg-slate-900 text-white';
                                        $btnText   = 'Lunas ✓';
                                        break;
                                    default:
                                        $badgeCls  = 'bg-amber-100 text-amber-800';
                                        $btnCls    = 'bg-emerald-600 hover:bg-emerald-700 text-white';
                                        $btnText   = 'Tandai Lunas';
                                        break;
                                }
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <!-- Invoice Number -->
                                <td class="px-4 py-3 font-medium text-slate-900><?= htmlspecialchars($inv['invoice_code']) ?></td>

                                <!-- Client Name -->
                                <td class="px-4 py-3 text-slate-700"><?= $clientName ?></td>

                                <!-- Due Date -->
                                <td class="px-4 py-3 text-slate-600">
                                    <?= date('d/m/Y', strtotime($inv['due_date'])) ?>
                                </td>

                                <!-- Total -->
                                <td class="px-4 py-3 text-right font-semibold text-slate-900">
                                    Rp <?= number_format((int)$inv['total_amount'], 0, ',', '.') ?>
                                </td>

                                <!-- Status Badge -->
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wide <?= $badgeCls ?>">
                                        <?= ($status === 'paid') ? 'Lunas' : 'Belum Dibayar' ?>
                                    </span>
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2 flex-wrap">
                                        <!-- Status Toggle -->
                                        <form action="/invoice/status/<?= $inv['id'] ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="text-xs font-medium px-2.5 py-1.5 rounded-md transition-colors <?= $btnCls ?>">
                                                <?= $btnText ?>
                                            </button>
                                        </form>

                                        <!-- WhatsApp -->
                                        <form action="/invoice/wa/<?= $inv['id'] ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-xs flex items-center gap-1 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                Tagih WA
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $this->endSection(); ?>
