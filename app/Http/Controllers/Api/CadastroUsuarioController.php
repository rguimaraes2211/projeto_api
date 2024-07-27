<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;

class CadastroUsuarioController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $dados = $request->all();
            $usuario = Usuario::create([
                "nome"=> $dados["nome"],
                "email"=> $dados["email"],
                "senha"=> Hash::make($dados["senha"])
            ]);

            DB::commit();
            return response()->json([
                "status" => true,
                "user" => $usuario,
                "msg" => "Usuario cadastrado com sucesso!"
            ], 200);

        } catch(Exception $e){
            DB::rollBack();

            return response()->json([
                "status" => false,
                "msg" => "Usuario não cadastrado!"
            ], 200);
        }
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'senha');

        $user = Usuario::where('email', $credentials['email'])->first();

        
        if ($user && Hash::check($credentials['senha'], $user->senha)) {
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'status' => true,
                'msg' => 'Login realizado com sucesso', 
                'user' => $user,
                'token'=> $token
            ], 200);
        } else {
            return response()->json([
                'status'=> false,
                'msg' => 'E-mail ou senha incorretos'
            ], 200);
        }
    }
}
