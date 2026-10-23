<?php

namespace App\Models;

use CodeIgniter\Model;

class Invoice extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_code',
        'user_id',
        'client_id',
        'total_amount',
        'status',
        'due_date'
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
        'invoice_code' => 'required|max_length[32]|is_unique[invoices.invoice_code,id,{id}]',
        'user_id'      => 'required|numeric',
        'client_id'    => 'required|numeric',
        'total_amount' => 'required|numeric',
        'status'       => 'required|in_list[unpaid,paid,overdue]',
        'due_date'     => 'required|date'
    ];
    protected $validationMessages   = [
        'invoice_code' => [
            'required' => 'Kode invoice wajib diisi',
            'max_length' => 'Kode invoice maksimal 32 karakter',
            'is_unique' => 'Kode invoice sudah digunakan'
        ],
        'user_id' => [
            'required' => 'ID pengguna wajib diisi',
            'numeric' => 'ID pengguna harus berupa angka'
        ],
        'client_id' => [
            'required' => 'ID klien wajib diisi',
            'numeric' => 'ID klien harus berupa angka'
        ],
        'total_amount' => [
            'required' => 'Total tagihan wajib diisi',
            'numeric' => 'Total tagihan harus berupa angka'
        ],
        'status' => [
            'required' => 'Status wajib diisi',
            'in_list' => 'Status tidak valid'
        ],
        'due_date' => [
            'required' => 'Tanggal jatuh tempo wajib diisi',
            'date' => 'Format tanggal tidak valid'
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
