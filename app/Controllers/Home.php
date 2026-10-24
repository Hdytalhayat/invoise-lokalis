<?php
 
namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        // Redirect logged-in users to dashboard
        if (session()->get('user_id')) {
            return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . session()->get('business_name') . '!');
        }
        
        return view('home');
    }
}
