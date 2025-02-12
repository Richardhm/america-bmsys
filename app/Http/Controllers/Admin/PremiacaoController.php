<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Premiacoes;

class PremiacaoController extends Controller
{
    public function index()
    {
        return view('admin.pages.premiacoes.index');
    }

    public function listarPremiacao()
    {
        return Premiacoes::with(['user','contrato','contrato.clientes'])->get();
    }

}
