<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class BarangController extends Controller
{
    public function index(){
        try{
            $barang = Barang::all();
            if ($barang->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Data Operator tidak ditemukan'], 404);
            }
            return response()->json(['status'=>true, 'message'=>'Data Barang ditemukan','data'=>$barang],200);
        }catch(QueryException $e){
            $error=[
                'error'=>$e->getMessage()
            ];
            return response()->json(['status'=>false, 'message'=>$error],500);
        }
    }
    public function findById($kode_barang){
        try{
            $barang= Barang::findOrFail($kode_barang);
            $response=['status'=>true, 'message'=>'Data Barang ditemukan', 'data'=>$barang];
            return response()->json($response, 200);
        }catch(\Exception $e){
            return response ()->json(['status'=>false,'message'=>'Data Kosong'],200);  
        }
    }
    public function create(Request $request){
        if (!auth()->check()) {
            return response()->json(['message' => 'Silahkan login terlebih dahulu'], 401);
        }
        try{
            $validator = Validator::make($request->all(),[
                'kode_barang' =>'required|unique:barang',
                'nama_barang' =>'required|string',
                'kategori'=>'required|string',
                'satuan'=>'required|string',
                'stok'=>'required|numeric',
                'supplier_id'=>'required|string'
            ]);
            if($validator->fails()){
                return response()->json(['Status'=>false,'Message'=>$validator->errors()],422);
            }
            $barang =Barang::create($request->all());
            $response =['status' => true,'message'=>'Data Barang Berhasil Ditambahkan','data'=> $barang];
            return response()->json($response, 201);
        }catch(\Exception $e){
            $error=[
                'error' =>$e->getMessage()
            ];
            return response()->json($error, 422);
        }
    }
    public function update(Request $request, $kode_barang)
{
    try {

        $barang = Barang::where('kode_barang', $kode_barang)->firstOrFail();


        $validator = Validator::make($request->all(), [
            'kode_barang' => 'sometimes|required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'sometimes|required',
            'kategori' => 'sometimes|required',
            'satuan' => 'sometimes|required',
            'stok' => 'sometimes|required|numeric',
            'supplier_id' => 'sometimes|required|exists:supplier,id',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }


        $barang->update($request->only(['kode_barang', 'nama_barang', 'kategori', 'satuan', 'stok', 'supplier_id']));


        return response()->json([
            'status' => true, 
            'message' => 'Data barang Berhasil di Update', 
            'data' => $barang,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Data Kosong atau Error: ' . $e->getMessage()], 404);
    }
}
    public function delete($kode_barang){
        if (!auth()->check()) {
            return response()->json(['message' => 'Silahkan login terlebih dahulu'], 401);
        }
        try {
            if (!$kode_barang) {
                return response()->json(['status' => false, 'message' => 'Kode Barang tidak ditemukan'], 400);
            }
            
            Barang::findOrFail($kode_barang)->delete();
            return response()->json(['status'=>true, 'message' => 'Data Barang Berhasil Dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data Barang Tidak Ditemukan'], 404);
        }
    }
}
