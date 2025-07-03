<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - TOKO</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php 
function curl(){ 
    $curl = curl_init(); 
    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://localhost:8080/api",
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_CUSTOMREQUEST => "GET", 
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: random123678abcghi",
        ),
    ));
    $output = curl_exec($curl); 	
    curl_close($curl);      
    $data = json_decode($output);   
    return $data;
} 
?>

<div class="p-3 pb-md-4 mx-auto text-center">
    <h1 class="display-4 fw-normal text-body-emphasis">Dashboard - TOKO</h1>
    <p class="fs-5 text-body-secondary"><?= date("l, d-m-Y") ?> <span id="jam"></span>:<span id="menit"></span>:<span id="detik"></span></p>
</div> 
<hr>

<div class="table-responsive card m-5 p-5">
    <table class="table text-center">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Username</th>
                <th style="width: 30%;">Alamat</th>
                <th style="width: 10%;">Total Harga</th>
                <th style="width: 10%;">Ongkir</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 25%;">Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $send1 = curl();
            if (!empty($send1)) {
                $hasil1 = $send1->results;
                $i = 1; 
                foreach ($hasil1 as $item1) { 
                    $jumlah_item = 0;
                    $subtotal = 0;

                    if (!empty($item1->details)) {
                        foreach ($item1->details as $detail) {
                            $jumlah_item += $detail->jumlah;
                            $subtotal += $detail->subtotal_harga;
                        }
                    }

                    $ongkir = $item1->ongkir ?? 0;
                    $total_harga = $subtotal + $ongkir;
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($item1->username) ?></td>
                <td><?= htmlspecialchars($item1->alamat) ?></td>
                <td>
                    <?= 'Rp ' . number_format($total_harga, 0, ',', '.') ?><br>
                    <small>(<?= $jumlah_item ?> item)</small>
                </td>
                <td><?= 'Rp ' . number_format($ongkir, 0, ',', '.') ?></td>
                <td><?= ($item1->status == 1) ? 'Selesai' : 'Belum Selesai'; ?></td>
                <td><?= htmlspecialchars($item1->created_at) ?></td>
            </tr>
            <?php 
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function waktu() {
    const waktu = new Date();
    document.getElementById("jam").innerHTML = waktu.getHours().toString().padStart(2, '0');
    document.getElementById("menit").innerHTML = waktu.getMinutes().toString().padStart(2, '0');
    document.getElementById("detik").innerHTML = waktu.getSeconds().toString().padStart(2, '0');
    setTimeout(waktu, 1000);
}
waktu();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>