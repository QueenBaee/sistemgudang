<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{   
    protected $table = 'mutasi';
    protected $fillable = ['barang_id', 'jenis_mutasi', 'jumlah','keterangan', 'tanggal_mutasi', 'user_id'];
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
