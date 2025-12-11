<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\SaleModel;

class Transaksi extends BaseController
{
    protected $saleModel;

    public function __construct()
    {
        $this->saleModel = new SaleModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');

        if (! $userId) {
            return redirect()->to(site_url('auth/login'));
        }

        // Ambil parameter filter
        $range     = $this->request->getGet('range') ?? 'today'; // today|yesterday|7days|all|custom
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');
        $q         = $this->request->getGet('q'); // search invoice

        // Normalisasi tanggal
        $today = date('Y-m-d');

        if ($range !== 'custom') {
            switch ($range) {
                case 'yesterday':
                    $startDate = date('Y-m-d', strtotime('-1 day'));
                    $endDate   = $startDate;
                    break;

                case '7days':
                    $startDate = date('Y-m-d', strtotime('-6 days'));
                    $endDate   = $today;
                    break;

                case 'all':
                    $startDate = null;
                    $endDate   = null;
                    break;

                case 'today':
                default:
                    $range     = 'today';
                    $startDate = $today;
                    $endDate   = $today;
                    break;
            }
        } else {
            // Range custom → pastikan format ada
            if (! $startDate && $endDate) {
                $startDate = $endDate;
            }
            if (! $endDate && $startDate) {
                $endDate = $startDate;
            }
        }

        // Build query
        $builder = $this->saleModel
            ->where('user_id', $userId);

        if ($startDate) {
            $builder->where('DATE(sale_date) >=', $startDate);
        }

        if ($endDate) {
            $builder->where('DATE(sale_date) <=', $endDate);
        }

        if ($q) {
            $builder->like('invoice_no', $q);
        }

        $builder->orderBy('sale_date', 'DESC');

        // Bisa pakai limit / paginate kalau mau
        $transaksi = $builder->findAll();

        $totalNominal = 0;
        foreach ($transaksi as $t) {
            $totalNominal += (float) $t['total_amount'];
        }

        $data = [
            'title'        => 'Riwayat Transaksi Kasir',
            'transaksi'    => $transaksi,
            'totalNominal' => $totalNominal,
            'range'        => $range,
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'q'            => $q,
        ];

        return view('kasir/transaksi/index', $data);
    }
}
