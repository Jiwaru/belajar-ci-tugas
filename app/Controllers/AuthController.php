<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;
use App\Models\DiskonModel;

class AuthController extends BaseController
{
protected $user;
    protected $diskon;

function __construct()
{
helper('form');
$this->user = new UserModel();
        $this->diskon = new DiskonModel();
}

public function login()
{
if ($this->request->getPost()) {
$rules = [
'username' => 'required|min_length[6]',
'password' => 'required|min_length[7]|numeric',
];

if ($this->validate($rules)) {
$username = $this->request->getVar('username');
$password = $this->request->getVar('password');

$dataUser = $this->user->where('username', $username)->first(); //pw 1234567 //123

if($dataUser) {
if (password_verify($password, $dataUser['password'])) {
                        // Ambil tanggal hari ini
                        $today = date('Y-m-d');
                        
                        // Cari diskon yang berlaku pada tanggal ini
                        $diskon = $this->diskon->getDiskonByDate($today);

                        // Simpan data diskon ke session jika ada
                        if ($diskon) {
                            session()->set('diskon', $diskon); // Menyimpan data diskon di session
                        }

session()->set([
'username' => $dataUser['username'],
'role' => $dataUser['role'],
'isLoggedIn' => TRUE
]);

return redirect()->to(base_url('/'));
} else { 
session()->setFlashdara('failed', 'Kombinasi Username dan Password Salah');
return redirect()->back();
}
} else {
session()->setFlashdata('failed', 'Username Tidak Ditemukan');
return redirect()->back();
}
} else {
session()->setFlashdata('failed', $this->validator->listErrors());
return redirect()->back();
}
}

return view('v_login');
}

public function logout()
{
session()->destroy();
return redirect()->to('login');
}
}