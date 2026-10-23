<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-3xl font-bold mb-4 text-slate-900">Selamat Datang di InvoiseLokalis</h1>
        <p class="text-lg text-slate-700 mb-6">
            Platform invoice sederhana untuk UMKM lokal di Indonesia. Buat dan kirim tagihan, terima pembayaran dengan QRIS statis atau transfer bank.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-slate-50 rounded-lg p-6 text-center">
                <div class="text-blue-600 text-3xl mb-4">📝</div>
                <h3 class="font-semibold text-lg mb-2">Buat Invoice</h3>
                <p class="text-slate-600">Buat invoice untuk klien Anda dengan mudah dan cepat.</p>
            </div>
            
            <div class="bg-slate-50 rounded-lg p-6 text-center">
                <div class="text-blue-600 text-3xl mb-4">💳</div>
                <h3 class="font-semibold text-lg mb-2">Terima Pembayaran</h3>
                <p class="text-slate-600">Terima pembayaran melalui QRIS statis atau transfer bank.</p>
            </div>
            
            <div class="bg-slate-50 rounded-lg p-6 text-center">
                <div class="text-blue-600 text-3xl mb-4">📊</div>
                <h3 class="font-semibold text-lg mb-2">Lacak Status</h3>
                <p class="text-slate-600">Lacak status pembayaran dan kirim pengingat via WhatsApp.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Mulai Sekarang</h2>
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="/register" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors text-center">
                Daftar Akun
            </a>
            <a href="/login" class="border border-slate-300 hover:border-slate-400 text-slate-700 font-medium py-2 px-6 rounded-lg transition-colors text-center">
                Masuk ke Akun
            </a>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>