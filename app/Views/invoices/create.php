<?php $this->extend('layouts/main'); ?>

<?php $this->section('content'); ?>
<div class="max-w-4xl mx-auto">

    <!-- ── Header ── -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Buat Invoice Baru</h1>
                <p class="text-slate-700 mt-1">Isi data klien dan item tagihan.</p>
            </div>
            <a href="/dashboard" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">← Kembali</a>
        </div>

        <!-- ── Client Section (Select existing or create inline) ── -->
        <form action="/invoice/store" method="post" id="invoiceForm">
            <?= csrf_field() ?>

            <!-- Existing client selector -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Klien (Opsional)</label>
                <select name="client_id" id="existingClientSelect" onchange="toggleNewClient()" class="w-full border border-slate-300 rounded-md px-3 py-2.5 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    <option value="0">— Klien Baru —</option>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= esc($client['id'], 'html') ?>"
                            <?= (old('client_id', old('client_id', 0)) == $client['id']) ? 'selected' : '' ?>>
                            <?= esc($client['client_name']) ?> (<?= esc($client['client_phone']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Inline new client form -->
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mt-3" id="newClientForm">
                    <p class="text-sm font-semibold text-slate-600 mb-3">Data Klien Baru</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Nama Klien *</label>
                            <input type="text" name="client_name" value="<?= esc(old('client_name', '')) ?>" required
                                class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">No. WA *</label>
                            <input type="text" name="client_phone" value="<?= esc(old('client_phone', '')) ?>" placeholder="08xxxxxxxxxx" required
                                class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-500 mb-1">Email (Opsional)</label>
                            <input type="email" name="client_email" value="<?= esc(old('client_email', '')) ?>"
                                class="w-full border border-slate-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Due Date ── -->
            <div class="mb-6">
                <label for="due_date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Jatuh Tempo</label>
                <input type="date" name="due_date" id="due_date" value="<?= esc(old('due_date', date('Y-m-d', strtotime('+30 days')))) ?>"
                    class="w-full md:w-1/3 border border-slate-300 rounded-md px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- ── Line Items ── -->
            <div class="mb-6" id="itemsSection">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Item / Jasa</label>

                <table class="min-w-full border border-slate-200 rounded-lg overflow-hidden mb-3" id="itemsTable">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Deskripsi Item / Jasa</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-48">Harga (Rp)</th>
                            <th class="px-4 py-2.5 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="bg-white divide-y divide-slate-100">
                        <?php
                            // Render old input items (validation fallback)
                            $oldDescs = old('item_description', []);
                            if (is_array($oldDescs) && !empty($oldDescs)):
                                foreach ($oldDescs as $i => $desc): ?>
                                    <tr class="form-item">
                                        <td class="px-3 py-2">
                                            <input type="text" name="item_description[]" value="<?= esc($desc, 'html') ?>" required
                                                placeholder="Nama jasa / item..."
                                                class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        </td>
                                        <td class="px-3 py-2">
                                            <input type="text" name="item_price[]" value="<?= esc($oldDescs[$i] ?? '0', 'html') ?>" oninput="updateTotal()" data-price
                                                placeholder="0"
                                                class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-right text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-lg font-bold leading-none" title="Hapus item">×</button>
                                        </td>
                                    </tr>
                        <?php   endforeach;
                            else:
                                defaultRow();
                            endif;

                            function defaultRow(): void { ?>
                                <tr class="form-item">
                                    <td class="px-3 py-2">
                                        <input type="text" name="item_description[]" required placeholder="Nama jasa / item..."
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="text" name="item_price[]" oninput="updateTotal()" data-price placeholder="0"
                                            class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-right text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-lg font-bold leading-none" title="Hapus item">×</button>
                                    </td>
                                </tr>
                            <?php }
                        ?>
                    </tbody>
                </table>

                <!-- Add item + Total -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <button type="button" onclick="addItem()"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-md transition-colors inline-flex items-center gap-1.5 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Item
                    </button>

                    <div class="text-right">
                        <span class="text-slate-500 text-sm mr-2">Total: </span>
                        <span id="totalDisplay" class="text-xl font-bold text-blue-700">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- ── Submit / Cancel ── -->
            <div class="flex items-center gap-3 pt-2 border-t border-slate-100">
                <button type="submit" id="btnSubmit" disabled
                    class="bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-medium py-2.5 px-6 rounded-md transition-colors inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Invoice
                </button>
            </div>
        </form>
    </div>

</div>

<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('[data-price]').forEach(el => {
        const val = parseInt(el.value.replace(/\D/g, '')) || 0;
        total += val;
    });
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');

    // Enable submit if at least one item has a description and price
    const descs  = document.querySelectorAll('[name="item_description[]"]');
    let validRow = false;
    descs.forEach((input, i) => {
        const priceInput = input.closest('tr').querySelector('[data-price]');
        if (input.value.trim() !== '' && priceInput.value.replace(/\D/g, '') !== '') {
            validRow = true;
        }
    });
    document.getElementById('btnSubmit').disabled = !validRow;
}

function addItem() {
    const tbody  = document.getElementById('itemsBody');
    const row = document.createElement('tr');
    row.className = 'form-item';
    row.innerHTML = `
        <td class="px-3 py-2">
            <input type="text" name="item_description[]" required placeholder="Nama jasa / item..."
                class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </td>
        <td class="px-3 py-2">
            <input type="text" name="item_price[]" oninput="updateTotal()" data-price placeholder="0"
                class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-right text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </td>
        <td class="px-3 py-2 text-center">
            <button type="button" onclick="removeItem(this)" class="text-red-400 hover:text-red-600 text-lg font-bold leading-none" title="Hapus item">×</button>
        </td>
    `;
    tbody.appendChild(row);
    // Scroll to newly added row (visible on mobile)
    row.scrollIntoView({ behavior:'auto', block:'nearest' });
}

function removeItem(btn) {
    const tbody = document.getElementById('itemsBody');
    if (tbody.querySelectorAll('.form-item').length <= 1) {
        alert('Minimal harus ada satu item.');
        return;
    }
    btn.closest('tr').remove();
    updateTotal();
}

function toggleNewClient() {
    const select = document.getElementById('existingClientSelect');
    const form   = document.getElementById('newClientForm');
    form.style.display = (select.value !== '0') ? 'none' : 'block';
}

// Initialize on load: check if old selected anything
document.addEventListener('DOMContentLoaded', function () {
    const select     = document.getElementById('existingClientSelect');
    const newForm    = document.getElementById('newClientForm');
    if (select.value !== '0') {
        newForm.style.display = 'none';
    } else {
        newForm.style.display = 'block';
    }
});
</script>
<?php $this->endSection(); ?>
