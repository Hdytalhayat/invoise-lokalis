<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-2xl font-bold mb-2 text-slate-900">Selamat datang, <?= htmlspecialchars(session()->get('business_name')) ?>!</h1>
        <p class="text-slate-700 mb-6">Berikut adalah daftar invoice yang telah Anda buat.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <h3 class="font-semibold text-blue-900">Total Tagihan</h3>
                <p class="text-2xl font-bold text-blue-700">Rp 0</p>
            </div>
            <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-100">
                <h3 class="font-semibold text-emerald-900">Lunas</h3>
                <p class="text-2xl font-bold text-emerald-700">Rp 0</p>
            </div>
            <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                <h3 class="font-semibold text-amber-900">Belum Dibayar</h3>
                <p class="text-2xl font-bold text-amber-700">Rp 0</p>
            </div>
        </div>
        
        <a href="/invoices/create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors inline-block">
            Buat Invoice Baru
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Invoice Terbaru</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Klien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tgl Jatuh Tempo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-slate-500">Belum ada invoice yang dibuat</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>