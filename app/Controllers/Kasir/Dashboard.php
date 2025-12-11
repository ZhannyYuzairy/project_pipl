<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\SaleModel;

class Dashboard extends BaseController
{
    protected $saleModel;

    public function __construct()
    {
        $this->saleModel = new SaleModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $namaKasir = session()->get('nama') ?? 'Kasir';

        // Tanggal hari ini (format YYYY-mm-dd)
        $today = date('Y-m-d');

        // Ambil total penjualan & jumlah transaksi kasir hari ini
        // (kalau kamu sudah punya query versi lain, boleh pakai punyamu;
        // yang penting hasil akhirnya $totalHariIni & $trxHariIni)
        $row = $this->saleModel
            ->select('COALESCE(SUM(total_amount), 0) AS total_hari_ini, COUNT(*) AS trx_hari_ini')
            ->where('user_id', $userId)
            ->where('DATE(sale_date)', $today)
            ->first();

        $totalHariIni = (float) ($row['total_hari_ini'] ?? 0);
        $trxHariIni   = (int)   ($row['trx_hari_ini'] ?? 0);
        $avgTicket    = $trxHariIni > 0 ? $totalHariIni / $trxHariIni : 0;

        $data = [
            'title'        => 'Dashboard Kasir',
            'namaKasir'    => $namaKasir,
            'totalHariIni' => $totalHariIni,
            'trxHariIni'   => $trxHariIni,
            'avgTicket'    => $avgTicket,
        ];

        return view('kasir/dashboard', $data);
    }
}
