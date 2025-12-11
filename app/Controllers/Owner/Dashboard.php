<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\SaleModel;
use App\Models\ProductModel;

class Dashboard extends BaseController
{
    protected $saleModel;
    protected $productModel;

    public function __construct()
    {
        $this->saleModel    = new SaleModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $today = date('Y-m-d');

        // Total penjualan hari ini (semua kasir)
        $totalRow = $this->saleModel
            ->selectSum('total_amount', 'totalPenjualan')
            ->where('DATE(sale_date)', $today)
            ->first();

        $totalPenjualan = $totalRow['totalPenjualan'] ?? 0;

        // Jumlah transaksi hari ini
        $totalTransaksi = $this->saleModel
            ->where('DATE(sale_date)', $today)
            ->countAllResults();

        // Jumlah produk aktif
        $totalProduk = $this->productModel->countAllResults();

        $data = [
            'title'          => 'Dashboard Owner',
            'totalPenjualan' => $totalPenjualan,
            'totalTransaksi' => $totalTransaksi,
            'totalProduk'    => $totalProduk,
        ];

        return view('owner/dashboard', $data);
    }
}
