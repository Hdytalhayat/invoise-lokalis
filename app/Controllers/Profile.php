<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        
        // Middleware auth filter
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return redirect()->to('/login')->with('error', 'User tidak ditemukan.');
        }
        
        return view('profile/index', ['user' => $user]);
    }

    public function updateBank()
    {
        if ($this->request->getMethod() === 'post') {
            $userId = session()->get('user_id');
            
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'bank_details' => 'max_length[500]'
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
            }

            $userData = [
                'bank_details' => $this->request->getPost('bank_details')
            ];
            
            try {
                $this->userModel->update($userData, $userId);
                
                return redirect()->back()->with('success', 'Informasi bank berhasil diperbarui.');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal memperbarui informasi bank. Silakan coba lagi.');
            }
        }
        
        return redirect()->to('/profile');
    }

    public function uploadQris()
    {
        if ($this->request->getMethod() === 'post') {
            $userId = session()->get('user_id');
            
            // Validate file upload
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'qris_image' => 'uploaded[qris_image]|max_size[qris_image,2048]|is_image[qris_image]|mime_in[qris_image,image/jpeg,image/png]'
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('error', 'Upload gambar QRIS gagal. Pastikan file berupa JPG/PNG maks 2MB.');
            }

            $file = $this->request->getFile('qris_image');
            
            if ($file->isValid() && !$file->hasMoved()) {
                // Generate unique filename
                $newName = 'qris_' . uniqid() . '.' . $file->getExtension();
                
                // Create directory if not exists
                $uploadDir = WRITEPATH . 'uploads/qris/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Move file
                $file->move($uploadDir, $newName);
                
                // Update database
                $userData = [
                    'qris_image' => $newName
                ];
                
                try {
                    $this->userModel->update($userData, $userId);
                    
                    return redirect()->back()->with('success', 'Gambar QRIS berhasil diunggah.');
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Gagal mengunggah gambar QRIS. Silakan coba lagi.');
                }
            } else {
                return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengunggah file.');
            }
        }
        
        return redirect()->to('/profile');
    }
}
