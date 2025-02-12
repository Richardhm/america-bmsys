<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comissoes;

class ComissoesController extends Controller
{
    public function index()
    {
        // dd(Comissoes::with(['user','contrato','contrato.clientes'])->get());
        return view('admin.pages.comissao.index');
    }


    public function listarComissoes()
    {
        return Comissoes::with(['user','contrato','contrato.clientes'])->get();
    }



}
