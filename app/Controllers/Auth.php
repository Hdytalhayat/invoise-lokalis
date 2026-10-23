<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\User;

class Auth extends BaseController
{
    public function index()
    {
        return redirect()->to('/login');
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'business_name' => 'required|max_length[100]',
                'email'         => 'required|valid_email|is_unique[users.email]',
                'password'      => 'required|min_length[8]',
                'phone_number'  => 'required|max_length[20]'
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
            }

            $userModel = new User();
            
            // Hash password
            $passwordHash = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            
            $userData = [
                'business_name' => $this->request->getPost('business_name'),
                'email'         => $this->request->getPost('email'),
                'password_hash' => $passwordHash,
                'phone_number'  => $this->request->getPost('phone_number')
            ];
            
            try {
                $userId = $userModel->insert($userData);
                
                // Set session
                session()->set([
                    'user_id' => $userId,
                    'email'   => $this->request->getPost('email'),
                    'business_name' => $this->request->getPost('business_name')
                ]);
                
                return redirect()->to('/dashboard')->with('success', 'Akun berhasil dibuat. Selamat datang!');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', 'Gagal membuat akun. Silakan coba lagi.');
            }
        }
        
        return view('auth/register');
    }

    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard');
        }
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $validation = \Config\Services::validation();
            
            $validation->setRules([
                'email'    => 'required|valid_email',
                'password' => 'required'
            ]);

            if (!$validation->run($this->request->getPost())) {
                return redirect()->back()->withInput()->with('error', 'Email atau password tidak valid.');
            }
            
            $userModel = new User();
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $user = $userModel->where('email', $email)->first();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Set session
                session()->set([
                    'user_id' => $user['id'],
                    'email'   => $user['email'],
                    'business_name' => $user['business_name']
                ]);
                
                return redirect()->to('/dashboard')->with('success', 'Login berhasil.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Email atau password tidak valid.');
            }
        }
        
        return view('auth/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah keluar dari akun.');
    }
}
