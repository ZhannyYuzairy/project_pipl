<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Kalau sudah login, langsung lempar ke dashboard sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (! $username || ! $password) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Username dan password wajib diisi.');
        }

        // Cari user berdasarkan username
        $user = $userModel->where('username', $username)->first();

        if (! $user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Cek apakah user dinonaktifkan (kalau kolom is_active ada)
        if (isset($user['is_active']) && (int) $user['is_active'] === 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Akun ini telah dinonaktifkan oleh owner.');
        }

        // Cek password
        $valid = false;

        // Kalau di tabel ada kolom password_hash → gunakan password_verify
        if (isset($user['password_hash']) && $user['password_hash']) {
            $valid = password_verify($password, $user['password_hash']);
        }
        // Kalau cuma ada 'password' (plain text) → pakai pembanding biasa (kompatibilitas lama)
        elseif (isset($user['password']) && $user['password']) {
            $valid = ($user['password'] === $password);
        }

        if (! $valid) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        // Set session
        session()->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'] ?? $user['username'],
            'role'      => $user['role'],   // pastikan di DB isinya 'owner' atau 'kasir'
            'logged_in' => true,
        ]);

        // Redirect sesuai role
        return $this->redirectByRole($user['role']);
    }

    /**
     * Redirect user ke dashboard sesuai role.
     * Kalau role tidak dikenal, session dihapus supaya tidak terjadi redirect loop.
     */
    private function redirectByRole(?string $role)
    {
        $role = strtolower((string) $role);

        if ($role === 'owner') {
            return redirect()->to(site_url('owner/dashboard'));
        }

        if ($role === 'kasir') {
            return redirect()->to(site_url('kasir/dashboard'));
        }

        // Role nggak dikenal → bersihkan session dan paksa login ulang
        session()->destroy();

        return redirect()
            ->to(site_url('login'))
            ->with('error', 'Role akun tidak dikenali. Hubungi owner untuk pengaturan user.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
