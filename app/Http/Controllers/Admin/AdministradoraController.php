<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Administradoras;



class AdministradoraController extends Controller
{
    private $repository;

    public function __construct(Administradoras $administradoras) 
    {
        $this->repository = $administradoras;
    }


    public function index()
    {
        

        return view('admin.pages.administradoras.index');
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
            $administradoras = $this->repository->all();
            return $administradoras;
        }
         
    }

    public function cadastrarAjax(Request $request)
    {
        if($request->ajax()) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            //Nome da Pasta de Destino
            $location = 'administradoras';
            // Realizar Upoload da Imagem
             $file->move($location,$filename);
            
             // $filepath = url($location.'/'.$filename);
             $logo = $location.'/'.$filename;
             $nome = $request->nome;

             $admin = new administradoras();
             $admin->nome = $nome;
             $admin->logo = $logo;
             $admin->cor = "rgb(0,0,0)";
             if($admin->save()) {
                return "sucesso";
             } else {
                return "error";
             }

            
        }
    }





}
