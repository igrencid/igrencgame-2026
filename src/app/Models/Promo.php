<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar',
        'kode_promo',
        'diskon',
        'tanggal_mulai',
        'tanggal_akhir',
        'status',
    ];
}