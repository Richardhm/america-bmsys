<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Corretora;
use App\Http\Requests\StoreUpdateCorratora;


class CorretoraController extends Controller
{
     private $repository;

     public function __construct(Corretora $corretora)
    {
        $this->repository = $corretora;   
        //$this->middleware(['can:configuracoes']);
    }



    public function index()
    {
        $corretora = $this->repository->first();
        if(!$corretora) {
            $corretora = new \stdClass();
            $corretora->nome = "";
            $corretora->logo = "";
            $corretora->endereco = "";
            $corretora->telefone = "";
            $corretora->site = "";
            $corretora->email = "";
            $corretora->instagram = "";
            $corretora->consultas_eletivas = "";
            $corretora->consultas_urgencia = "";
            $corretora->exames_simples = "";
            $corretora->exames_complexos = "";

            $corretora->linha_01_coletivo = "";
            $corretora->linha_02_coletivo = "";
            $corretora->linha_03_coletivo = "";

            $corretora->linha_01_individual = "";
            $corretora->linha_02_individual = "";
            $corretora->linha_03_individual = "";

            $corretora->cor = "";
        }    


        return view('admin.pages.corretora.index',[
            "corretora" => $corretora
        ]);
    }

    public function store(StoreUpdateCorratora $request)
    {
        $corretora = $this->repository->first();
        if($corretora) {
            echo "Ja Existe";
            // if(isset($request->logo) && !empty($request->logo)) {
            //     if($corretora->logo != '') {
            //         unlink("storage/".$corretora->logo);
            //     }
            //     $corretora->logo = $request->file('logo')->store('corretora','public');
            // }
            // $corretora->endereco = $request->endereco;   
            // $corretora->telefone = $request->telefone;
            // $corretora->site = $request->site;
            // $corretora->email = $request->email;
            // $corretora->instagram = $request->instagram;
            // $corretora->consultas_eletivas = str_replace([".",","],["","."],$request->consultas_eletivas);
            // $corretora->consultas_urgencia = str_replace([".",","],["","."],$request->consultas_urgencia);
            // $corretora->exames_simples = str_replace([".",","],["","."],$request->exames_simples);
            // $corretora->exames_complexos = str_replace([".",","],["","."],$request->exames_complexos);
            // $corretora->linha_01_coletivo = $request->linha_01_coletivo;
            // $corretora->linha_02_coletivo = $request->linha_02_coletivo;
            // $corretora->linha_03_coletivo = $request->linha_03_coletivo;
            // $corretora->linha_01_individual = $request->linha_01_individual;
            // $corretora->linha_02_individual = $request->linha_02_individual;
            // $corretora->linha_03_individual = $request->linha_03_individual;
            // $corretora->save();
            // return redirect()->route('corretora.index')->with('alterado','Dados Alterados com Sucesso');  
        } else {
            $dados = $request->all();
            $dados['consultas_eletivas'] = str_replace([".",","],["","."],$request->consultas_eletivas);
            $dados['consultas_urgencia'] = str_replace([".",","],["","."],$request->consultas_urgencia);
            $dados['exames_simples'] = str_replace([".",","],["","."],$request->exames_simples);
            $dados['exames_complexos'] = str_replace([".",","],["","."],$request->exames_complexos);
            
            if(!empty($request->file('logo'))) {
                $dados['logo'] = $request->file('logo')->store('corretora','public');
            }
            
            $this->repository->create($dados);
            return redirect()->route('corretora.index')->with('cadastrado','Cadastrado com Sucesso');           
        }
        
        
    }

}
