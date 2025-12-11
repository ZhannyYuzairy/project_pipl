<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['nama', 'username', 'password_hash', 'role', 'is_active','created_at', 'updated_at'];

    protected $returnType = 'array';

    // convenience method: bikin kasir baru
    public function createKasir(string $nama, string $username, string $plainPassword = 'password'): int
    {
        $data = [
            'nama'          => $nama,
            'username'      => $username,
            'role'          => 'kasir',
            'password_hash' => password_hash($plainPassword, PASSWORD_DEFAULT),
        ];

        $this->insert($data);

        return (int) $this->getInsertID();
    }

    public function getAllKasir(): array
    {
        return $this->where('role', 'kasir')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }
    
    public function setKasirActive($id, $status)
    {
        return $this->update($id, ['is_active' => $status]);
    }

    public function resetPasswordKasir($id)
    {
        return $this->update($id, [
            'password_hash' => password_hash('password', PASSWORD_DEFAULT)
        ]);
    }
}