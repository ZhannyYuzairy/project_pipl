<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProductEnhancements extends Migration
{
    public function up()
    {
        $db = db_connect();

        // ==========================
        // 1. TABEL product_categories
        // ==========================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '120',
                'null'       => true,
            ],
            'keterangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('product_categories', true);



        // ==========================
        // 2. TAMBAH KOLOM KE products (SAFE)
        // ==========================
        $fields = [];

        if (! $db->fieldExists('category_id', 'products')) {
            $fields['category_id'] = [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'nama',
            ];
        }

        if (! $db->fieldExists('brand', 'products')) {
            $fields['brand'] = [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => isset($fields['category_id']) ? 'category_id' : 'nama',
            ];
        }

        if (! empty($fields)) {
            $this->forge->addColumn('products', $fields);
        }



        // ==========================
        // 3. TABEL RIWAYAT STOK (TANPA FOREIGN KEY)
        // ==========================
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true
            ],
            'product_id' => [
                'type'     => 'INT',
                'unsigned' => true
            ],
            'movement_type' => [
                'type'    => 'ENUM("in","out","adjust")',
                'default' => 'adjust'
            ],
            'qty' => [
                'type' => 'INT',
                'null' => false
            ],
            'stock_before' => [
                'type' => 'INT',
                'null' => false
            ],
            'stock_after' => [
                'type' => 'INT',
                'null' => false
            ],
            'note' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_by' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP'
            ]
        ]);

        $this->forge->addKey('id', true);

        // ❌ Tidak pakai addForeignKey lagi, biar nggak error kalau engine/table beda
        $this->forge->createTable('stock_movements', true);
    }



    public function down()
    {
        $db = db_connect();

        // Hapus tabel riwayat & kategori kalau ada
        if ($db->tableExists('stock_movements')) {
            $this->forge->dropTable('stock_movements', true);
        }

        if ($db->tableExists('product_categories')) {
            $this->forge->dropTable('product_categories', true);
        }

        // Hapus kolom dari products kalau memang ada
        if ($db->fieldExists('category_id', 'products')) {
            $this->forge->dropColumn('products', 'category_id');
        }

        if ($db->fieldExists('brand', 'products')) {
            $this->forge->dropColumn('products', 'brand');
        }
    }
}
