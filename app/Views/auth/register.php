<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center text-slate-900">Buat Akun Baru</h2>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <form action="/register" method="post">
        <?= csrf_field() ?>
        
        <div class="mb-4">
            <label for="business_name" class="block text-slate-700 text-sm font-medium mb-2">Nama Usaha</label>
            <input type="text" id="business_name" name="business_name" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-slate-700 text-sm font-medium mb-2">Email</label>
            <input type="email" id="email" name="email" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-slate-700 text-sm font-medium mb-2">Password</label>
            <input type="password" id="password" name="password" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        
        <div class="mb-6">
            <label for="phone_number" class="block text-slate-700 text-sm font-medium mb-2">Nomor WhatsApp</label>
            <input type="text" id="phone_number" name="phone_number" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                   required>
        </div>
        
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
            Daftar
        </button>
    </form>
    
    <div class="mt-4 text-center">
        <p class="text-slate-600 text-sm">Sudah punya akun? <a href="/login" class="text-blue-600 hover:underline">Masuk di sini</a></p>
    </div>
</div>
<?php $this->endSection(); ?>