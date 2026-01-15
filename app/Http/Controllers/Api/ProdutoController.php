<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        return Produto::where('user_id', $request->user()->id)->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_barras' => 'required|unique:produtos',
            'nome' => 'required',
            'preco_venda' => 'required|numeric',
            'preco_custo' => 'required|numeric',
            'estoque' => 'required|integer',
            'estoque_minimo' => 'nullable|integer',
            'descricao' => 'nullable',
            'foto' => 'nullable|string'
        ]);

        $produto = Produto::create([
            'user_id' => $request->user()->id,
            'codigo_barras' => $request->codigo_barras,
            'nome' => $request->nome,
            'preco_venda' => $request->preco_venda,
            'preco_custo' => $request->preco_custo,
            'estoque' => $request->estoque,
            'estoque_minimo' => $request->estoque_minimo ?? 0,
            'descricao' => $request->descricao,
            'foto' => $request->foto
        ]);

        return response()->json($produto, 201);
    }

    public function buscarPorCodigo(Request $request, $codigo)
    {
        $produto = Produto::where('user_id', $request->user()->id)
            ->where('codigo_barras', $codigo)
            ->first();

        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado'], 404);
        }

        return $produto;
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->firstOrFail();

        $produto->update($request->all());

        return $produto;
    }
}
