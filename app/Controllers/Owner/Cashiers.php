<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Cashiers extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // ================== LIST KASIR ==================
    public function index()
    {
        $cashiers = $this->userModel
            ->where('role', 'kasir')
            ->orderBy('nama', 'ASC')
            ->findAll();

        return view('owner/cashiers/index', [
            'title'    => 'Manajemen Kasir',
            'cashiers' => $cashiers,
        ]);
    }

    // ================== CREATE ==================
    public function create()
    {
        return view('owner/cashiers/create', [
            'title' => 'Tambah Kasir',
        ]);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|is_unique[users.username]',
            'nama'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $this->userModel->insert([
            'nama'          => $this->request->getPost('nama'),
            'username'      => $this->request->getPost('username'),
            'role'          => 'kasir',
            'password_hash' => password_hash('password', PASSWORD_DEFAULT),
            'is_active'     => 1,
        ]);

        return redirect()->to(site_url('owner/cashiers'))
            ->with('success', 'Kasir berhasil dibuat (password: password).');
    }

    // ================== EDIT ==================
    public function edit($id)
    {
        $kasir = $this->userModel->find($id);

        if (!$kasir || $kasir['role'] !== 'kasir') {
            return redirect()->to(site_url('owner/cashiers'))
                ->with('error', 'Kasir tidak ditemukan.');
        }

        return view('owner/cashiers/edit', [
            'title' => 'Edit Kasir',
            'kasir' => $kasir,
        ]);
    }

    public function update($id)
    {
        $kasir = $this->userModel->find($id);

        if (!$kasir) {
            return redirect()->to(site_url('owner/cashiers'))->with('error', 'Kasir tidak ditemukan.');
        }

        $rules = [
            'nama' => 'required',
        ];

        // Validasi username unik
        if ($kasir['username'] !== $this->request->getPost('username')) {
            $rules['username'] = 'required|is_unique[users.username]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $this->userModel->update($id, [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ]);

        return redirect()->to(site_url('owner/cashiers'))
            ->with('success', 'Data kasir berhasil diperbarui.');
    }

    // ================== AKTIF / NONAKTIF ==================
    public function activate($id)
    {
        $this->userModel->setKasirActive($id, 1);
        return redirect()->to(site_url('owner/cashiers'))
            ->with('success', 'Kasir berhasil diaktifkan kembali.');
    }

    public function deactivate($id)
    {
        $this->userModel->setKasirActive($id, 0);
        return redirect()->to(site_url('owner/cashiers'))
            ->with('success', 'Kasir berhasil dinonaktifkan.');
    }

    // ================== RESET PASSWORD ==================
    public function resetPassword($id)
    {
        $this->userModel->resetPasswordKasir($id);
        return redirect()->to(site_url('owner/cashiers'))
            ->with('success', 'Password kasir berhasil direset menjadi: password');
    }
}
