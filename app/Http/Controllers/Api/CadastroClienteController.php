<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\DB;

class CadastroClienteController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $dados = $request->all();

            if ($dados['nome'] == "") {
                return response()->json([
                    "status" => false,
                    "msg" => "Insira um nome!"
                ], 200);
            }
            if ($dados['cpf'] == "") {
                return response()->json([
                    "status" => false,
                    "msg" => "Insira um CPF!"
                ], 200);
            }
            if ($this->validarCPF($dados['cpf']) == false) {
                return response()->json([
                    "status" => false,
                    "msg" => "CPF Inválido!"
                ], 200);
            }
            if ($dados['nascimento'] == "") {
                return response()->json([
                    "status" => false,
                    "msg" => "Insira uma data de nascimento!"
                ], 200);
            }

            $caminhoFoto = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $caminhoFoto = $foto->store('fotos', 'public');
            } else {
                return response()->json([
                    "status" => false,
                    "msg" => "Insira uma foto!"
                ], 200);
            }


            $cliente = Cliente::create([
                'nome' => $dados['nome'],
                'cpf' => $dados['cpf'],
                'nascimento' => $dados['nascimento'],
                'telefone' => $dados['telefone'],
                'estado' => $dados['estado'],
                'cidade' => $dados['cidade'],
                'foto' => $caminhoFoto
            ]);

            DB::commit();
            return response()->json([
                "status" => true,
                "user" => $cliente,
                "msg" => "Cliente cadastrado com sucesso!"
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                "status" => false,
                "msg" => "Cliente não cadastrado!"
            ], 200);
        }
    }

    public function getClientes(Request $request)
    {
        DB::beginTransaction();

        try {
            $query = Cliente::query();

            $dados = ($request->all()['params']);

            $query->select('*', DB::raw('TIMESTAMPDIFF(YEAR, nascimento, CURDATE()) AS idade'));

            if (isset($dados['nome']) && $dados['nome'] !== '') {
                $query->where('nome', 'like', '%' . $dados['nome'] . '%');
            }

            if (isset($dados['cpf']) && $dados['cpf'] !== '') {
                $query->where('cpf', $dados['cpf']);
            }
            if (isset($dados['idade']) && $dados['idade'] !== '') {
                $query->where(DB::raw('TIMESTAMPDIFF(YEAR, nascimento, CURDATE())'), '=', $dados['idade']);
            }

            if (isset($dados['cidade']) && $dados['cidade'] !== '') {
                $query->where('cidade', $dados['cidade']);
            }

            if (isset($dados['estado']) && $dados['estado'] !== '') {
                $query->where('estado', $dados['estado']);
            }

            // Obter os resultados com base nos filtros aplicados
            $clientes = $query->get();

            return response()->json([
                "status" => true,
                "users" => $clientes,
                "msg" => "Cliente cadastrado com sucesso!"
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                "status" => false,
                "msg" => "Cliente não cadastrado!"
            ], 200);
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        $cliente->delete();
        return response()->json([
            "status" => true,
            'msg' => 'Cliente excluído com sucesso.'
        ], 200);
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    public function update(Request $request, $id)
    {
        var_dump($request->all());   
        $cliente = Cliente::findOrFail($id);
        $cliente->nome = $request->input('nome');
        $cliente->cpf = $request->input('cpf');
        $cliente->nascimento = $request->input('nascimento');
        $cliente->telefone = $request->input('telefone');
        $cliente->estado = $request->input('estado');
        $cliente->cidade = $request->input('cidade');

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public');
            $cliente->foto = basename($path);
        }

        $cliente->save();
        return response()->json([
            'status'=> true,
            'message' => 'Cliente atualizado com sucesso.'
        ]);
    }

    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/^(\d)\1+$/', $cpf)) {
            return false;
        }

        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        return ($cpf[9] == $digito1 && $cpf[10] == $digito2);
    }
}
