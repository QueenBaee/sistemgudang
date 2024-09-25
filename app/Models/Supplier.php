<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'kode_supplier','nama_supplier', 'alamat', 'kontak'
    ];
    public $timestamps = false;
    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
