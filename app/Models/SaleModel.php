<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleModel extends Model
{
    protected $table      = 'sales';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'invoice_no', 'user_id', 'sale_date',
        'total_amount', 'total_cost',
        'payment_method', 'amount_paid', 'change_amount'
    ];

    protected $returnType = 'array';
}
