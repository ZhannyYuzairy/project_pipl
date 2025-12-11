<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\SaleModel;
use App\Models\UserModel;
use App\Models\ProductModel;
use Dompdf\Dompdf;
use Config\Database;

class Reports extends BaseController
{
    protected $saleModel;
    protected $userModel;
    protected $productModel;

    public function __construct()
    {
        $this->saleModel    = new SaleModel();
        $this->userModel    = new UserModel();
        $this->productModel = new ProductModel();
    }

    // ===================== LAPORAN PENJUALAN (WEB) =====================
    public function penjualan()
    {
        // Ambil filter dari query string
        $startDate   = $this->request->getGet('start_date');
        $endDate     = $this->request->getGet('end_date');
        $cashierId   = $this->request->getGet('cashier_id');
        $payMethod   = $this->request->getGet('payment_method');

        // Default: awal bulan ini s/d hari ini
        if (! $startDate) {
            $startDate = date('Y-m-01');
        }
        if (! $endDate) {
            $endDate = date('Y-m-d');
        }

        // Builder dasar
        $builder = $this->saleModel
            ->select('sales.*, users.nama AS kasir_nama')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('DATE(sales.sale_date) >=', $startDate)
            ->where('DATE(sales.sale_date) <=', $endDate);

        if (! empty($cashierId)) {
            $builder->where('sales.user_id', (int) $cashierId);
        }

        if (! empty($payMethod) && $payMethod !== 'all') {
            $builder->where('sales.payment_method', $payMethod);
        }

        $sales = $builder
            ->orderBy('sales.sale_date', 'DESC')
            ->findAll();

        // Hitung ringkasan
        $totalTransaksi = count($sales);
        $totalOmzet     = 0;
        $totalCash      = 0;
        $totalQris      = 0;

        foreach ($sales as $row) {
            $totalOmzet += (float) $row['total_amount'];

            $pm = strtolower((string) ($row['payment_method'] ?? ''));
            if ($pm === 'cash' || $pm === 'tunai') {
                $totalCash += (float) $row['total_amount'];
            } elseif ($pm === 'qris' || $pm === 'qris_ewallet') {
                $totalQris += (float) $row['total_amount'];
            }
        }

        // List kasir untuk dropdown filter
        $cashiers = $this->userModel
            ->where('role', 'kasir')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Laporan Penjualan',
            'sales'    => $sales,
            'cashiers' => $cashiers,
            'summary'  => [
                'total_transaksi' => $totalTransaksi,
                'total_omzet'     => $totalOmzet,
                'total_cash'      => $totalCash,
                'total_qris'      => $totalQris,
            ],
            'filters'  => [
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'cashier_id'     => $cashierId,
                'payment_method' => $payMethod ?: 'all',
            ],
        ];

        return view('owner/reports/penjualan', $data);
    }

    // ===================== EXPORT CSV LAPORAN PENJUALAN =====================
    public function penjualanExport()
    {
        $startDate   = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate     = $this->request->getGet('end_date') ?: date('Y-m-d');
        $cashierId   = $this->request->getGet('cashier_id');
        $payMethod   = $this->request->getGet('payment_method');

        $builder = $this->saleModel
            ->select('sales.*, users.nama AS kasir_nama')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('DATE(sales.sale_date) >=', $startDate)
            ->where('DATE(sales.sale_date) <=', $endDate);

        if (! empty($cashierId)) {
            $builder->where('sales.user_id', (int) $cashierId);
        }

        if (! empty($payMethod) && $payMethod !== 'all') {
            $builder->where('sales.payment_method', $payMethod);
        }

        $sales = $builder
            ->orderBy('sales.sale_date', 'DESC')
            ->findAll();

        $delimiter = ';';
        $lines     = [];

        // HEADER
        $headers = ['Tanggal', 'Invoice', 'Kasir', 'Metode Pembayaran', 'Total', 'Dibayar', 'Kembali'];
        $lines[] = implode($delimiter, $headers);

        foreach ($sales as $row) {
            $tanggal = date('Y-m-d H:i', strtotime($row['sale_date']));
            $invoice = $row['invoice_no'];
            $kasir   = $row['kasir_nama'] ?? '-';

            $pmRaw   = strtolower((string) ($row['payment_method'] ?? ''));
            $pmLabel = $row['payment_method'] ?? '-';
            if ($pmRaw === 'cash' || $pmRaw === 'tunai') {
                $pmLabel = 'Cash';
            } elseif ($pmRaw === 'qris' || $pmRaw === 'qris_ewallet') {
                $pmLabel = 'QRIS / e-Wallet';
            }

            $total   = (float) ($row['total_amount'] ?? 0);
            $dibayar = (float) ($row['amount_paid'] ?? 0);
            $kembali = (float) ($row['change_amount'] ?? 0);

            $lines[] = implode($delimiter, [
                $tanggal,
                $invoice,
                $kasir,
                $pmLabel,
                $total,
                $dibayar,
                $kembali,
            ]);
        }

        // Tambah BOM supaya aman di Excel
        $csvContent = "\xEF\xBB\xBF" . implode("\n", $lines);

        $filename = 'laporan_penjualan_' . date('Ymd_His') . '.csv';

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($csvContent);
    }

    // ===================== EXPORT PDF LAPORAN PENJUALAN =====================
    public function penjualanPdf()
    {
        date_default_timezone_set('Asia/Jakarta');
        $startDate   = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate     = $this->request->getGet('end_date') ?: date('Y-m-d');
        $cashierId   = $this->request->getGet('cashier_id');
        $payMethod   = $this->request->getGet('payment_method');

        $builder = $this->saleModel
            ->select('sales.*, users.nama AS kasir_nama')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('DATE(sales.sale_date) >=', $startDate)
            ->where('DATE(sales.sale_date) <=', $endDate);

        if (! empty($cashierId)) {
            $builder->where('sales.user_id', (int) $cashierId);
        }

        if (! empty($payMethod) && $payMethod !== 'all') {
            $builder->where('sales.payment_method', $payMethod);
        }

        $sales = $builder
            ->orderBy('sales.sale_date', 'DESC')
            ->findAll();

        // Ringkasan
        $totalTransaksi = count($sales);
        $totalOmzet     = 0;
        $totalCash      = 0;
        $totalQris      = 0;

        foreach ($sales as $row) {
            $totalOmzet += (float) $row['total_amount'];

            $pm = strtolower((string) ($row['payment_method'] ?? ''));
            if ($pm === 'cash' || $pm === 'tunai') {
                $totalCash += (float) $row['total_amount'];
            } elseif ($pm === 'qris' || $pm === 'qris_ewallet') {
                $totalQris += (float) $row['total_amount'];
            }
        }

        // (opsional) list kasir kalau suatu saat mau ditampilkan di PDF
        $cashiers = $this->userModel
            ->where('role', 'kasir')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Laporan Penjualan',
            'sales'    => $sales,
            'cashiers' => $cashiers,
            'summary'  => [
                'total_transaksi' => $totalTransaksi,
                'total_omzet'     => $totalOmzet,
                'total_cash'      => $totalCash,
                'total_qris'      => $totalQris,
            ],
            'filters'  => [
                'start_date'     => $startDate,
                'end_date'       => $endDate,
                'cashier_id'     => $cashierId,
                'payment_method' => $payMethod ?: 'all',
            ],
        ];

        $html = view('owner/reports/penjualan_pdf', $data);

        $dompdf = new Dompdf([
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan_penjualan_' . date('Ymd_His') . '.pdf';

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

    // ===================== LAPORAN STOK (WEB) =====================
    public function stok()
    {
        $products = $this->productModel
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Laporan Stok',
            'products' => $products,
        ];

        return view('owner/reports/stok', $data);
    }

    // ===================== EXPORT PDF LAPORAN STOK =====================
    public function stokPdf()
    {
        date_default_timezone_set('Asia/Jakarta');
        $products = $this->productModel
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title'        => 'Laporan Stok',
            'products'     => $products,
            'generated_at' => date('Y-m-d H:i'),
        ];

        $html = view('owner/reports/stok_pdf', $data);

        $dompdf = new Dompdf([
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan_stok_' . date('Ymd_His') . '.pdf';

        return $dompdf->stream($filename, ['Attachment' => true]);
    }

    // ===================== LAPORAN LABA RUGI (WEB) =====================
    public function labaRugi()
    {
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?: date('Y-m-d');

        $db = Database::connect();

        $builder = $db->table('sale_items')
            ->select('
                sales.id,
                sales.invoice_no,
                sales.sale_date,
                users.nama AS kasir_nama,
                SUM(sale_items.subtotal) AS total_penjualan,
                SUM(sale_items.subtotal_cost) AS total_modal,
                SUM(sale_items.subtotal) - SUM(sale_items.subtotal_cost) AS total_laba
            ')
            ->join('sales', 'sales.id = sale_items.sale_id', 'inner')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('DATE(sales.sale_date) >=', $startDate)
            ->where('DATE(sales.sale_date) <=', $endDate)
            ->groupBy('sales.id')
            ->orderBy('sales.sale_date', 'DESC');

        $rows = $builder->get()->getResultArray();

        $grandPenjualan = 0;
        $grandModal     = 0;
        $grandLaba      = 0;

        foreach ($rows as $r) {
            $grandPenjualan += (float) $r['total_penjualan'];
            $grandModal     += (float) $r['total_modal'];
            $grandLaba      += (float) $r['total_laba'];
        }

        $data = [
            'title'   => 'Laporan Laba & Rugi',
            'rows'    => $rows,
            'summary' => [
                'total_penjualan' => $grandPenjualan,
                'total_modal'     => $grandModal,
                'total_laba'      => $grandLaba,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
        ];

        return view('owner/reports/laba_rugi', $data);
    }

    // ===================== EXPORT PDF LAPORAN LABA RUGI =====================
    public function labaRugiPdf()
    {
        date_default_timezone_set('Asia/Jakarta');
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?: date('Y-m-d');

        $db = Database::connect();

        $builder = $db->table('sale_items')
            ->select('
                sales.id,
                sales.invoice_no,
                sales.sale_date,
                users.nama AS kasir_nama,
                SUM(sale_items.subtotal) AS total_penjualan,
                SUM(sale_items.subtotal_cost) AS total_modal,
                SUM(sale_items.subtotal) - SUM(sale_items.subtotal_cost) AS total_laba
            ')
            ->join('sales', 'sales.id = sale_items.sale_id', 'inner')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->where('DATE(sales.sale_date) >=', $startDate)
            ->where('DATE(sales.sale_date) <=', $endDate)
            ->groupBy('sales.id')
            ->orderBy('sales.sale_date', 'DESC');

        $rows = $builder->get()->getResultArray();

        $grandPenjualan = 0;
        $grandModal     = 0;
        $grandLaba      = 0;

        foreach ($rows as $r) {
            $grandPenjualan += (float) $r['total_penjualan'];
            $grandModal     += (float) $r['total_modal'];
            $grandLaba      += (float) $r['total_laba'];
        }

        $data = [
            'title'   => 'Laporan Laba & Rugi',
            'rows'    => $rows,
            'summary' => [
                'total_penjualan' => $grandPenjualan,
                'total_modal'     => $grandModal,
                'total_laba'      => $grandLaba,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date'   => $endDate,
            ],
        ];

        $html = view('owner/reports/laba_rugi_pdf', $data);

        $dompdf = new Dompdf([
            'isRemoteEnabled' => true,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'laporan_laba_rugi_' . date('Ymd_His') . '.pdf';

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
