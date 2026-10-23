<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceItem extends Model
{
    protected $table            = 'invoice_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_id',
        'description',
        'quantity',
        'price',
        'subtotal'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'invoice_id' => 'required|numeric',
        'description' => 'required|max_length[255]',
        'quantity' => 'required|numeric|min[1]',
        'price' => 'required|numeric',
        'subtotal' => 'required|numeric'
    ];
    protected $validationMessages   = [
        'invoice_id' => [
            'required' => 'ID invoice wajib diisi',
            'numeric' => 'ID invoice harus berupa angka'
        ],
        'description' => [
            'required' => 'Deskripsi wajib diisi',
            'max_length' => 'Deskripsi maksimal 255 karakter'
        ],
        'quantity' => [
            'required' => 'Jumlah wajib diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'min' => 'Jumlah minimal 1'
        ],
        'price' => [
            'required' => 'Harga wajib diisi',
            'numeric' => 'Harga harus berupa angka'
        ],
        'subtotal' => [
            'required' => 'Subtotal wajib diisi',
            'numeric' => 'Subtotal harus berupa angka'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
