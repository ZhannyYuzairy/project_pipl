<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\ProductModel;

class Products extends BaseController
{
    protected $productModel;

    // daftar satuan yang diijinkan (dipakai di validasi dan view)
    protected array $allowedUnits = [
        'pcs',
        'pak',
        'dus',
        'box',
        'kg',
        'gram',
        'ml',
        'liter',
    ];

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $products = $this->productModel
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title'    => 'Master Produk',
            'products' => $products,
        ];

        return view('owner/products/index', $data);
    }

    public function create()
    {
        $data = [
            'title'        => 'Tambah Produk',
            'allowedUnits' => $this->allowedUnits,
        ];

        return view('owner/products/create', $data);
    }

public function store()
{
    $rules = [
        'barcode'    => 'required|is_unique[products.barcode]',
        'nama'       => 'required',
        'stok'       => 'required|integer|greater_than_equal_to[0]',
        'harga_beli' => 'required|numeric|greater_than_equal_to[0]',
        'harga_jual' => 'required|numeric|greater_than_equal_to[0]',
        'gambar'     => 'permit_empty|is_image[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
    ];

    // Validasi tambahan: harga jual harus >= harga beli
    if ((float) $this->request->getPost('harga_jual') < (float) $this->request->getPost('harga_beli')) {
        return redirect()->back()->withInput()->with('error', 'Harga jual tidak boleh lebih rendah dari harga beli.');
    }

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', implode('<br>', $this->validator->getErrors()));
    }

        $gambarName = null;
        $gambar     = $this->request->getFile('gambar');

        if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
            $gambarName = $gambar->getRandomName();
            $gambar->move(ROOTPATH . 'public/uploads/products', $gambarName);
        }

        $satuan = $this->request->getPost('satuan');
        if (! in_array($satuan, $this->allowedUnits, true)) {
            $satuan = 'pcs';
        }

        $this->productModel->insert([
            'barcode'    => $this->request->getPost('barcode'),
            'nama'       => $this->request->getPost('nama'),
            'stok'       => (int) $this->request->getPost('stok'),
            'harga_beli' => (float) $this->request->getPost('harga_beli'),
            'harga_jual' => (float) $this->request->getPost('harga_jual'),
            'satuan'     => $satuan,
            'gambar'     => $gambarName,
        ]);

        return redirect()
            ->to(site_url('owner/products'))
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id = null)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to(site_url('owner/products'))
                ->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'        => 'Edit Produk',
            'product'      => $product,
            'allowedUnits' => $this->allowedUnits,
        ];

        return view('owner/products/edit', $data);
    }

public function update($id = null)
{
    $product = $this->productModel->find($id);

    if (! $product) {
        return redirect()->to(site_url('owner/products'))->with('error', 'Produk tidak ditemukan.');
    }

    $rules = [
        'nama'       => 'required',
        'stok'       => 'required|integer|greater_than_equal_to[0]',
        'harga_beli' => 'required|numeric|greater_than_equal_to[0]',
        'harga_jual' => 'required|numeric|greater_than_equal_to[0]',
        'gambar'     => 'permit_empty|is_image[gambar]|max_size[gambar,2048]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
    ];

    // Barcode unik jika diubah
    $barcodeBaru = $this->request->getPost('barcode');
    if ($barcodeBaru !== $product['barcode']) {
        $rules['barcode'] = 'required|is_unique[products.barcode]';
    } else {
        $rules['barcode'] = 'required';
    }

    // Validasi harga jual >= harga beli
    if ((float) $this->request->getPost('harga_jual') < (float) $this->request->getPost('harga_beli')) {
        return redirect()->back()->withInput()->with('error', 'Harga jual tidak boleh lebih rendah dari harga beli.');
    }

    if (! $this->validate($rules)) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', implode('<br>', $this->validator->getErrors()));
    }

        $gambar     = $this->request->getFile('gambar');
        $gambarName = $product['gambar'];

        if ($gambar && $gambar->isValid() && ! $gambar->hasMoved()) {
            // hapus gambar lama jika ada
            if (! empty($product['gambar'])) {
                $oldPath = ROOTPATH . 'public/uploads/products/' . $product['gambar'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $gambarName = $gambar->getRandomName();
            $gambar->move(ROOTPATH . 'public/uploads/products', $gambarName);
        }

        $satuan = $this->request->getPost('satuan');
        if (! in_array($satuan, $this->allowedUnits, true)) {
            $satuan = 'pcs';
        }

        $this->productModel->update($id, [
            'barcode'    => $barcodeBaru,
            'nama'       => $this->request->getPost('nama'),
            'stok'       => (int) $this->request->getPost('stok'),
            'harga_beli' => (float) $this->request->getPost('harga_beli'),
            'harga_jual' => (float) $this->request->getPost('harga_jual'),
            'satuan'     => $satuan,
            'gambar'     => $gambarName,
        ]);

        return redirect()
            ->to(site_url('owner/products'))
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Tampilkan halaman konfirmasi hapus
     */
    public function delete($id = null)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to(site_url('owner/products'))
                ->with('error', 'Produk tidak ditemukan.');
        }

        $data = [
            'title'   => 'Hapus Produk',
            'product' => $product,
        ];

        return view('owner/products/delete', $data);
    }

    /**
     * Eksekusi hapus (dipanggil via POST /owner/products/destroy/:id)
     */
    public function destroy($id = null)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            return redirect()
                ->to(site_url('owner/products'))
                ->with('error', 'Produk tidak ditemukan.');
        }

        if (! empty($product['gambar'])) {
            $path = ROOTPATH . 'public/uploads/products/' . $product['gambar'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $this->productModel->delete($id);

        return redirect()
            ->to(site_url('owner/products'))
            ->with('success', 'Produk berhasil dihapus.');
    }
}
