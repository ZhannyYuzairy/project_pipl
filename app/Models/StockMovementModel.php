<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table         = 'stock_movements';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // tidak dipakai

    protected $allowedFields = [
        'product_id',
        'movement_type',
        'qty',
        'stock_before',
        'stock_after',
        'note',
        'created_by',
    ];
}
