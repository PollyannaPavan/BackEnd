<?php
namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::all();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required',
            'fabricante' => 'required',
            'quantidade' => 'required|integer',
            'preco' => 'required|numeric'
        ]);

        Produto::create($dados);

        return redirect()->route('produtos.index');
    }

    public function show(string $id)
    {
        $produto = Produto::findOrFail($id);

        return view('produtos.show', compact('produto'));
    }

    public function edit(string $id)
    {
        $produto = Produto::findOrFail($id);

        return view('produtos.edit', compact('produto'));
    }

    public function update(Request $request, string $id)
    {
        $produto = Produto::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'required',
            'fabricante' => 'required',
            'quantidade' => 'required|integer',
            'preco' => 'required|numeric'
        ]);

        $produto->update($dados);

        return redirect()->route('produtos.index');
    }

    public function destroy(string $id)
    {
        $produto = Produto::findOrFail($id);

        $produto->delete();

        return redirect()->route('produtos.index');
    }
}
