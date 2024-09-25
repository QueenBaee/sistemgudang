<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mutasi;
use App\Models\Barang;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class MutasiController extends Controller
{
    public function index(){
        try{
            $Mutasi = Mutasi::all();
            if ($Mutasi->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Data Mutasi tidak ditemukan'], 404);
            }
            return response()->json(['status'=>true, 'message'=>'Data Mutasi ditemukan','data'=>$Mutasi],200);
        }catch(QueryException $e){
            $error=[
                'error'=>$e->getMessage()
            ];
            return response()->json(['status'=>false, 'message'=>$error],500);
        }
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id',
            'jenis_mutasi' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric',
            'keterangan' => 'nullable|string',
            'tanggal_mutasi' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }

        $mutasi = Mutasi::create($request->all());
        return response()->json(['status' => true, 'message' => 'Mutasi berhasil dibuat', 'data' => $mutasi], 201);
    }
    public function historyByBarang($barang_id)
    {
        $barang = Barang::findOrFail($barang_id);
        $history = Mutasi::where('barang_id', $barang_id)->get();

        if ($history->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Tidak ada mutasi untuk barang ini'], 404);
        }

        return response()->json(['status' => true, 'message' => 'History mutasi untuk barang ditemukan', 'data' => $history], 200);
    }
    public function historyByUser($user_id)
    {
        $user = User::findOrFail($user_id);
        $history = Mutasi::where('user_id', $user_id)->get();

        if ($history->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'Tidak ada mutasi untuk pengguna ini'], 404);
        }

        return response()->json(['status' => true, 'message' => 'History mutasi untuk pengguna ditemukan', 'data' => $history], 200);
    } 
    public function findById($id){
        $mutasi= Mutasi::findOrFail($id);
        try{
        $response=['status'=>true, 'message'=>'Data mutasi ditemukan', 'data'=>$mutasi];
        return response()->json($response, 200);
    }
    catch(\Exception $e){
        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 404); 
    }
    
    }
    public function delete($id){
        if (!auth()->check()) {
            return response()->json(['message' => 'Silahkan login terlebih dahulu'], 401);
        }
        try {
            if (!$id) {
                return response()->json(['status' => false, 'message' => 'Kode Mutasi tidak ditemukan'], 400);
            }
            
            Mutasi::findOrFail($id)->delete();
            return response()->json(['status'=>true, 'message' => 'Data Mutasi Berhasil Dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data Mutasi Tidak Ditemukan'], 404);
        }
    }
}
