<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Administradoras;
use App\Models\TabelaOrigens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.pages.home.administrador');
    }

    public function tabelaPrecoResposta(Request $request)
    {
        $id = $request->administradora;

        $tabelas = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,apartamento_com_coparticipacao_com_odonto,enfermaria_com_coparticipacao_com_odonto,apartamento_sem_coparticipacao_com_odonto,enfermaria_sem_coparticipacao_com_odonto FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_sem_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_sem_coparticipacao_com_odonto
                from tabelas AS fora WHERE fora.administradora_id = $id

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");

        $ambulatorial = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,ambulatorial_com_coparticipacao,ambulatorial_com_coparticipacao_total,ambulatorial_sem_coparticipacao,ambulatorial_sem_coparticipacao_total FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao_total,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao_total

                from tabelas AS fora WHERE fora.administradora_id = $id AND acomodacao_id = 3 AND valor != 0

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");

        return view("admin.pages.home.resultado-search",[

            "tabelas" => $tabelas,
            "card_inicial" => $tabelas[0]->card,
            "ambulatorial" => $ambulatorial,
            'card_incial_ambulatorial' => count($ambulatorial) >= 1 ? $ambulatorial[0]->card : ""

        ]);



    }


    public function tabelaPrecoRespostaCidade(Request $request)
    {
        $id = $request->administradora;


        if($request->cidade != null) {
            $cidade = $request->cidade;

            $tabelas = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,apartamento_com_coparticipacao_com_odonto,enfermaria_com_coparticipacao_com_odonto,apartamento_sem_coparticipacao_com_odonto,enfermaria_sem_coparticipacao_com_odonto FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_sem_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_sem_coparticipacao_com_odonto
                from tabelas AS fora WHERE fora.administradora_id = $id AND tabela_origens_id = $cidade

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");




            $ambulatorial = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,ambulatorial_com_coparticipacao,ambulatorial_com_coparticipacao_total,ambulatorial_sem_coparticipacao,ambulatorial_sem_coparticipacao_total FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao_total,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao_total

                from tabelas AS fora WHERE fora.administradora_id = $id AND acomodacao_id = 3 AND valor != 0 AND tabela_origens_id = $cidade

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");


            if(count($tabelas) > 0) {
                return view("admin.pages.home.resultado-search",[

                    "tabelas" => $tabelas,
                    "card_inicial" => $tabelas[0]->card,
                    "ambulatorial" => $ambulatorial,
                    'card_incial_ambulatorial' => count($ambulatorial) >= 1 ? $ambulatorial[0]->card : ""

                ]);
            } else {
                return "error_vazio";
            }













        } else {

            if(auth()->user()->id == 1 || auth()->user()->id == 23 || auth()->user()->id == 316) {
                $tabelas = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,apartamento_com_coparticipacao_com_odonto,enfermaria_com_coparticipacao_com_odonto,apartamento_sem_coparticipacao_com_odonto,enfermaria_sem_coparticipacao_com_odonto FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_sem_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_sem_coparticipacao_com_odonto
                from tabelas AS fora WHERE fora.administradora_id = $id

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");

                $ambulatorial = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,ambulatorial_com_coparticipacao,ambulatorial_com_coparticipacao_total,ambulatorial_sem_coparticipacao,ambulatorial_sem_coparticipacao_total FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao_total,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao_total

                from tabelas AS fora WHERE fora.administradora_id = $id AND acomodacao_id = 3 AND valor != 0

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");
            } else {
                $tabelas = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,apartamento_com_coparticipacao_com_odonto,enfermaria_com_coparticipacao_com_odonto,apartamento_sem_coparticipacao_com_odonto,enfermaria_sem_coparticipacao_com_odonto FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_sem_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_sem_coparticipacao_com_odonto
                from tabelas AS fora WHERE fora.administradora_id = $id and fora.tabela_origens_id != 5

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");

                $ambulatorial = DB::select("SELECT faixas,administradora,card,cidade,plano,odontos,ambulatorial_com_coparticipacao,ambulatorial_com_coparticipacao_total,ambulatorial_sem_coparticipacao,ambulatorial_sem_coparticipacao_total FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,'Sem Odonto','Com Odonto') AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),'_',dentro.plano_id,'_',dentro.tabela_origens_id,'_',dentro.coparticipacao,'_',dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_com_coparticipacao_total,

                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao,
                    (SELECT valor  FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 3 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS ambulatorial_sem_coparticipacao_total

                from tabelas AS fora WHERE fora.administradora_id = $id AND acomodacao_id = 3 AND valor != 0 and fora.tabela_origens_id != 5

                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela");
            }




            return view("admin.pages.home.resultado-search",[

                "tabelas" => $tabelas,
                "card_inicial" => $tabelas[0]->card,
                "ambulatorial" => $ambulatorial,
                'card_incial_ambulatorial' => count($ambulatorial) >= 1 ? $ambulatorial[0]->card : ""

            ]);



        }
    }






    public function search()
    {

        $administradoras = Administradoras::orderByRaw("CASE WHEN id = 5 THEN 1 ELSE 0 END, id DESC")->get();



        if(auth()->user()->id == 1 || auth()->user()->id == 23 || auth()->user()->id == 316) {
            $cidades = TabelaOrigens::all();
        } else {
            $cidades = TabelaOrigens::where("id","!=",5)->get();
        }


        $tabelas = DB::select('SELECT faixas,administradora,card,cidade,plano,odontos,apartamento_com_coparticipacao_com_odonto,enfermaria_com_coparticipacao_com_odonto,apartamento_sem_coparticipacao_com_odonto,enfermaria_sem_coparticipacao_com_odonto FROM (
            SELECT
            (SELECT nome FROM faixa_etarias WHERE faixa_etarias.id = fora.faixa_etaria_id) AS faixas,
            (SELECT logo FROM administradoras as aa WHERE aa.id = fora.administradora_id) AS administradora,
            (SELECT nome FROM tabela_origens as cc WHERE cc.id = fora.tabela_origens_id) AS cidade,
            (SELECT nome FROM planos as pp WHERE pp.id = fora.plano_id) AS plano,
            (SELECT if(dentro.odonto = 0,"Sem Odonto","Com Odonto") AS odontos  FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS odontos,
                (SELECT
                    CONCAT((SELECT nome FROM administradoras as aa WHERE aa.id = dentro.administradora_id),"_",dentro.plano_id,"_",dentro.tabela_origens_id,"_",dentro.coparticipacao,"_",dentro.odonto) FROM tabelas AS dentro WHERE dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.coparticipacao = fora.coparticipacao AND dentro.odonto = fora.odonto AND dentro.tabela_origens_id = fora.tabela_origens_id AND dentro.faixa_etaria_id = fora.faixa_etaria_id LIMIT 1) AS card,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 1 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_com_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 1 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS apartamento_sem_coparticipacao_com_odonto,
                    (SELECT valor FROM tabelas AS dentro where dentro.administradora_id = fora.administradora_id AND dentro.plano_id = fora.plano_id AND dentro.tabela_origens_id = fora.tabela_origens_id AND acomodacao_id = 2 AND dentro.faixa_etaria_id = fora.faixa_etaria_id AND dentro.coparticipacao = 0 AND dentro.odonto = fora.odonto GROUP BY dentro.coparticipacao) AS enfermaria_sem_coparticipacao_com_odonto
                from tabelas AS fora
                GROUP BY faixa_etaria_id,administradora_id,plano_id,tabela_origens_id,odonto ORDER BY id)
            AS full_tabela');

        return view("admin.pages.home.search",[

            "tabelas" => $tabelas,
            "card_inicial" => $tabelas[0]->card,
            "administradoras" => $administradoras,
            "cidades" => $cidades
        ]);



        //return view('admin.pages.home.');
    }


    public function consultar()
    {
        // $data_inicio = new \DateTime("2016-07-08");
        // $data_fim = new \DateTime("2016-08-08");

        // // Resgata diferença entre as datas
        // $dateInterval = $data_inicio->diff($data_fim);
        // dd($dateInterval->days);


        return view('admin.pages.home.consultar');
    }

    public function consultarCarteirnha(Request $request)
    {
        $cpf = str_replace([".","-"],"",$request->cpf);
        $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario?cpf=$cpf";
        $ca = curl_init($url);
        curl_setopt($ca,CURLOPT_URL,$url);
        curl_setopt($ca,CURLOPT_RETURNTRANSFER,true);
        $resultado = (array) json_decode(curl_exec($ca),true);

        if(count($resultado) != 0) {
            $key = array_search("SAUDE",array_column($resultado, 'tipoPlanoC'));
            $carteirinha = $resultado[$key]['cdUsuario'];
            $dados = $resultado[$key];
            $urlc = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario/{$carteirinha}/financeiro/historico";
            $ch = curl_init($urlc);
            curl_setopt($ch, CURLOPT_URL, $urlc);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $resultado_final = json_decode(curl_exec($ch));
            $urllast = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/teleatendimento/beneficiario/{$carteirinha}";
            $chlast = curl_init($urllast);
            curl_setopt($chlast, CURLOPT_URL, $urllast);
            curl_setopt($chlast, CURLOPT_RETURNTRANSFER, true);
            $resultado_last = json_decode(curl_exec($chlast));
            $celular = "(".substr($resultado_last->nuFone,0,2).") ".substr($resultado_last->nuFone,2,1)." ".substr($resultado_last->nuFone,3,8);
            if($resultado_final != null && count($resultado_final) >= 1) {
                sort($resultado_final);
            } else {
                $resultado_final = [];
            }
            return view('admin.pages.financeiro.detalhe-consultar',[
                "resultado" => $resultado_final,
                "dados" => $dados,
                "last" => $resultado_last,
                "celular" => $celular
            ]);
        } else {
            return "error";
        }




    }




}
