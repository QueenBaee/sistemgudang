<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(){
        try{
            $supplier = Supplier::all();
            if ($supplier->isEmpty()) {
                return response()->json(['status' => false, 'message' => 'Data Operator tidak ditemukan'], 404);
            }
            return response()->json(['status'=>true, 'message'=>'Data Barang ditemukan','data'=>$supplier],200);
        }catch(QueryException $e){
            $error=[
                'error'=>$e->getMessage()
            ];
            return response()->json(['status'=>false, 'message'=>$error],500);
        }
    }
    public function findById($kode_suppplier){
            $supplier= Supplier::findOrFail($kode_suppplier);
            try{
            $response=['status'=>true, 'message'=>'Data Barang ditemukan', 'data'=>$supplier];
            return response()->json($response, 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 404); 
        }
    }
    public function create(Request $request){
        try{
            $validator = Validator::make($request->all(),[
            'kode_supplier' => 'sometimes|required|unique:supplier,kode_supplier,',
            'nama_supplier' => 'sometimes|required',
            'alamat' => 'sometimes|required',
            'kontak' => 'sometimes|required',
            ]);
            if($validator->fails()){
                return response()->json(['Status'=>false,'Message'=>$validator->errors()],422);
            }
            $supplier =Supplier::create($request->all());
            $response =['status' => true,'message'=>'Data Barang Berhasil Ditambahkan','data'=> $supplier];
            return response()->json($response, 201);
        }catch(\Exception $e){
            $error=[
                'error' =>$e->getMessage()
            ];
            return response()->json($error, 422);
        }
    }
    public function update(Request $request, $kode_suppplier)
{
    try {

        $supplier = Supplier::where('kode_barang', $kode_suppplier)->firstOrFail();


        $validator = Validator::make($request->all(), [
            'kode_supplier' => 'sometimes|required|unique:supplier,kode_supplier,' . $supplier->id,
            'nama_supplier' => 'sometimes|required',
            'alamat' => 'sometimes|required',
            'kontak' => 'sometimes|required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()], 422);
        }


        $supplier->update($request->only(['kode_supplier', 'nama_supplier', 'alamat', 'kontak']));


        return response()->json([
            'status' => true, 
            'message' => 'Data supplier Berhasil di Update', 
            'data' => $supplier,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Data Kosong atau Error: ' . $e->getMessage()], 404);
    }
}
    public function delete($kode_suppplier){
        if (!auth()->check()) {
            return response()->json(['message' => 'Silahkan login terlebih dahulu'], 401);
        }
        try {
            if (!$kode_suppplier) {
                return response()->json(['status' => false, 'message' => 'Kode Barang tidak ditemukan'], 400);
            }
            
            Supplier::findOrFail($kode_suppplier)->delete();
            return response()->json(['status'=>true, 'message' => 'Data Supplier Berhasil Dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Data Supplier Tidak Ditemukan'], 404);
        }
    }
}
