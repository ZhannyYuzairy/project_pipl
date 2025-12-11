<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'barcode',
        'nama',
        'category_id',   // ✅ baru
        'brand',         // ✅ baru
        'stok',
        'harga_beli',
        'harga_jual',
        'satuan',
        'gambar',
    ];
}
