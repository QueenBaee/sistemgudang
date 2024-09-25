<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori','satuan','stok', 'supplier_id'];
    public $timestamps = false;
    public function mutasi()
    {
        return $this->hasMany(Mutasi::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
