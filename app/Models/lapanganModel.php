<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lapanganModel extends Model
{
    protected $table = 'lapangan';

    protected $fillable = [
        'rumah',
        'deskripsi',
        'ukuran',
        'type',
        'harga',
        'status',
        'gambar',
    ];
}
