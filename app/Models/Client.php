<?php

namespace App\Models;

use CodeIgniter\Model;

class Client extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'client_name',
        'client_phone',
        'client_email'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id' => 'required|numeric',
        'client_name' => 'required|max_length[100]',
        'client_phone' => 'required|max_length[20]'
    ];
    protected $validationMessages   = [
        'user_id' => [
            'required' => 'ID pengguna wajib diisi',
            'numeric' => 'ID pengguna harus berupa angka'
        ],
        'client_name' => [
            'required' => 'Nama klien wajib diisi',
            'max_length' => 'Nama klien maksimal 100 karakter'
        ],
        'client_phone' => [
            'required' => 'Nomor telepon klien wajib diisi',
            'max_length' => 'Nomor telepon maksimal 20 digit'
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
