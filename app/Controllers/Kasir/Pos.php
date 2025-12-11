<?php

namespace App\Controllers\Kasir;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use App\Models\UserModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;

class Pos extends BaseController
{
    protected $productModel;
    protected $saleModel;
    protected $saleItemModel;
    protected $userModel;

    // HANYA 2 metode pembayaran
    protected $allowedPaymentMethods = ['cash', 'qris'];

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->saleModel     = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'POS - Toko Z&Z',
        ];

        return view('kasir/pos/index', $data);
    }

    // ================== AJAX SEARCH PRODUK ==================
    public function searchProductAjax()
    {
        if (! $this->request->isAJAX()) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status'  => 'error',
                    'message' => 'Bad request',
                ]);
        }

        $q = trim((string) $this->request->getGet('q'));

        if ($q === '') {
            return $this->response->setJSON([
                'status' => 'ok',
                'data'   => [],
            ]);
        }

        $products = $this->productModel
            ->select('id, nama, barcode, harga_jual, stok, satuan')
            ->groupStart()
                ->like('nama', $q)
                ->orLike('barcode', $q)
            ->groupEnd()
            ->orderBy('nama', 'ASC')
            ->limit(10)
            ->find();

        return $this->response->setJSON([
            'status' => 'ok',
            'data'   => $products,
        ]);
    }

    // ================== CART: ADD ITEM ==================
    public function addItem()
    {
        $keyword = trim((string) $this->request->getPost('barcode'));
        $qty     = (int) $this->request->getPost('qty');

        if ($keyword === '' || $qty <= 0) {
            return redirect()
                ->back()
                ->with('error', 'Barcode / nama barang dan qty harus diisi.');
        }

        // 1) cari barcode
        $product = $this->productModel
            ->where('barcode', $keyword)
            ->first();

        // 2) cari nama persis
        if (! $product) {
            $product = $this->productModel
                ->where('nama', $keyword)
                ->first();
        }

        // 3) cari LIKE nama
        if (! $product) {
            $product = $this->productModel
                ->like('nama', $keyword)
                ->orderBy('nama', 'ASC')
                ->first();
        }

        if (! $product) {
            return redirect()
                ->back()
                ->with('error', 'Produk dengan barcode / nama tersebut tidak ditemukan.');
        }

        $cart = session()->get('cart') ?? [];
        $productId = (int) $product['id'];

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $qty;
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'barcode'    => $product['barcode'],
                'nama'       => $product['nama'],
                'qty'        => $qty,
                'harga_beli' => (float) $product['harga_beli'],
                'harga_jual' => (float) $product['harga_jual'],
            ];
        }

        session()->set('cart', $cart);

        $msg = 'Barang "' . $product['nama'] . '" (qty ' . $qty . ') ditambahkan ke keranjang.';
        return redirect()
            ->to(site_url('kasir/pos'))
            ->with('cart_success', $msg);
    }

    // ================== CART: UPDATE QTY (GET) ==================
    public function updateQty()
    {
        $productId = (int) $this->request->getGet('product_id');
        $qty       = (int) $this->request->getGet('qty');

        $cart = session()->get('cart') ?? [];

        if (! isset($cart[$productId])) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Item tidak ditemukan di keranjang.');
        }

        if ($qty <= 0) {
            unset($cart[$productId]);
            session()->set('cart', $cart);

            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('success', 'Item dihapus dari keranjang.');
        }

        $cart[$productId]['qty'] = $qty;
        session()->set('cart', $cart);

        return redirect()
            ->to(site_url('kasir/pos'))
            ->with('success', 'Jumlah item berhasil diupdate.');
    }

    // ================== CART: REMOVE ITEM (GET) ==================
    public function removeItem()
    {
        $productId = (int) $this->request->getGet('product_id');
        $cart      = session()->get('cart') ?? [];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->set('cart', $cart);

            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('success', 'Item berhasil dihapus dari keranjang.');
        }

        return redirect()
            ->to(site_url('kasir/pos'))
            ->with('error', 'Item tidak ditemukan di keranjang.');
    }

    // ================== CART: CLEAR (GET) ==================
    public function clearCart()
    {
        session()->remove('cart');

        return redirect()
            ->to(site_url('kasir/pos'))
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }

    // ================== CHECKOUT ==================
    public function checkout()
    {
        $cart = session()->get('cart') ?? [];

        if (empty($cart)) {
            return redirect()
                ->back()
                ->with('error', 'Cart masih kosong.');
        }

        // --- Cek stok dulu ---
        $productIds = array_keys($cart);
        $products   = [];

        if (! empty($productIds)) {
            $products = $this->productModel
                ->select('id, nama, barcode, stok')
                ->whereIn('id', $productIds)
                ->findAll();
        }

        $stokMap = [];
        foreach ($products as $p) {
            $stokMap[(int) $p['id']] = (int) $p['stok'];
        }

        $stokKurang = [];

        foreach ($cart as $productId => $item) {
            $stokSistem = $stokMap[$productId] ?? 0;
            $qtyCart    = (int) $item['qty'];

            if ($stokSistem < $qtyCart) {
                $stokKurang[] = [
                    'product_id'   => $productId,
                    'nama'         => $item['nama'],
                    'barcode'      => $item['barcode'],
                    'qty_cart'     => $qtyCart,
                    'stok_sistem'  => $stokSistem,
                    'selisih'      => $qtyCart - $stokSistem,
                ];
            }
        }

        if (! empty($stokKurang)) {
            session()->setFlashdata('error', 'Beberapa item stoknya tidak mencukupi. Silakan sesuaikan qty di keranjang.');
            session()->setFlashdata('stock_errors', $stokKurang);

            return redirect()
                ->back()
                ->withInput();
        }

        // --- Pembayaran ---
        $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';
        $amountPaid    = (float) $this->request->getPost('amount_paid');

        if (! in_array($paymentMethod, $this->allowedPaymentMethods, true)) {
            return redirect()
                ->back()
                ->with('error', 'Metode pembayaran tidak dikenal.')
                ->withInput();
        }

        $totalAmount = 0;
        $totalCost   = 0;

        foreach ($cart as $item) {
            $lineTotal = $item['qty'] * $item['harga_jual'];
            $lineCost  = $item['qty'] * $item['harga_beli'];

            $totalAmount += $lineTotal;
            $totalCost   += $lineCost;
        }

        if ($paymentMethod === 'cash') {
            if ($amountPaid < $totalAmount) {
                return redirect()
                    ->back()
                    ->with('error', 'Uang yang diterima kurang dari total belanja.');
            }
        } else {
            // QRIS → dianggap lunas pas
            $amountPaid = $totalAmount;
        }

        $change = $amountPaid - $totalAmount;

        $userId = session()->get('user_id') ?? null;

        // Waktu WIB
        $nowWib    = Time::now('Asia/Jakarta');
        $invoiceNo = 'INV' . $nowWib->format('YmdHis');
        $saleDate  = $nowWib->toDateTimeString();

        $db = \Config\Database::connect();
        $db->transStart();

        // Simpan sale
        $saleId = $this->saleModel->insert([
            'invoice_no'     => $invoiceNo,
            'user_id'        => $userId,
            'sale_date'      => $saleDate,
            'total_amount'   => $totalAmount,
            'total_cost'     => $totalCost,
            'payment_method' => $paymentMethod,
            'amount_paid'    => $amountPaid,
            'change_amount'  => $change,
        ], true);

        // Simpan items + update stok
        $productTable = $db->table('products');

        foreach ($cart as $item) {
            $this->saleItemModel->insert([
                'sale_id'       => $saleId,
                'product_id'    => $item['product_id'],
                'qty'           => $item['qty'],
                'price'         => $item['harga_jual'],
                'cost'          => $item['harga_beli'],
                'subtotal'      => $item['qty'] * $item['harga_jual'],
                'subtotal_cost' => $item['qty'] * $item['harga_beli'],
            ]);

            $productTable
                ->set('stok', 'stok - ' . (int) $item['qty'], false)
                ->where('id', $item['product_id'])
                ->update();
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan transaksi.')
                ->withInput();
        }

        session()->remove('cart');

        return redirect()
            ->to(site_url('kasir/pos/struk/' . $saleId));
    }

    // ================== STRUK BIASA ==================
    public function struk($saleId = null)
    {
        if (! $saleId) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $sale = $this->saleModel->find($saleId);

        if (! $sale) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $kasir = $this->userModel->find($sale['user_id']);

        $items = $this->saleItemModel
            ->select('sale_items.*, products.nama, products.barcode, products.satuan')
            ->join('products', 'products.id = sale_items.product_id')
            ->where('sale_id', $saleId)
            ->findAll();

        $printedAt = Time::now('Asia/Jakarta');

        $data = [
            'sale'          => $sale,
            'kasir'         => $kasir,
            'items'         => $items,
            'printed_at'    => $printedAt,
            'payment_label' => $sale['payment_method'] === 'qris' ? 'QRIS' : 'Tunai',
            'title'         => 'Struk Transaksi',
        ];

        return view('kasir/pos/struk', $data);
    }

    // ================== STRUK THERMAL ==================
    public function strukThermal($saleId = null)
    {
        if (! $saleId) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $sale = $this->saleModel->find($saleId);

        if (! $sale) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $kasir = $this->userModel->find($sale['user_id']);

        $items = $this->saleItemModel
            ->select('sale_items.*, products.nama, products.barcode, products.satuan')
            ->join('products', 'products.id = sale_items.product_id')
            ->where('sale_id', $saleId)
            ->findAll();

        $printedAt = Time::now('Asia/Jakarta');

        $data = [
            'sale'          => $sale,
            'kasir'         => $kasir,
            'items'         => $items,
            'printed_at'    => $printedAt,
            'payment_label' => $sale['payment_method'] === 'qris' ? 'QRIS' : 'Tunai',
        ];

        return view('kasir/pos/struk_thermal', $data);
    }

    // ================== STRUK PDF ==================
    public function strukPdf($saleId = null)
    {
        if (! $saleId) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $sale = $this->saleModel->find($saleId);

        if (! $sale) {
            return redirect()
                ->to(site_url('kasir/pos'))
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $kasir = $this->userModel->find($sale['user_id']);

        $items = $this->saleItemModel
            ->select('sale_items.*, products.nama, products.barcode, products.satuan')
            ->join('products', 'products.id = sale_items.product_id')
            ->where('sale_id', $saleId)
            ->findAll();

        $printedAt = Time::now('Asia/Jakarta');

        $data = [
            'sale'          => $sale,
            'kasir'         => $kasir,
            'items'         => $items,
            'printed_at'    => $printedAt,
            'payment_label' => $sale['payment_method'] === 'qris' ? 'QRIS' : 'Tunai',
        ];

        $html = view('kasir/pos/struk_pdf', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fileName = 'struk_' . $sale['invoice_no'] . '.pdf';

        $this->response->setHeader('Content-Type', 'application/pdf');
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . $fileName . '"');

        return $this->response->setBody($dompdf->output());
    }
}
