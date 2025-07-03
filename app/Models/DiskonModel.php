<?php

namespace App\Models;

use CodeIgniter\Model;

class DiskonModel extends Model
{
    protected $table = 'diskon';
    protected $primaryKey = 'id';
    protected $allowedFields = ['tanggal', 'nominal'];
    protected $useTimestamps = true;

    // Fungsi untuk mendapatkan diskon berdasarkan tanggal
    public function getDiskonByDate($date)
    {
        return $this->where('tanggal', $date)
                    ->first(); // Mengambil diskon yang berlaku pada tanggal tersebut
    }
}