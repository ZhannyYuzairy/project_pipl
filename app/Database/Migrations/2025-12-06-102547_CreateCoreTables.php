<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoreTables extends Migration
{
    public function up()
    {
        // ==========================
        // TABEL USERS
        // ==========================
        if (! $this->db->tableExists('users')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'username' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => false,
                ],
                'password_hash' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                // Kalau masih ada field "password" plain text lama, bisa ditambah juga:
                // 'password' => [
                //     'type'       => 'VARCHAR',
                //     'constraint' => '100',
                //     'null'       => true,
                // ],
                'role' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20', // 'owner' atau 'kasir'
                    'null'       => false,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('username', false, true); // unique key
            $this->forge->createTable('users', true);
        }

        // ==========================
        // TABEL PRODUCTS
        // ==========================
        if (! $this->db->tableExists('products')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'barcode' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => false,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                    'null'       => false,
                ],
                // kolom kategori dan brand akan ditambahkan di migration AddProductEnhancements
                'stok' => [
                    'type'       => 'INT',
                    'null'       => false,
                    'default'    => 0,
                ],
                'harga_beli' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'harga_jual' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'satuan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'null'       => false,
                    'default'    => 'pcs',
                ],
                'gambar' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('barcode', false, true); // unique
            $this->forge->createTable('products', true);
        }

        // ==========================
        // TABEL SALES (TRANSAKSI)
        // ==========================
        if (! $this->db->tableExists('sales')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'invoice_no' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => false,
                ],
                'sale_date' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
                'user_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                    'null'     => true, // kasir bisa null kalau entah kenapa
                ],
                'total_amount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'amount_paid' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'change_amount' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'payment_method' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '30', // 'cash', 'qris', dll
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('invoice_no', false, true); // unique
            $this->forge->createTable('sales', true);
        }

        // ==========================
        // TABEL SALE_ITEMS (DETAIL TRANSAKSI)
        // ==========================
        if (! $this->db->tableExists('sale_items')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'sale_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                    'null'     => false,
                ],
                'product_id' => [
                    'type'     => 'INT',
                    'unsigned' => true,
                    'null'     => false,
                ],
                'qty' => [
                    'type'       => 'INT',
                    'null'       => false,
                    'default'    => 1,
                ],
                'price' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'subtotal' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '15,2',
                    'null'       => false,
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('sale_id');
            $this->forge->addKey('product_id');
            // Tidak pakai foreign key di sini supaya tidak bentrok engine / existing table
            $this->forge->createTable('sale_items', true);
        }
    }

    public function down()
    {
        // urutan kebalikan
        if ($this->db->tableExists('sale_items')) {
            $this->forge->dropTable('sale_items', true);
        }

        if ($this->db->tableExists('sales')) {
            $this->forge->dropTable('sales', true);
        }

        if ($this->db->tableExists('products')) {
            $this->forge->dropTable('products', true);
        }

        if ($this->db->tableExists('users')) {
            $this->forge->dropTable('users', true);
        }
    }
}
