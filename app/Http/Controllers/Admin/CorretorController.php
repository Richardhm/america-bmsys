<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Models\User;


class CorretorController extends Controller
{
    public function index()
    {
        $cargos = Cargo::all();

        return view('admin.pages.corretores.index',[
            "cargos" => $cargos
        ]);
    }

    public function store(Request $request)
    {
        if($request->ajax()) {
             $file = $request->file('file');
             $filename = time().'_'.$file->getClientOriginalName();
             //Nome da Pasta de Destino
             $location = 'colaboradores';
             // Realizar Upoload da Imagem
             $file->move($location,$filename);
             // $filepath = url($location.'/'.$filename);
             $logo = $location.'/'.$filename;
             $nome = $request->nome;
             $celular = $request->celular;
             $cidade = $request->cidade;
             $email = $request->email;
             $endereco = $request->endereco;
             $estado = $request->estado;
             $numero = $request->numero;
             $password = bcrypt($request->password);
             $cargo = $request->cargo;
             $cpf = $request->cpf;


             $user = new User();
             $user->name = $nome;
             $user->email = $email;
             $user->cidade = $cidade;
             $user->estado = $estado;
             $user->celular = $celular;
             $user->password = $password;
             $user->email = $email;
             $user->cargo_id = $cargo;
             $user->cpf = $cpf;
             $user->image = $logo;
             $user->numero = $numero;
             $user->save();





             //$user->cpf = $





        }
    }

}    
