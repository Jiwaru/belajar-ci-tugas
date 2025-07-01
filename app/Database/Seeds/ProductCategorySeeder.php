<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Elektronik',
                'deskripsi'  => 'Produk-produk elektronik seperti laptop dan handphone',
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Pakaian',
                'deskripsi'  => 'Baju, celana, jaket, dan aksesoris fashion lainnya',
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama'       => 'Makanan & Minuman',
                'deskripsi'  => 'Makanan ringan, minuman, dan kebutuhan pokok lainnya',
                'created_at' => date("Y-m-d H:i:s"),
            ]
        ];

        foreach ($data as $item) {
            $this->db->table('product_category')->insert($item);
        }
    }
}
