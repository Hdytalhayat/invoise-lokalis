<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-slate-900">Profil Usaha</h2>
    
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <div class="mb-4 flex items-center gap-4">
        <a href="/dashboard" class="text-blue-600 hover:text-blue-700 font-medium hover:underline">&larr; Kembali ke Dashboard</a>
        <span class="text-slate-300">|</span>
        <form action="/logout" method="get" class="inline">
            <?= csrf_field() ?>
            <button type="submit" class="text-red-500 hover:text-red-700 font-medium hover:underline">Logout</button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4 text-slate-900">Informasi Bank</h3>
        
        <?php if (empty($user['bank_details'])): ?>
            <p class="text-slate-600 mb-4">Anda belum mengisi informasi bank. Silakan isi informasi berikut:</p>
        <?php else: ?>
            <div class="bg-slate-50 p-4 rounded mb-4">
                <pre><?= htmlspecialchars($user['bank_details']) ?></pre>
            </div>
        <?php endif; ?>
        
        <form action="/profile/bank" method="post">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label for="bank_details" class="block text-slate-700 text-sm font-medium mb-2">Informasi Bank</label>
                <textarea id="bank_details" name="bank_details" rows="4" 
                          class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Format: BCA: 1234-5678-90 (a.n. Nama Usaha)"><?= old('bank_details', $user['bank_details'] ?? '') ?></textarea>
                <p class="text-slate-500 text-sm mt-1">Contoh format: BCA: 1234-5678-90 (a.n. Nama Usaha)</p>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                Simpan Informasi Bank
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold mb-4 text-slate-900">QRIS Statis</h3>
        
        <?php if (!empty($user['qris_image'])): ?>
            <div class="mb-4">
                <p class="text-slate-600 mb-2">Gambar QRIS yang telah diunggah:</p>
                <img src="<?= base_url('uploads/qris/' . $user['qris_image']) ?>" alt="QRIS" class="max-w-xs h-auto border rounded">
            </div>
        <?php else: ?>
            <p class="text-slate-600 mb-4">Anda belum mengunggah gambar QRIS.</p>
        <?php endif; ?>
        
        <form action="/profile/qris" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label for="qris_image" class="block text-slate-700 text-sm font-medium mb-2">Upload Gambar QRIS (jpg/png max 2MB)</label>
                <input type="file" id="qris_image" name="qris_image" 
                       class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       accept="image/jpeg,image/png">
                <p class="text-slate-500 text-sm mt-1">Format gambar: JPG/PNG | Max ukuran: 2MB</p>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                Unggah QRIS
            </button>
        </form>
    </div>
</div>
<?php $this->endSection(); ?>