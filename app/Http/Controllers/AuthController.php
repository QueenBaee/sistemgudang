<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Raw;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);
        if ($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);
        return response()->json(['user'=>$user],201);
    }

    public function login(Request $request){
        $credentials = $request ->only('email', 'password');
        if(auth()->attempt($credentials)){
            $user = auth()->user();
            $token = $user->createToken('MyApp')->plainTextToken;
            return response()->json(['token'=>$token], 200);
        }
        return response()->json(['error'=>'Unauthorized'], 401);
    }
    public function logout(Request $request)
    {
    try {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Silahkan login dahulu'
            ], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout sukses'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Terjadi kesalahan saat logout: ' . $e->getMessage()
        ], 500);
    }
}
}
