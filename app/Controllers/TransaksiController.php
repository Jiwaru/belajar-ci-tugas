<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
protected $cart;
protected $client;
protected $apiKey;
protected $transaction;
protected $transaction_detail;

function __construct()
{
helper('number');
helper('form');
$this->cart = \Config\Services::cart();
$this->client = new \GuzzleHttp\Client();
$this->apiKey = env('COST_KEY');
$this->transaction = new TransactionModel();
$this->transaction_detail = new TransactionDetailModel();

}

public function index()
{
// Ambil item dari cart
$data['items'] = $this->cart->contents();

// Cek apakah ada diskon yang disimpan di session
$diskon = session()->get('diskon');
$totalDiskon = 0;
$totalHarga = 0;

// Loop untuk menghitung harga total setelah diskon
foreach ($data['items'] as $item) {
// Jika ada diskon, hitung harga setelah diskon
$price = $item['price']; // Default price
if ($diskon) {
$price -= $diskon['nominal']; // Kurangi dengan diskon
}
$subtotal = $item['qty'] * $price;
$totalHarga += $subtotal;  // Tambahkan subtotal ke total harga
}

// Menyimpan total harga yang sudah didiskon
$data['total'] = $totalHarga;

return view('v_keranjang', $data);
}

public function cart_add()
{
$productId = $this->request->getPost('id');
$quantity = 1;
$price = $this->request->getPost('harga');
$name = $this->request->getPost('nama');
$foto = $this->request->getPost('foto');

// Get diskon data from session
$diskon = session()->get('diskon');  // Retrieve diskon from session

// If there's a valid diskon, apply it to the price
if ($diskon) {
$price -= $diskon['nominal'];  // Subtract the discount amount from the price
}

// Insert the product into the cart with the discounted price
$this->cart->insert([
'id'        => $productId,
'qty'       => $quantity,
'price'     => $price,  // Discounted price
'name'      => $name,
'options'   => ['foto' => $foto]
]);


session()->setflashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
return redirect()->to(base_url('/'));
}

public function cart_clear()
{
$this->cart->destroy();
session()->setflashdata('success', 'Keranjang Berhasil Dikosongkan');
return redirect()->to(base_url('keranjang'));
}

public function cart_edit()
{
$i = 1;
foreach ($this->cart->contents() as $value) {
$this->cart->update(array(
'rowid' => $value['rowid'],
'qty'   => $this->request->getPost('qty' . $i++)
));
}

session()->setflashdata('success', 'Keranjang Berhasil Diedit');
return redirect()->to(base_url('keranjang'));
}

public function cart_delete($rowid)
{
$this->cart->remove($rowid);
session()->setflashdata('success', 'Keranjang Berhasil Dihapus');
return redirect()->to(base_url('keranjang'));
}

public function checkout()
{
$data['items'] = $this->cart->contents();
$data['total'] = $this->cart->total();

return view('v_checkout', $data);
}

public function getLocation()
{
//keyword pencarian yang dikirimkan dari halaman checkout
$search = $this->request->getGet('search');

$response = $this->client->request(
'GET', 
'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
'headers' => [
'accept' => 'application/json',
'key' => $this->apiKey,
],
]
);

$body = json_decode($response->getBody(), true); 
return $this->response->setJSON($body['data']);
}

public function getCost()
{ 
//ID lokasi yang dikirimkan dari halaman checkout
$destination = $this->request->getGet('destination');

//parameter daerah asal pengiriman, berat produk, dan kurir dibuat statis
//valuenya => 64999 : PEDURUNGAN TENGAH , 1000 gram, dan JNE
$response = $this->client->request(
'POST', 
'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
'multipart' => [
[
'name' => 'origin',
'contents' => '64999'
],
[
'name' => 'destination',
'contents' => $destination
],
[
'name' => 'weight',
'contents' => '1000'
],
[
'name' => 'courier',
'contents' => 'jne'
]
],
'headers' => [
'accept' => 'application/json',
'key' => $this->apiKey,
],
]
);

$body = json_decode($response->getBody(), true); 
return $this->response->setJSON($body['data']);
}

public function buy()
{
        if ($this->request->getPost()) { 
        if ($this->request->getPost()) {  
            $diskon = session()->get('diskon');
            $totalDiskon = $diskon ? $diskon['nominal'] : 0; 

$dataForm = [
'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            'total_harga' => $this->request->getPost('total_harga'),
            'alamat' => $this->request->getPost('alamat'),
            'ongkir' => $this->request->getPost('ongkir'),
            'status' => 0,  // Status set to 0 (not completed yet)
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
];

$this->transaction->insert($dataForm);

$last_insert_id = $this->transaction->getInsertID();

foreach ($this->cart->contents() as $value) {
$dataFormDetail = [
'transaction_id' => $last_insert_id,
'product_id' => $value['id'],
'jumlah' => $value['qty'],
                    'diskon' => 0,
                    'diskon' => $value['price'],
'subtotal_harga' => $value['qty'] * $value['price'],
'created_at' => date("Y-m-d H:i:s"),
'updated_at' => date("Y-m-d H:i:s")
];

$this->transaction_detail->insert($dataFormDetail);
}

$this->cart->destroy();

return redirect()->to(base_url());
}
}
}}  