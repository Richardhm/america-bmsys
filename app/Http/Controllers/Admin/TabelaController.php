<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Planos;
use App\Models\Administradoras;
use App\Models\Acomodacao;
use App\Models\FaixaEtaria;
use App\Models\TabelaOrigens;
use App\Models\Tabela;
use Illuminate\Support\Facades\DB;

class TabelaController extends Controller
{
    public function index()
    {
        $administradoras = Administradoras::all();
        $planos = Planos::all();
        $acomodacao = Acomodacao::all();
        $faixas = FaixaEtaria::all();

        $tabela_origem = TabelaOrigens::all();

        return view('admin.pages.tabelas.index',[
            "administradoras" => $administradoras,
            "planos" => $planos,
            "acomodacao" => $acomodacao,
            "faixas" => $faixas,
            "tabela_origem" => $tabela_origem
        ]);
    }

    public function search()
    {
        
        $administradoras = Administradoras::all();
        $tipos = Planos::all();    
        
        $modelos = Acomodacao::all();
        $cidades =  TabelaOrigens::all();

        

        return view('admin.pages.tabelas.search',[
            
            "administradoras" => $administradoras,
            "tipos" => $tipos,
            "modelos" => $modelos,
            "cidades" => $cidades
        ]);
    }

    public function pesquisar(Request $request)
    {
        
        $rules = [
            
            "administradora_search" => "required",
            "planos_search" => "required",
            "coparticipacao_search" => "required",
            "odonto_search" => "required",
            "cidade_search" => "required"
        ];

        $message = [
            
            "administradora_search.required" => "O campo administradora e campo obrigatorio",
            "planos_search.required" => "O campo plano e campo obrigatorio",
            "coparticipacao_search.required" => "O campo coparticipacao e campo obrigatorio",
            "odonto_search.required" => "O campo odonto e campo obrigatorio",
            "cidade_search.required" => "O campo cidade e campo obrigatorio"
        ];

        $request->validate($rules,$message);
        
        $administradora = $request->administradora_search;
        $planos = $request->planos_search;
        $coparticipacao = ($request->coparticipacao_search == "sim" ? 1 : 0);
        $odonto = ($request->odonto_search == "sim" ? 1 : 0);
        $cidade = $request->cidade_search;
        
        
        

        $tabelas = DB::select("SELECT faixas,apartamento,id_apartamento,enfermaria,id_enfermaria,ambulatorial,id_ambulatorial FROM (
                select 
                    (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
                    (SELECT valor FROM tabelas AS dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS apartamento,
                    (SELECT id    FROM tabelas AS dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS id_apartamento,
                    
                    (SELECT valor FROM tabelas as dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS enfermaria,
                    (SELECT id FROM tabelas AS dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS id_enfermaria,
                    (SELECT valor FROM tabelas as dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS ambulatorial, 
                    (SELECT id FROM tabelas as dentro where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id) AS id_ambulatorial 
                    
                    
                    from tabelas AS fora 
                    where administradora_id = ".$administradora." AND plano_id = ".$planos." AND coparticipacao = ".$coparticipacao." AND odonto = ".$odonto." AND tabela_origens_id = ".$cidade." GROUP BY faixa_etaria_id ORDER BY id) AS full_tabela");
        

        
        $administradoras = Administradoras::all();
        $tipos = Planos::whereRaw("empresarial IS NULL")->get();   
        
        $cidades =  TabelaOrigens::all();


        return view("admin.pages.tabelas.search",[
                "header" => "",
                "tabelas" => $tabelas,
                "cidades" => $cidades,
                "administradoras" => $administradoras,
                "tipos" => $tipos,
                
                
                "administradora_id" => $administradora ?? "",
                "plano_id" => !empty($planos) ? $planos : "",
                "cidade_id" => !empty($cidade) ? $cidade : "",    
                "coparticipacao" => ($request->coparticipacao_search == "sim" ? 1 : 0),
                "odonto" => ($request->odonto_search == "sim" ? 1 : 0),
                "coparticipacao_texto" => ($request->coparticipacao_search == "sim" ? "Com Coparticipacao" : "Sem Coparticipacao"),
                "odonto_texto" => ($request->odonto_search == "sim" ? "Com Odonto" : "Sem Odonto"),
                "administradora_texto" => Administradoras::where("id",$request->administradora_search)->selectRaw("nome")->first()->nome
            ]);    
    }

    public function store(Request $request) 
    {

         foreach($request->faixa_etaria_id_apartamento as $k => $v) {
                $tabela = new Tabela();
                
                $tabela->administradora_id = $request->administradora;
                $tabela->plano_id = $request->planos;
                $tabela->tabela_origens_id = $request->tabela_origem;
                $tabela->acomodacao_id = 1;
                
                $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
                $tabela->odonto = ($request->odonto == "sim" ? true : false);
                $tabela->faixa_etaria_id = $request->faixa_etaria_id_apartamento[$k];
                $tabela->valor = str_replace([".",","],["","."],$request->valor_apartamento[$k]);
                $tabela->save();
            }

            foreach($request->faixa_etaria_id_enfermaria as $k => $v) {
                $tabela = new Tabela();

                $tabela->administradora_id = $request->administradora;
                $tabela->plano_id = $request->planos;
                $tabela->tabela_origens_id = $request->tabela_origem;
                $tabela->acomodacao_id = 2;
                
                $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
                $tabela->odonto = ($request->odonto == "sim" ? true : false);

                $tabela->faixa_etaria_id = $request->faixa_etaria_id_enfermaria[$k];
                $tabela->valor = str_replace([".",","],["","."],$request->valor_enfermaria[$k]);
                
                $tabela->save();
            }

            foreach($request->faixa_etaria_id_ambulatorial as $k => $v) {
                $tabela = new Tabela();
                
                $tabela->administradora_id = $request->administradora;
                $tabela->plano_id = $request->planos;
                $tabela->tabela_origens_id = $request->tabela_origem;
                $tabela->acomodacao_id = 3;
                
                $tabela->coparticipacao = ($request->coparticipacao == "sim" ? true : false);
                $tabela->odonto = ($request->odonto == "sim" ? true : false);

                $tabela->faixa_etaria_id = $request->faixa_etaria_id_ambulatorial[$k];
                $tabela->valor = str_replace([".",","],["","."],$request->valor_ambulatorial[$k]);


                $tabela->save();
            }

            return redirect()->route('tabela.index')->with('success',"A tabela foi cadastrada com sucesso");




    }


    public function edit(Request $request)
    {
        $id = $request->id;
        $alt = Tabela::where("id",$id)->first();
        $alt->valor = str_replace([".",","],["","."],$request->valor);
        if($alt->save()) {
            return "alterado";
        } else {
            return "error";
        }
    }



}
