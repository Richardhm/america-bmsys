<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TabelaOrigens;


class TabelaOrigemControlller extends Controller
{
    private $repository;

    public function __construct(TabelaOrigens $tabelaOrigens)
    {
        $this->repository = $tabelaOrigens;
    }

    public function index()
    {
        return view('admin.pages.tabela_origem.index');
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
            return $this->repository->all();
        }
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
            $dados['nome'] = $request->nome;
            $dados['uf'] = $request->uf;
            $this->repository->create($request->all());
            
        }
    }


}
