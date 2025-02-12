<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ComissoesCorretoraLancadas;
use App\Models\ComissoesCorretoresLancadas;
use App\Models\Contrato;
use App\Models\ContratoEmpresarial;
use App\Models\User;
use App\Models\ValoresCorretoresLancados;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VendedorController extends Controller
{
    public function index()
    {

        $meses = DB::select(
            "SELECT
                        MONTHNAME(DATA) AS mes_nome,
                        MONTH(DATA) AS mes,
                        SUM(valor_total) AS total_por_mes
                    FROM valores_corretores_lancados
                    WHERE YEAR(DATA) = 2024
                    GROUP BY MONTH(DATA);
                "
        );
        $mesAtualN = date('n');
        $mes_atual = date("m");
        $ano_atual = date("Y");
        $users = User::where("ativo",1)->get();
        $semestre = ($mesAtualN < 7) ? 1 : 2;
        $semestreAtual = "";
        if ($semestre == 1) {
            // Primeiro semestre (de janeiro a junho)
            $startDate = $ano_atual . "-01-01";
            $endDate = $ano_atual . "-06-30";
            $semestreAtual = "1/".date("Y");
        } else {
            // Segundo semestre (de julho a dezembro)
            $startDate = $ano_atual . "-07-01";
            $endDate = $ano_atual . "-12-31";
            $semestreAtual = "2/".date("Y");
        }

        $ranking_semestre = DB::select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  contratos.created_at BETWEEN '$startDate' AND '$endDate')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  contrato_empresarial.created_at BETWEEN '$startDate' AND '$endDate')
            ) as valor
            from comissoes

            inner join users on users.id = comissoes.user_id
            where ranking = 1
            group by user_id order by quantidade desc
            "
        );

        $ranking_ano = DB::select(
            "
            select
            users.name as usuario,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id
							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial
							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as valor
            from comissoes
            inner join users on users.id = comissoes.user_id
            where ranking = 1
            group by user_id order by quantidade desc
            "
        );

        $data_atual = $mes_atual."/".$ano_atual;
        $users = User::where("id","!=",1)->where("ativo",1)->orderBy("name")->get();

        $ranking_mes = DB::select(
            "
            select
            users.name as usuario,
            users.image as image,
            users.image AS imagem,
            (
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes_atual' AND YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes_atual' AND YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as quantidade,
            (
                       (select if(sum(valor_adesao)>0,sum(valor_adesao),0) from clientes
							  INNER JOIN contratos ON contratos.cliente_id = clientes.id

							  where clientes.user_id = comissoes.user_id AND
							  MONTH(contratos.created_at) = '$mes_atual' AND YEAR(contratos.created_at) = '$ano_atual')
                       +
                       (select if(sum(valor_plano)>0,sum(valor_plano),0) from contrato_empresarial

							  where contrato_empresarial.user_id = comissoes.user_id AND
							  MONTH(contrato_empresarial.created_at) = '$mes_atual' AND YEAR(contrato_empresarial.created_at) = '$ano_atual')
            ) as valor
            from comissoes
            inner join users on users.id = comissoes.user_id
            where ranking = 1
            group by user_id order by quantidade desc
            "
        );
        $semestreAtual = (date('n') <= 6) ? 1 : 2;
        $mesInicialSemestre = ($semestreAtual == 1) ? 1 : 7;
        $mesFinalSemestre = ($semestreAtual == 1) ? 6 : 12;
        $anoAtual = date("Y");
        $mesesSelect = DB::select(
            "
                SELECT *
                    FROM (
                        SELECT
                            DATE_FORMAT(created_at, '%m/%Y') AS month_date,
                            CONCAT(
                                CASE
                                    WHEN MONTH(created_at) = 1 THEN 'Janeiro'
                                    WHEN MONTH(created_at) = 2 THEN 'Fevereiro'
                                    WHEN MONTH(created_at) = 3 THEN 'Março'
                                    WHEN MONTH(created_at) = 4 THEN 'Abril'
                                    WHEN MONTH(created_at) = 5 THEN 'Maio'
                                    WHEN MONTH(created_at) = 6 THEN 'Junho'
                                    WHEN MONTH(created_at) = 7 THEN 'Julho'
                                    WHEN MONTH(created_at) = 8 THEN 'Agosto'
                                    WHEN MONTH(created_at) = 9 THEN 'Setembro'
                                    WHEN MONTH(created_at) = 10 THEN 'Outubro'
                                    WHEN MONTH(created_at) = 11 THEN 'Novembro'
                                    WHEN MONTH(created_at) = 12 THEN 'Dezembro'
                                END,
                                '/',
                                YEAR(created_at)
                            ) AS month_name_and_year,
                            YEAR(created_at) AS year,
                            MONTH(created_at) AS month
                        FROM contratos
                        WHERE created_at IS NOT NULL
                        GROUP BY YEAR(created_at), MONTH(created_at)
                    ) AS subquery
                    ORDER BY year DESC, month DESC;
          ");
        // Cache key
        $cacheKey = 'dashboard_vendedores_data_now_' . date('Ym');
        // Consultas otimizadas usando Cache
        $data = Cache::remember($cacheKey, now(), function () use ($startDate,$ano_atual ,$endDate,$semestreAtual,$mesInicialSemestre,$mesFinalSemestre,$anoAtual) {
            return [
                'total_coletivo_quantidade_vidas' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_super_simples_quantidade_vidas' => ContratoEmpresarial::where("plano_id",5)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas' => ContratoEmpresarial::where("plano_id",6)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas' => ContratoEmpresarial::where("plano_id",9)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas' => ContratoEmpresarial::where("plano_id",13)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind' => Contrato
                    //::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ::sum('valor_adesao'),
                'valor_plano_empresar' => ContratoEmpresarial
                    //::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ::sum('valor_plano'),
                'total_vendas' => Contrato
                        ::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                        ->sum('valor_adesao') +

                    ContratoEmpresarial
                        ::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                        ->sum('valor_plano'),
                'total_valor' => Contrato
                    ::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_adesao')
                    +
                    ContratoEmpresarial
                        ::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                        ->sum('valor_plano'),

                'total_individual' => Contrato::where("plano_id",1)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_adesao'),

                'total_coletivo' => Contrato::where("plano_id",3)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_adesao'),

                'total_ss' => ContratoEmpresarial::where('plano_id',5)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),

                'total_sindipao' => ContratoEmpresarial::where('plano_id',6)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),//Sindipão
                'total_sindimaco' => ContratoEmpresarial::where('plano_id',9)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),//Sindimaco
                'total_sincofarma' => ContratoEmpresarial::where('plano_id',13)
                    ->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))
                    ->sum('valor_plano'),

                'total_individual_semestre' => Contrato::where("plano_id", 1)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'total_coletivo_semestre' => Contrato::where("plano_id", 3)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'total_ss_semestre' => ContratoEmpresarial::where('plano_id', 5)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sindipao_semestre' => ContratoEmpresarial::where('plano_id', 6)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sindimaco_semestre' => ContratoEmpresarial::where('plano_id', 9)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_sincofarma_semestre' => ContratoEmpresarial::where('plano_id', 13)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),

                'total_individual_quantidade_vidas_semestre' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 1)
                    ->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)
                    ->whereYear("contratos.created_at", date("Y"))
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_semestre' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 3)
                    ->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)
                    ->whereYear("contratos.created_at", date("Y"))
                    ->sum('quantidade_vidas'),


                'total_super_simples_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",5)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",6)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",9)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas_semestre' => ContratoEmpresarial::where("plano_id",13)
                    ->whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at","<=", $mesFinalSemestre)
                    ->whereYear("created_at",date("Y"))
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind_semestre' => Contrato::whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_adesao'),


                'valor_plano_empresar_semestre' => ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)
                    ->whereMonth("created_at", "<=", $mesFinalSemestre)
                    ->whereYear("created_at", date("Y"))
                    ->sum('valor_plano'),


                'total_valor_semestre' => Contrato::whereMonth("created_at", ">=", $mesInicialSemestre)
                        ->whereMonth("created_at", "<=", $mesFinalSemestre)
                        ->whereYear("created_at", date("Y"))
                        ->sum('valor_adesao') + ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)
                        ->whereMonth("created_at", "<=", $mesFinalSemestre)
                        ->whereYear("created_at", date("Y"))
                        ->sum('valor_plano'),

                'total_individual_ano' => Contrato::where("plano_id", 1)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_adesao'),


                'total_coletivo_ano' => Contrato::where("plano_id", 3)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_adesao'),


                'total_ss_ano' => ContratoEmpresarial::where('plano_id', 5)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sindipao_ano' => ContratoEmpresarial::where('plano_id', 6)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sindimaco_ano' => ContratoEmpresarial::where('plano_id', 9)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),

                'total_sincofarma_ano' => ContratoEmpresarial::where('plano_id', 13)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('valor_plano'),


                'total_individual_quantidade_vidas_ano' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 1)
                    ->whereYear("contratos.created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_ano' => Cliente::select("*")
                    ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->where("contratos.plano_id", 3)
                    ->whereYear("contratos.created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_super_simples_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 5)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sindipao_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 6)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sindimaco_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 9)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'total_sincofarma_quantidade_vidas_ano' => ContratoEmpresarial::where("plano_id", 13)
                    ->whereYear("created_at", $anoAtual)
                    ->sum('quantidade_vidas'),

                'valor_adesao_col_ind_ano' => Contrato::whereYear("created_at", $anoAtual)->sum('valor_adesao'),


                'valor_plano_empresar_ano' => ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano'),


                'total_valor_ano' => Contrato::whereYear("created_at", $anoAtual)->sum('valor_adesao') + ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano'),

                'totalContratoEmpresarial' => ContratoEmpresarial::whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas'),
                'totalClientes' => Cliente::select("*")->join("contratos","contratos.cliente_id","=","clientes.id")->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas'),
                'totalGeralVidas' =>
                    ContratoEmpresarial
                        ::whereMonth("created_at",date("m"))
                        ->whereYear("created_at",date("Y"))
                        ->sum('quantidade_vidas')
                    +
                    Cliente::select("*")->join("contratos","contratos.cliente_id","=","clientes.id")
                        ->whereMonth("contratos.created_at",date("m"))
                        ->whereYear("contratos.created_at",date("Y"))
                        ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_janeiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_fevereiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_marco' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_abril' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_maio' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_junho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_julho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'07')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_agosto' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_setembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_outubro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",10)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_novembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",11)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_coletivo_quantidade_vidas_dezembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",3)
                    ->whereMonth("contratos.created_at",12)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_janeiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_fevereiro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_marco' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),


                'total_individual_quantidade_vidas_abril' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_maio' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_junho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_julho' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",07)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_agosto' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_setembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_outubro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'10')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_novembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'11')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'total_individual_quantidade_vidas_dezembro' => Cliente::select("*")
                    ->join('contratos','contratos.cliente_id','=','clientes.id')
                    ->where("contratos.plano_id",1)
                    ->whereMonth("contratos.created_at",'12')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJaneiro' => ContratoEmpresarial
                    ::whereMonth("created_at",01)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialFevereiro' => ContratoEmpresarial
                    ::whereMonth("created_at",02)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialMarco' => ContratoEmpresarial
                    ::whereMonth("created_at",03)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialAbril' => ContratoEmpresarial
                    ::whereMonth("created_at",04)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialMaio' => ContratoEmpresarial
                    ::whereMonth("created_at",05)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJunho' => ContratoEmpresarial
                    ::whereMonth("created_at",06)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialJulho' => ContratoEmpresarial
                    ::whereMonth("created_at",07)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialAgosto' => ContratoEmpresarial
                    ::whereMonth("created_at",'08')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialSetembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'09')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialOutubro' => ContratoEmpresarial
                    ::whereMonth("created_at",'10')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialNovembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'11')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),

                'totalContratoEmpresarialDezembro' => ContratoEmpresarial
                    ::whereMonth("created_at",'12')
                    ->whereYear("created_at",$ano_atual)
                    ->sum('quantidade_vidas'),


                'tabela_folha_janeiro_comissao' => ValoresCorretoresLancados::whereMonth("data",01)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_fevereiro_comissao' => ValoresCorretoresLancados::whereMonth("data",02)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_marco_comissao' => ValoresCorretoresLancados::whereMonth("data",03)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_abril_comissao' => ValoresCorretoresLancados::whereMonth("data",04)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_maio_comissao' => ValoresCorretoresLancados::whereMonth("data",05)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_junho_comissao' => ValoresCorretoresLancados::whereMonth("data",06)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_julho_comissao' => ValoresCorretoresLancados::whereMonth("data",07)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_agosto_comissao' => ValoresCorretoresLancados::whereMonth("data",'08')->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_setembro_comissao' => ValoresCorretoresLancados::whereMonth("data",'09')->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_outubro_comissao' => ValoresCorretoresLancados::whereMonth("data",10)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_novembro_comissao' => ValoresCorretoresLancados::whereMonth("data",11)->whereYear("data",$ano_atual)->sum('valor_total'),
                'tabela_folha_dezembro_comissao' => ValoresCorretoresLancados::whereMonth("data",12)->whereYear("data",$ano_atual)->sum('valor_total'),


                'tabela_folha_janeiro_planos' => Contrato::select("*")
                        ->join('clientes','clientes.id','=','contratos.cliente_id')
                        ->whereMonth("contratos.created_at",01)
                        ->whereYear("contratos.created_at",2024)
                        ->sum('valor_plano') +
                    ContratoEmpresarial
                        ::whereMonth("created_at",01)
                        ->whereYear("created_at",2024)
                        ->sum('valor_plano'),


                'tabela_folha_fevereiro_planos' =>
                    Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",02)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_marco_planos' =>
                Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",03)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_abril_planos' => Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",04)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_maio_planos' => Contrato
                    ::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",05)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_junho_planos' => Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",06)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_julho_planos' =>
                Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",07)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",07)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_agosto_planos' => Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",'08')
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_setembro_planos' => Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",'09')
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_outubro_planos' => Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",10)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",10)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_novembro_planos' => Contrato
                    ::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",11)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",11)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),

            'tabela_folha_dezembro_planos' => Contrato
                    ::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",12)
                    ->whereYear("contratos.created_at",2024)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",12)
                    ->whereYear("created_at",2024)
                    ->sum('valor_plano'),



                'total_comissao' => ValoresCorretoresLancados::whereMonth("created_at",date('m'))->whereYear("created_at",date('Y'))->sum('valor_comissao'),





                //'total_comissao' => ValoresCorretoresLancados::sum('valor_comissao'),
                'total_quantidade_vidas_geral' => Cliente::sum('quantidade_vidas') + ContratoEmpresarial::sum('quantidade_vidas'),
                'total_quantidade_vidas_geral_mes' => Cliente::whereMonth("created_at",date('m'))->whereYear("created_at",date('Y'))->sum('quantidade_vidas') + ContratoEmpresarial::whereMonth("created_at",date('m'))->whereYear("created_at",date('Y'))->sum('quantidade_vidas'),

                'total_quantidade_vidas_individual_geral' => Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',1)->sum('quantidade_vidas'),
                'total_valor_individual_geral' => Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',1)->sum('valor_plano'),


                'total_quantidade_vidas_coletivo_geral' => Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',3)->sum('quantidade_vidas'),
                'total_valor_coletivo_geral' => Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',3)->sum('valor_plano'),

                'total_quantidade_vidas_ss_geral' => ContratoEmpresarial::where("plano_id",5)->sum('quantidade_vidas'),
                'total_valor_ss_geral' => ContratoEmpresarial::where("plano_id",5)->sum('valor_plano'),

                'total_quantidade_vidas_sindipao_geral' => ContratoEmpresarial::where("plano_id",6)->sum('quantidade_vidas'),
                'total_valor_sindipao_geral' => ContratoEmpresarial::where("plano_id",6)->sum('valor_plano'),

                'total_quantidade_vidas_sindimaco_geral' => ContratoEmpresarial::where("plano_id",9)->sum('quantidade_vidas'),
                'total_valor_sindimaco_geral' => ContratoEmpresarial::where("plano_id",9)->sum('valor_plano'),

                'total_quantidade_vidas_sincofarma_geral' => ContratoEmpresarial::where("plano_id",13)->sum('quantidade_vidas'),
                'total_valor_sincofarma_geral' => ContratoEmpresarial::where("plano_id",13)->sum('valor_plano')
            ];
        });


        $dados_tabela = DB::select(
            "
            SELECT
                u.name AS user_name,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 1 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS individual,
                SUM(CASE WHEN co.empresarial = 0 AND co.plano_id = 3 AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas ELSE 0 END) AS coletivo,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 4 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS pme,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 5 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS super_simples,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 6 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindipao,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 9 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sindimaco,
                SUM(CASE WHEN co.empresarial = 1 AND co.plano_id = 13 AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas ELSE 0 END) AS sincofarma,
                SUM(COALESCE(
                    CASE WHEN co.empresarial = 0 AND co.plano_id IN (1, 3) AND MONTH(c.created_at) = $mes_atual AND YEAR(c.created_at) = $ano_atual THEN cl.quantidade_vidas
                         WHEN co.empresarial = 1 AND co.plano_id IN (4, 5, 6, 9, 13) AND MONTH(ce.created_at) = $mes_atual AND YEAR(ce.created_at) = $ano_atual THEN ce.quantidade_vidas
                         ELSE 0
                    END, 0
                )) AS total
            FROM
                comissoes AS co
            LEFT JOIN
                contratos AS c ON c.id = co.contrato_id
            LEFT JOIN
                clientes AS cl ON cl.id = c.cliente_id
            LEFT JOIN
                contrato_empresarial AS ce ON ce.id = co.contrato_empresarial_id
            LEFT JOIN
                users AS u ON u.id = co.user_id
            WHERE
                u.ativo = 1
            GROUP BY
                u.id
            ORDER BY
                total DESC;
            ");









        return view('admin.pages.vendedor.index',[
            "users" => $users,
            "dados_tabela" => $dados_tabela,
            "ranking_mes" => $ranking_mes,
            "ranking_semestre" => $ranking_semestre,
            "ano_atual" => $ano_atual,
            "ranking_ano" => $ranking_ano,
            "semestreAtual" => $semestreAtual,
            "quantidade_vidas" => $data['totalGeralVidas'],
            "meses" => $meses,
            "total_vendas" => $data['total_vendas'],
            "total_comissao" => $data['total_comissao'],
            'total_quantidade_vidas_geral' => $data['total_quantidade_vidas_geral'],
            "total_coletivo_quantidade_vidas" => $data['total_coletivo_quantidade_vidas'],
            "total_individual_quantidade_vidas" => $data['total_individual_quantidade_vidas'],
            "total_super_simples_quantidade_vidas" => $data['total_super_simples_quantidade_vidas'],
            "total_sindipao_quantidade_vidas" => $data['total_sindipao_quantidade_vidas'],
            "total_sindimaco_quantidade_vidas" => $data['total_sindimaco_quantidade_vidas'],
            "total_sincofarma_quantidade_vidas" => $data['total_sincofarma_quantidade_vidas'],
            "total_sindicato_vidas" => $data['total_sindipao_quantidade_vidas'] + $data['total_sindimaco_quantidade_vidas'] + $data['total_sincofarma_quantidade_vidas'],


            "total_quantidade_vidas_individual_geral" => $data['total_quantidade_vidas_individual_geral'],
            "total_valor_individual_geral" => $data['total_valor_individual_geral'],

            "total_quantidade_vidas_coletivo_geral" => $data['total_quantidade_vidas_coletivo_geral'],
            "total_valor_coletivo_geral" => $data["total_valor_coletivo_geral"],
            "total_quantidade_vidas_geral_mes" => $data['total_quantidade_vidas_geral_mes'],

            "total_quantidade_vidas_ss_geral" => $data["total_quantidade_vidas_ss_geral"],
            "total_valor_ss_geral" => $data['total_valor_ss_geral'],

            "total_quantidade_vidas_sindipao_geral" => $data['total_quantidade_vidas_sindipao_geral'],
            "total_valor_sindipao_geral" => $data['total_valor_sindipao_geral'],

            "total_quantidade_vidas_sindimaco_geral" => $data['total_quantidade_vidas_sindimaco_geral'],
            "total_valor_sindimaco_geral" => $data['total_valor_sindimaco_geral'],

            "total_quantidade_vidas_sincofarma_geral" => $data["total_quantidade_vidas_sincofarma_geral"],
            "total_valor_sincofarma_geral" => $data["total_valor_sincofarma_geral"],

            "total_quantidade_vidas_sindicato_geral" => $data['total_quantidade_vidas_sindipao_geral'] + $data['total_quantidade_vidas_sindimaco_geral'] + $data["total_quantidade_vidas_sincofarma_geral"],
            "total_valor_sindicato_geral" => $data['total_valor_sindipao_geral'] + $data['total_valor_sindimaco_geral'] + $data["total_valor_sincofarma_geral"],



            "total_valor" =>  $data['total_valor'],
            "total_coletivo" => $data['total_coletivo'],
            "total_individual" => $data['total_individual'],
            "total_super_simples" => $data['total_ss'],
            "total_sindipao" => $data['total_sindipao'],
            "total_sindimaco" => $data['total_sindimaco'],
            "total_sincofarma" => $data['total_sincofarma'],
            "total_sindicato" => $data['total_sindipao'] + $data['total_sindimaco'] + $data['total_sincofarma'],




            "total_individual_quantidade_vidas_semestre" => $data['total_individual_quantidade_vidas_semestre'],
            "total_coletivo_quantidade_vidas_semestre" => $data['total_coletivo_quantidade_vidas_semestre'],
            "total_super_simples_quantidade_vidas_semestre" => $data['total_super_simples_quantidade_vidas_semestre'],
            "total_sindipao_quantidade_vidas_semestre" => $data['total_sindipao_quantidade_vidas_semestre'],
            "total_sindimaco_quantidade_vidas_semestre" => $data['total_sindimaco_quantidade_vidas_semestre'],
            "total_sincofarma_quantidade_vidas_semestre" => $data['total_sincofarma_quantidade_vidas_semestre'],
            "total_quantidade_vidas_semestre" => $data['total_individual_quantidade_vidas_semestre'] + $data['total_coletivo_quantidade_vidas_semestre'] +
                $data['total_super_simples_quantidade_vidas_semestre'] + $data['total_sindipao_quantidade_vidas_semestre'] +
                $data['total_sindimaco_quantidade_vidas_semestre']
                + $data['total_sincofarma_quantidade_vidas_semestre'],

            "total_individual_ano" => $data['total_individual_ano'],
            "total_coletivo_ano" => $data['total_coletivo_ano'],
            "total_ss_ano" => $data['total_ss_ano'],
            "total_sindipao_ano" => $data['total_sindipao_ano'],
            "total_sindimaco_ano" => $data['total_sindimaco_ano'],
            "total_sincofarma_ano" => $data['total_sincofarma_ano'],
            "total_individual_quantidade_vidas_ano" => $data['total_individual_quantidade_vidas_ano'],
            "total_coletivo_quantidade_vidas_ano" => $data['total_coletivo_quantidade_vidas_ano'],
            "total_super_simples_quantidade_vidas_ano" => $data['total_super_simples_quantidade_vidas_ano'],
            "total_sindipao_quantidade_vidas_ano" => $data['total_sindipao_quantidade_vidas_ano'],
            "total_sindimaco_quantidade_vidas_ano" => $data['total_sindimaco_quantidade_vidas_ano'],
            "total_sincofarma_quantidade_vidas_ano" => $data['total_sincofarma_quantidade_vidas_ano'],
            "total_quantidade_vidas_ano" => $data['total_individual_quantidade_vidas_ano'] + $data['total_coletivo_quantidade_vidas_ano'] +
                $data['total_super_simples_quantidade_vidas_ano']
                + $data['total_sindipao_quantidade_vidas_ano'] + $data['total_sindimaco_quantidade_vidas_ano'] + $data['total_sincofarma_quantidade_vidas_ano'],
            "valor_adesao_col_ind_ano" => $data['valor_adesao_col_ind_ano'],
            "valor_plano_empresar_ano" => $data['valor_plano_empresar_ano'],
            "total_valor_ano" => $data['total_valor_ano'],
            "total_individual_semestre" => $data['total_individual_semestre'],
            "total_coletivo_semestre" => $data['total_coletivo_semestre'],
            "total_ss_semestre" => $data['total_ss_semestre'],
            "total_sindipao_semestre" => $data['total_sindipao_semestre'],
            "total_sindimaco_semestre" => $data['total_sindimaco_semestre'],
            "total_sincofarma_semestre" => $data['total_sincofarma_semestre'],
            "total_valor_semestre" => $data['total_valor_semestre'],
            "total_coletivo_quantidade_vidas_janeiro" => $data['total_coletivo_quantidade_vidas_janeiro'],
            "total_coletivo_quantidade_vidas_fevereiro" => $data['total_coletivo_quantidade_vidas_fevereiro'],
            "total_coletivo_quantidade_vidas_marco" => $data['total_coletivo_quantidade_vidas_marco'],
            "total_coletivo_quantidade_vidas_abril" => $data['total_coletivo_quantidade_vidas_abril'],
            "total_coletivo_quantidade_vidas_maio" => $data['total_coletivo_quantidade_vidas_maio'],
            "total_coletivo_quantidade_vidas_junho" => $data['total_coletivo_quantidade_vidas_junho'],
            "total_coletivo_quantidade_vidas_julho" => $data['total_coletivo_quantidade_vidas_julho'],
            "total_coletivo_quantidade_vidas_agosto" => $data['total_coletivo_quantidade_vidas_agosto'],
            "total_coletivo_quantidade_vidas_setembro" => $data['total_coletivo_quantidade_vidas_setembro'],
            "total_coletivo_quantidade_vidas_outubro" => $data['total_coletivo_quantidade_vidas_outubro'],
            "total_coletivo_quantidade_vidas_novembro" => $data['total_coletivo_quantidade_vidas_novembro'],
            "total_coletivo_quantidade_vidas_dezembro" => $data['total_coletivo_quantidade_vidas_dezembro'],
            "total_individual_quantidade_vidas_janeiro" => $data['total_individual_quantidade_vidas_janeiro'],
            "total_individual_quantidade_vidas_fevereiro" => $data['total_individual_quantidade_vidas_fevereiro'],
            "total_individual_quantidade_vidas_marco" => $data['total_individual_quantidade_vidas_marco'],
            "total_individual_quantidade_vidas_abril" => $data['total_individual_quantidade_vidas_abril'],
            "total_individual_quantidade_vidas_maio" => $data['total_individual_quantidade_vidas_maio'],
            "total_individual_quantidade_vidas_junho" => $data['total_individual_quantidade_vidas_junho'],
            "total_individual_quantidade_vidas_julho" => $data['total_individual_quantidade_vidas_julho'],
            "total_individual_quantidade_vidas_agosto" => $data['total_individual_quantidade_vidas_agosto'],
            "total_individual_quantidade_vidas_setembro" => $data['total_individual_quantidade_vidas_setembro'],
            "total_individual_quantidade_vidas_outubro" => $data['total_individual_quantidade_vidas_outubro'],
            "total_individual_quantidade_vidas_novembro" => $data['total_individual_quantidade_vidas_novembro'],
            "total_individual_quantidade_vidas_dezembro" => $data['total_individual_quantidade_vidas_dezembro'],
            "totalContratoEmpresarialJaneiro" => $data['totalContratoEmpresarialJaneiro'],
            "totalContratoEmpresarialFevereiro" => $data['totalContratoEmpresarialFevereiro'],
            "totalContratoEmpresarialMarco" => $data['totalContratoEmpresarialMarco'],
            "totalContratoEmpresarialAbril" => $data['totalContratoEmpresarialAbril'],
            "totalContratoEmpresarialMaio" => $data['totalContratoEmpresarialMaio'],
            "totalContratoEmpresarialJunho" => $data['totalContratoEmpresarialJunho'],
            "totalContratoEmpresarialJulho" => $data['totalContratoEmpresarialJulho'],
            "totalContratoEmpresarialAgosto" => $data['totalContratoEmpresarialAgosto'],
            "totalContratoEmpresarialSetembro" => $data['totalContratoEmpresarialSetembro'],
            "totalContratoEmpresarialOutubro" => $data['totalContratoEmpresarialOutubro'],
            "totalContratoEmpresarialNovembro" => $data['totalContratoEmpresarialNovembro'],
            "totalContratoEmpresarialDezembro" => $data['totalContratoEmpresarialDezembro'],
            "data_atual" => $data_atual,
            "mesesSelect" => $mesesSelect,


            'tabela_folha_janeiro_comissao' => $data['tabela_folha_janeiro_comissao'],
            'tabela_folha_fevereiro_comissao' => $data['tabela_folha_fevereiro_comissao'],
            'tabela_folha_marco_comissao' => $data['tabela_folha_marco_comissao'],
            'tabela_folha_abril_comissao' => $data['tabela_folha_abril_comissao'],
            'tabela_folha_maio_comissao' => $data['tabela_folha_maio_comissao'],
            'tabela_folha_junho_comissao' => $data['tabela_folha_junho_comissao'],
            'tabela_folha_julho_comissao' => $data['tabela_folha_julho_comissao'],
            'tabela_folha_agosto_comissao' => $data['tabela_folha_agosto_comissao'],
            'tabela_folha_setembro_comissao' => $data['tabela_folha_setembro_comissao'],
            'tabela_folha_outubro_comissao' => $data['tabela_folha_outubro_comissao'],
            'tabela_folha_novembro_comissao' => $data['tabela_folha_novembro_comissao'],
            'tabela_folha_dezembro_comissao' => $data['tabela_folha_dezembro_comissao'],


            'tabela_folha_janeiro_planos' => $data['tabela_folha_janeiro_planos'],
            'tabela_folha_fevereiro_planos' => $data['tabela_folha_fevereiro_planos'],
            'tabela_folha_marco_planos' => $data['tabela_folha_marco_planos'],
            'tabela_folha_abril_planos' => $data['tabela_folha_abril_planos'],
            'tabela_folha_maio_planos' => $data['tabela_folha_maio_planos'],
            'tabela_folha_junho_planos' => $data['tabela_folha_junho_planos'],
            'tabela_folha_julho_planos' => $data['tabela_folha_julho_planos'],
            'tabela_folha_agosto_planos' => $data['tabela_folha_agosto_planos'],
            'tabela_folha_setembro_planos' => $data['tabela_folha_setembro_planos'],
            'tabela_folha_outubro_planos' => $data['tabela_folha_outubro_planos'],
            'tabela_folha_novembro_planos' => $data['tabela_folha_novembro_planos'],
            'tabela_folha_dezembro_planos' => $data['tabela_folha_dezembro_planos']

        ]);
    }

    public function filtrar(Request $request)
    {
        $user_id = $request->user;
        if($user_id != 0) {
            $imagem = User::find($user_id)->image;
            $mes_atual = date('m');
            $ano_atual = date("Y");
            /***************************Small Box Total************************************/
            $total_individual_quantidade_vidas_small_box = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("user_id",$user_id)->where("contratos.plano_id",1)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_small_box = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where("user_id",$user_id)->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",5)->where("user_id",$user_id)->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",6)->where("user_id",$user_id)->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",9)->where("user_id",$user_id)->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",13)->where("user_id",$user_id)->sum('quantidade_vidas');
            $valor_adesao_col_ind_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("user_id",$user_id)->sum('valor_adesao');
            $valor_plano_empresar_small_box = ContratoEmpresarial::where("user_id",$user_id)->sum('valor_plano');
            $total_valor_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("user_id",$user_id)->sum('valor_adesao')
                + ContratoEmpresarial::where("user_id",$user_id)->sum('valor_plano');

            $total_individual_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where("user_id",$user_id)->sum('valor_adesao');
            $total_coletivo_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where("user_id",$user_id)->sum('valor_adesao');
            $total_ss_small_box = ContratoEmpresarial::where('plano_id',5)->where("user_id",$user_id)->sum('valor_plano');
            $total_sindipao_small_box = ContratoEmpresarial::where('plano_id',6)->where("user_id",$user_id)->sum('valor_plano');
            $total_sindimaco_small_box = ContratoEmpresarial::where('plano_id',9)->where("user_id",$user_id)->sum('valor_plano');
            $total_sincofarma_small_box = ContratoEmpresarial::where('plano_id',13)->where("user_id",$user_id)->sum('valor_plano');
            /***************************Small Box Total************************************/


            /***************************Mês************************************/
            $total_coletivo_quantidade_vidas_mes = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->where("user_id",$user_id)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_mes = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("user_id",$user_id)->where("contratos.plano_id",1)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",5)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",6)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",9)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",13)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');

            $total_individual_mes = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where("user_id",$user_id)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('valor_adesao');
            $total_coletivo_mes = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where("user_id",$user_id)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('valor_adesao');
            $total_ss_mes = ContratoEmpresarial::where('plano_id',5)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sindipao_mes = ContratoEmpresarial::where('plano_id',6)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sindimaco_mes = ContratoEmpresarial::where('plano_id',9)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sincofarma_mes = ContratoEmpresarial::where('plano_id',13)->where("user_id",$user_id)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');

            $total_mes_vidas = $total_coletivo_quantidade_vidas_mes + $total_individual_quantidade_vidas_mes + $total_super_simples_quantidade_vidas_mes + $total_sindipao_quantidade_vidas_mes + $total_sindimaco_quantidade_vidas_mes + $total_sincofarma_quantidade_vidas_mes;
            $total_mes_valor = $total_individual_mes + $total_coletivo_mes + $total_ss_mes + $total_sindipao_mes + $total_sindimaco_mes + $total_sincofarma_mes;
            /***************************Mês************************************/

            /***************************Semestre************************************/
            $semestreAtual = (date('n') <= 6) ? 1 : 2;
            $mesInicialSemestre = ($semestreAtual == 1) ? 1 : 7;
            $mesFinalSemestre = ($semestreAtual == 1) ? 6 : 12;
            $anoAtual = date("Y");

            $total_individual_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where("plano_id", 1)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $total_coletivo_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where("plano_id", 3)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $total_ss_semestre = ContratoEmpresarial::where('plano_id', 5)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sindipao_semestre = ContratoEmpresarial::where('plano_id', 6)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sindimaco_semestre = ContratoEmpresarial::where('plano_id', 9)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sincofarma_semestre = ContratoEmpresarial::where('plano_id', 13)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_individual_quantidade_vidas_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 1)->where('user_id',$user_id)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where("contratos.plano_id", 3)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",5)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",6)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",9)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",13)->where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $valor_adesao_col_ind_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $valor_plano_empresar_semestre = ContratoEmpresarial::where('user_id',$user_id)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_valor_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->where('user_id',$user_id)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao')
                +
                ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->where('user_id',$user_id)->whereYear("created_at", date("Y"))->sum('valor_plano');
            /***************************Semestre************************************/



            /***************************Ano************************************/
            $total_individual_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where("plano_id", 1)->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $total_coletivo_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where("plano_id", 3)->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $total_ss_ano = ContratoEmpresarial::where('plano_id', 5)->where('user_id',$user_id)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sindipao_ano = ContratoEmpresarial::where('plano_id', 6)->where('user_id',$user_id)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sindimaco_ano = ContratoEmpresarial::where('plano_id', 9)->where('user_id',$user_id)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sincofarma_ano = ContratoEmpresarial::where('plano_id', 13)->where('user_id',$user_id)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_vidas_ano = $total_individual_ano + $total_coletivo_ano + $total_ss_ano + $total_sindipao_ano + $total_sindimaco_ano + $total_sincofarma_ano;

            $total_individual_quantidade_vidas_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 1)->where('user_id',$user_id)->whereYear("contratos.created_at", $anoAtual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 3)->where('user_id',$user_id)->whereYear("contratos.created_at", $anoAtual)->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 5)->whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 6)->whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 9)->whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 13)->whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_quantidade_vidas_ano = $total_individual_quantidade_vidas_ano + $total_coletivo_quantidade_vidas_ano + $total_super_simples_quantidade_vidas_ano
                + $total_sindipao_quantidade_vidas_ano + $total_sindimaco_quantidade_vidas_ano + $total_sincofarma_quantidade_vidas_ano;



            $valor_adesao_col_ind_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $valor_plano_empresar_ano = ContratoEmpresarial::whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('valor_plano');
            $total_valor_ano =
                Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->whereYear("contratos.created_at", $anoAtual)->where('user_id',$user_id)->sum('valor_adesao')
                +
                ContratoEmpresarial::whereYear("created_at", $anoAtual)->where('user_id',$user_id)->sum('valor_plano');
            /***************************Ano************************************/



            /**************************Grafico********************************/
            $total_coletivo_quantidade_vidas_janeiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",01)->where('user_id',$user_id)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_fevereiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",02)->where('user_id',$user_id)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_marco = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",03)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_abril = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",04)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_maio = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",05)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_junho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",06)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_julho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",'07')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_agosto = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",'08')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_setembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",'09')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_outubro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",10)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_novembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",11)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_dezembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->where('user_id',$user_id)->whereMonth("contratos.created_at",12)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_janeiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",01)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_fevereiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",02)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_marco = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",03)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_abril = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",04)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_maio = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",05)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_junho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",06)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_julho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",07)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_agosto = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",'08')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_setembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",'09')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_outubro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'10')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_novembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",'11')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_dezembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->where('user_id',$user_id)->whereMonth("contratos.created_at",'12')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJaneiro = ContratoEmpresarial::whereMonth("created_at",01)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialFevereiro = ContratoEmpresarial::whereMonth("created_at",02)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialMarco = ContratoEmpresarial::whereMonth("created_at",03)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialAbril = ContratoEmpresarial::whereMonth("created_at",04)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialMaio = ContratoEmpresarial::whereMonth("created_at",05)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJunho = ContratoEmpresarial::whereMonth("created_at",06)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJulho = ContratoEmpresarial::whereMonth("created_at",07)->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialAgosto = ContratoEmpresarial::whereMonth("created_at",'08')->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialSetembro = ContratoEmpresarial::whereMonth("created_at",'09')->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialOutubro = ContratoEmpresarial::whereMonth("created_at",'10')->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialNovembro = ContratoEmpresarial::whereMonth("created_at",'11')->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialDezembro = ContratoEmpresarial::whereMonth("created_at",'12')->where('user_id',$user_id)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            /**************************Grafico********************************/


            /**************************Geral********************************/
            $total_vidas_geral_individual = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where('plano_id',1)->sum('quantidade_vidas');
            $total_vidas_geral_coletivo = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('user_id',$user_id)->where('plano_id',3)->sum('quantidade_vidas');
            $total_ss_vidas_geral = ContratoEmpresarial::where('plano_id', 5)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sindipao_vidas_geral = ContratoEmpresarial::where('plano_id', 6)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sindimaco_vidas_geral = ContratoEmpresarial::where('plano_id', 9)->where('user_id',$user_id)->sum('quantidade_vidas');
            $total_sincofarma_vidas_geral = ContratoEmpresarial::where('plano_id', 13)->where('user_id',$user_id)->sum('quantidade_vidas');

            $total_valor_individual_geral = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('user_id',$user_id)->where('plano_id',1)->sum('valor_plano');
            $total_valor_coletivo_geral = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('user_id',$user_id)->where('plano_id',3)->sum('valor_plano');
            $total_valor_ss_geral = ContratoEmpresarial::where("plano_id",5)->where('user_id',$user_id)->sum('valor_plano');
            $total_valor_sindipao_geral = ContratoEmpresarial::where("plano_id",6)->where('user_id',$user_id)->sum('valor_plano');
            $total_valor_sindimaco_geral = ContratoEmpresarial::where("plano_id",9)->where('user_id',$user_id)->sum('valor_plano');
            $total_valor_sincofarma_geral = ContratoEmpresarial::where("plano_id",13)->where('user_id',$user_id)->sum('valor_plano');

            $total_comissao = ValoresCorretoresLancados::where('user_id',$user_id)->sum('valor_comissao');
            $total_quantidade_vidas_geral =
                Cliente::where('user_id',$user_id)->sum('quantidade_vidas')
                +
                ContratoEmpresarial::where("user_id",$user_id)->sum('quantidade_vidas');


            if(!empty(User::find($user_id)->image)) {
                $imagem = User::find($user_id)->image;
            } else {
                $imagem = "storage/avatar-default.jpg";
            }
            $nome = User::find($user_id)->name;
            $total_comissao = ValoresCorretoresLancados::where("user_id",$user_id)
                    ->whereMonth("data", date("m"))->whereYear("data", date("Y"))
                    ->sum('valor_comissao');
            $total_vendas =
                Contrato::select('*')->join('clientes','contratos.cliente_id','=','clientes.id')->where('user_id',$user_id)
                        ->whereMonth("contratos.created_at", date("m"))->whereYear("contratos.created_at", date("Y"))
                        ->sum('valor_adesao')
                +
                ContratoEmpresarial::where('user_id',$user_id)
                    ->whereMonth("created_at", date("m"))->whereYear("created_at", date("Y"))
                    ->sum('valor_plano');

            $total_vidas = Contrato::select('*')->join('clientes','contratos.cliente_id','=','clientes.id')->where('user_id',$user_id)->sum('quantidade_vidas')
                + ContratoEmpresarial::where('user_id',$user_id)->sum('quantidade_vidas');

            /**************************Geral********************************/

            /**************************Comissão e Planos********************************/
            $tabela_folha_janeiro_comissao = ValoresCorretoresLancados::whereMonth("data",01)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_fevereiro_comissao = ValoresCorretoresLancados::whereMonth("data",02)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_marco_comissao = ValoresCorretoresLancados::whereMonth("data",03)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_abril_comissao = ValoresCorretoresLancados::whereMonth("data",04)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_maio_comissao = ValoresCorretoresLancados::whereMonth("data",05)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_junho_comissao = ValoresCorretoresLancados::whereMonth("data",06)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_julho_comissao = ValoresCorretoresLancados::whereMonth("data",07)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_agosto_comissao = ValoresCorretoresLancados::whereMonth("data",'08')->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_setembro_comissao = ValoresCorretoresLancados::whereMonth("data",'09')->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_outubro_comissao = ValoresCorretoresLancados::whereMonth("data",10)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_novembro_comissao = ValoresCorretoresLancados::whereMonth("data",11)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_dezembro_comissao = ValoresCorretoresLancados::whereMonth("data",12)->whereYear("data",$ano_atual)->where('user_id',$user_id)->sum('valor_total');
            $tabela_folha_janeiro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",01)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');


            $tabela_folha_fevereiro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",02)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_marco_planos =
                Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial
                    ::where("user_id",$user_id)
                    ->whereMonth("created_at",03)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_abril_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",04)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_maio_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",05)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_junho_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",06)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_julho_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",07)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",07)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_agosto_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",'08')
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_setembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",'09')
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_outubro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",10)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",10)
                    ->whereYear("created_at",2024)->sum('valor_plano');

            $tabela_folha_novembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",11)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",11)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_dezembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",12)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->where("user_id",$user_id)->sum('valor_plano') +
                ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",12)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');
            /**************************Comissão e Planos********************************/









        } else {

            $imagem = 'avatar-default.jpg';
            $mes_atual = date('m');
            $ano_atual = date("Y");
            /***************************Small Box Total************************************/
            $total_individual_quantidade_vidas_small_box = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_small_box = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",5)->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",6)->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",9)->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_small_box = ContratoEmpresarial::where("plano_id",13)->sum('quantidade_vidas');
            $valor_adesao_col_ind_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->sum('valor_adesao');
            $valor_plano_empresar_small_box = ContratoEmpresarial::sum('valor_plano');
            $total_valor_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->sum('valor_adesao')
                + ContratoEmpresarial::sum('valor_plano');

            $total_individual_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->sum('valor_adesao');
            $total_coletivo_small_box = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->sum('valor_adesao');
            $total_ss_small_box = ContratoEmpresarial::where('plano_id',5)->sum('valor_plano');
            $total_sindipao_small_box = ContratoEmpresarial::where('plano_id',6)->sum('valor_plano');
            $total_sindimaco_small_box = ContratoEmpresarial::where('plano_id',9)->sum('valor_plano');
            $total_sincofarma_small_box = ContratoEmpresarial::where('plano_id',13)->sum('valor_plano');
            /***************************Small Box Total************************************/


            /***************************Mês************************************/
            $total_coletivo_quantidade_vidas_mes = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_mes = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",5)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",6)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",9)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_mes = ContratoEmpresarial::where("plano_id",13)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('quantidade_vidas');

            $total_individual_mes = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('valor_adesao');
            $total_coletivo_mes = Cliente::select('*')->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",date("m"))->whereYear("contratos.created_at",date("Y"))->sum('valor_adesao');
            $total_ss_mes = ContratoEmpresarial::where('plano_id',5)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sindipao_mes = ContratoEmpresarial::where('plano_id',6)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sindimaco_mes = ContratoEmpresarial::where('plano_id',9)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');
            $total_sincofarma_mes = ContratoEmpresarial::where('plano_id',13)->whereMonth("created_at",date("m"))->whereYear("created_at",date("Y"))->sum('valor_plano');

            $total_mes_vidas = $total_coletivo_quantidade_vidas_mes + $total_individual_quantidade_vidas_mes + $total_super_simples_quantidade_vidas_mes + $total_sindipao_quantidade_vidas_mes + $total_sindimaco_quantidade_vidas_mes + $total_sincofarma_quantidade_vidas_mes;
            $total_mes_valor = $total_individual_mes + $total_coletivo_mes + $total_ss_mes + $total_sindipao_mes + $total_sindimaco_mes + $total_sincofarma_mes;

            /***************************Mês************************************/



            /***************************Semestre************************************/
            $semestreAtual = (date('n') <= 6) ? 1 : 2;
            $mesInicialSemestre = ($semestreAtual == 1) ? 1 : 7;
            $mesFinalSemestre = ($semestreAtual == 1) ? 6 : 12;
            $anoAtual = date("Y");

            $total_individual_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("plano_id", 1)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $total_coletivo_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("plano_id", 3)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $total_ss_semestre = ContratoEmpresarial::where('plano_id', 5)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sindipao_semestre = ContratoEmpresarial::where('plano_id', 6)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sindimaco_semestre = ContratoEmpresarial::where('plano_id', 9)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_sincofarma_semestre = ContratoEmpresarial::where('plano_id', 13)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_individual_quantidade_vidas_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 1)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 3)->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",5)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",6)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",9)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_semestre = ContratoEmpresarial::where("plano_id",13)->whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at","<=", $mesFinalSemestre)->whereYear("created_at",date("Y"))->sum('quantidade_vidas');
            $valor_adesao_col_ind_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao');
            $valor_plano_empresar_semestre = ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            $total_valor_semestre = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->whereMonth("contratos.created_at", ">=", $mesInicialSemestre)->whereMonth("contratos.created_at", "<=", $mesFinalSemestre)->whereYear("contratos.created_at", date("Y"))->sum('valor_adesao')
                +
                ContratoEmpresarial::whereMonth("created_at", ">=", $mesInicialSemestre)->whereMonth("created_at", "<=", $mesFinalSemestre)->whereYear("created_at", date("Y"))->sum('valor_plano');
            /***************************Semestre************************************/



            /***************************Ano************************************/
            $total_individual_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("plano_id", 1)->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $total_coletivo_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("plano_id", 3)->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $total_ss_ano = ContratoEmpresarial::where('plano_id', 5)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sindipao_ano = ContratoEmpresarial::where('plano_id', 6)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sindimaco_ano = ContratoEmpresarial::where('plano_id', 9)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_sincofarma_ano = ContratoEmpresarial::where('plano_id', 13)->whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_vidas_ano = $total_individual_ano + $total_coletivo_ano + $total_ss_ano + $total_sindipao_ano + $total_sindimaco_ano + $total_sincofarma_ano;

            $total_individual_quantidade_vidas_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 1)->whereYear("contratos.created_at", $anoAtual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where("contratos.plano_id", 3)->whereYear("contratos.created_at", $anoAtual)->sum('quantidade_vidas');
            $total_super_simples_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 5)->whereYear("created_at", $anoAtual)->sum('quantidade_vidas');
            $total_sindipao_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 6)->whereYear("created_at", $anoAtual)->sum('quantidade_vidas');
            $total_sindimaco_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 9)->whereYear("created_at", $anoAtual)->sum('quantidade_vidas');
            $total_sincofarma_quantidade_vidas_ano = ContratoEmpresarial::where("plano_id", 13)->whereYear("created_at", $anoAtual)->sum('quantidade_vidas');
            $total_quantidade_vidas_ano = $total_individual_quantidade_vidas_ano + $total_coletivo_quantidade_vidas_ano + $total_super_simples_quantidade_vidas_ano
                + $total_sindipao_quantidade_vidas_ano + $total_sindimaco_quantidade_vidas_ano + $total_sincofarma_quantidade_vidas_ano;



            $valor_adesao_col_ind_ano = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao');
            $valor_plano_empresar_ano = ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano');
            $total_valor_ano =
                Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
                    ->whereYear("contratos.created_at", $anoAtual)->sum('valor_adesao')
                +
                ContratoEmpresarial::whereYear("created_at", $anoAtual)->sum('valor_plano');
            /***************************Ano************************************/



            /**************************Grafico********************************/
            $total_coletivo_quantidade_vidas_janeiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",01)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_fevereiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",02)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_marco = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",03)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_abril = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",04)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_maio = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",05)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_junho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",06)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_julho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",'07')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_agosto = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",'08')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_setembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",'09')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_outubro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",10)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_novembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",11)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_coletivo_quantidade_vidas_dezembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",3)->whereMonth("contratos.created_at",12)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_janeiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",01)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_fevereiro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",02)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_marco = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",03)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_abril = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",04)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_maio = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",05)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_junho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",06)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_julho = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",07)->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_agosto = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'08')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_setembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'09')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_outubro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'10')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_novembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'11')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $total_individual_quantidade_vidas_dezembro = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where("contratos.plano_id",1)->whereMonth("contratos.created_at",'12')->whereYear("contratos.created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJaneiro = ContratoEmpresarial::whereMonth("created_at",01)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialFevereiro = ContratoEmpresarial::whereMonth("created_at",02)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialMarco = ContratoEmpresarial::whereMonth("created_at",03)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialAbril = ContratoEmpresarial::whereMonth("created_at",04)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialMaio = ContratoEmpresarial::whereMonth("created_at",05)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJunho = ContratoEmpresarial::whereMonth("created_at",06)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialJulho = ContratoEmpresarial::whereMonth("created_at",07)->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialAgosto = ContratoEmpresarial::whereMonth("created_at",'08')->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialSetembro = ContratoEmpresarial::whereMonth("created_at",'09')->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialOutubro = ContratoEmpresarial::whereMonth("created_at",'10')->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialNovembro = ContratoEmpresarial::whereMonth("created_at",'11')->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            $totalContratoEmpresarialDezembro = ContratoEmpresarial::whereMonth("created_at",'12')->whereYear("created_at",$ano_atual)->sum('quantidade_vidas');
            /**************************Grafico********************************/


            /**************************Geral********************************/
            $total_vidas_geral_individual = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('plano_id',1)->sum('quantidade_vidas');
            $total_vidas_geral_coletivo = Cliente::select("*")->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')->where('plano_id',3)->sum('quantidade_vidas');
            $total_ss_vidas_geral = ContratoEmpresarial::where('plano_id', 5)->sum('quantidade_vidas');
            $total_sindipao_vidas_geral = ContratoEmpresarial::where('plano_id', 6)->sum('quantidade_vidas');
            $total_sindimaco_vidas_geral = ContratoEmpresarial::where('plano_id', 9)->sum('quantidade_vidas');
            $total_sincofarma_vidas_geral = ContratoEmpresarial::where('plano_id', 13)->sum('quantidade_vidas');

            $total_valor_individual_geral = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',1)->sum('valor_plano');
            $total_valor_coletivo_geral = Cliente::select("*")->join('contratos','contratos.cliente_id','=','clientes.id')->where('plano_id',3)->sum('valor_plano');
            $total_valor_ss_geral = ContratoEmpresarial::where("plano_id",5)->sum('valor_plano');
            $total_valor_sindipao_geral = ContratoEmpresarial::where("plano_id",6)->sum('valor_plano');
            $total_valor_sindimaco_geral = ContratoEmpresarial::where("plano_id",9)->sum('valor_plano');
            $total_valor_sincofarma_geral = ContratoEmpresarial::where("plano_id",13)->sum('valor_plano');

            $total_comissao = ValoresCorretoresLancados::sum('valor_comissao');
            $total_quantidade_vidas_geral =
                Cliente::sum('quantidade_vidas')
                +
                ContratoEmpresarial::sum('quantidade_vidas');
                $imagem = "avatar-default.jpg";

            $nome = 'Accert';
            $total_comissao = ValoresCorretoresLancados::sum('valor_comissao');
            $total_vendas = Contrato::select('*')->join('clientes','contratos.cliente_id','=','clientes.id')->sum('valor_adesao')
                + ContratoEmpresarial::sum('valor_plano');
            $total_vidas = Contrato::select('*')->join('clientes','contratos.cliente_id','=','clientes.id')->sum('quantidade_vidas')
                + ContratoEmpresarial::sum('quantidade_vidas');

            /**************************Geral********************************/

            /**************************Comissão e Planos********************************/
            $tabela_folha_janeiro_comissao = ValoresCorretoresLancados::whereMonth("data",01)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_fevereiro_comissao = ValoresCorretoresLancados::whereMonth("data",02)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_marco_comissao = ValoresCorretoresLancados::whereMonth("data",03)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_abril_comissao = ValoresCorretoresLancados::whereMonth("data",04)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_maio_comissao = ValoresCorretoresLancados::whereMonth("data",05)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_junho_comissao = ValoresCorretoresLancados::whereMonth("data",06)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_julho_comissao = ValoresCorretoresLancados::whereMonth("data",07)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_agosto_comissao = ValoresCorretoresLancados::whereMonth("data",'08')->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_setembro_comissao = ValoresCorretoresLancados::whereMonth("data",'09')->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_outubro_comissao = ValoresCorretoresLancados::whereMonth("data",10)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_novembro_comissao = ValoresCorretoresLancados::whereMonth("data",11)->whereYear("data",$ano_atual)->sum('valor_total');
            $tabela_folha_dezembro_comissao = ValoresCorretoresLancados::whereMonth("data",12)->whereYear("data",$ano_atual)->sum('valor_total');






            $tabela_folha_janeiro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",01)
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial

                    ::whereMonth("created_at",01)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');


            $tabela_folha_fevereiro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",02)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial::whereMonth("created_at",02)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_marco_planos =
                Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",03)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",03)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');

            $tabela_folha_abril_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",04)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",04)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');

            $tabela_folha_maio_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",05)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",05)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');

            $tabela_folha_junho_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",06)
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",06)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');

            $tabela_folha_julho_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",07)
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial::whereMonth("created_at",07)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_agosto_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'08')
                    ->whereYear("contratos.created_at",$ano_atual)
                    ->sum('valor_plano') +
                ContratoEmpresarial
                    ::whereMonth("created_at",'08')
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_setembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",'09')
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial::whereMonth("created_at",'09')
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_outubro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",10)
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial

                    ::whereMonth("created_at",10)
                    ->whereYear("created_at",$ano_atual)->sum('valor_plano');

            $tabela_folha_novembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",11)
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial

                    ::whereMonth("created_at",11)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');

            $tabela_folha_dezembro_planos = Contrato::select("*")
                    ->join('clientes','clientes.id','=','contratos.cliente_id')
                    ->whereMonth("contratos.created_at",12)
                    ->whereYear("contratos.created_at",$ano_atual)

                    ->sum('valor_plano') +
                ContratoEmpresarial

                    ::whereMonth("created_at",12)
                    ->whereYear("created_at",$ano_atual)
                    ->sum('valor_plano');
            /**************************Comissão e Planos********************************/
        }
        return [
            'total_coletivo_quantidade_vidas_small_box' => $total_coletivo_quantidade_vidas_small_box,
            'total_individual_quantidade_vidas_small_box' => $total_individual_quantidade_vidas_small_box,
            'total_super_simples_quantidade_vidas_small_box' => $total_super_simples_quantidade_vidas_small_box,
            'total_sindipao_quantidade_vidas_small_box' => $total_sindipao_quantidade_vidas_small_box,
            'total_sindimaco_quantidade_vidas_small_box' => $total_sindimaco_quantidade_vidas_small_box,
            'total_sincofarma_quantidade_vidas_small_box' => $total_sincofarma_quantidade_vidas_small_box,
            'valor_adesao_col_ind_small_box' => $valor_adesao_col_ind_small_box,
            'valor_plano_empresar_small_box' => $valor_plano_empresar_small_box,
            'total_quantidade_vidas' => $total_coletivo_quantidade_vidas_small_box + $total_individual_quantidade_vidas_small_box + $total_super_simples_quantidade_vidas_small_box
                + $total_sindipao_quantidade_vidas_small_box + $total_sindimaco_quantidade_vidas_small_box + $total_sincofarma_quantidade_vidas_small_box,
            'total_valor' => number_format($total_valor_small_box,2,",","."),
            'total_individual_small_box' => number_format($total_individual_small_box,2,",","."),
            'total_coletivo_small_box' => number_format($total_coletivo_small_box,2,",","."),
            'total_ss_small_box' => number_format($total_ss_small_box,2,",","."),
            'total_sindipao_small_box' => $total_sindipao_small_box,
            'total_sindimaco_small_box' => $total_sindimaco_small_box,
            'total_sincofarma_small_box' => $total_sincofarma_small_box,
            "total_sindicado" => $total_sincofarma_quantidade_vidas_small_box + $total_sindimaco_quantidade_vidas_small_box + $total_sindipao_quantidade_vidas_small_box,
            "total_sindicato_valor" => number_format($total_sindipao_small_box + $total_sindimaco_small_box + $total_sincofarma_small_box,2,",","."),

            /*********************************************MES*********************************************************/
            "total_coletivo_quantidade_vidas_mes" => $total_coletivo_quantidade_vidas_mes,
            "total_individual_quantidade_vidas_mes" => $total_individual_quantidade_vidas_mes,
            "total_super_simples_quantidade_vidas_mes" => $total_super_simples_quantidade_vidas_mes,
            "total_sindipao_quantidade_vidas_mes" => $total_sindipao_quantidade_vidas_mes,
            "total_sindimaco_quantidade_vidas_mes" => $total_sindimaco_quantidade_vidas_mes,
            "total_sincofarma_quantidade_vidas_mes" => $total_sincofarma_quantidade_vidas_mes,
            "total_individual_mes"   => $total_individual_mes,
            "total_coletivo_mes"     => $total_coletivo_mes,
            "total_ss_mes"           => $total_ss_mes,
            "total_sindimaco_mes"    => $total_sindimaco_mes,
            "total_sincofarma_mes"   => $total_sincofarma_mes,
            "total_sindipao_mes"     => $total_sindipao_mes,
            "total_mes_vidas"        => $total_mes_vidas,
            "total_mes_valor"        => number_format($total_mes_valor,2,",","."),
            /*********************************************MES*********************************************************/

            /*********************************************Semestre*********************************************************/
            "total_coletivo_quantidade_vidas_semestre" => $total_coletivo_quantidade_vidas_semestre,
            "total_individual_quantidade_vidas_semestre" => $total_individual_quantidade_vidas_semestre,
            "total_super_simples_quantidade_vidas_semestre" => $total_super_simples_quantidade_vidas_semestre,
            "total_sindipao_quantidade_vidas_semestre" => $total_sindipao_quantidade_vidas_semestre,
            "total_sindimaco_quantidade_vidas_semestre" => $total_sindimaco_quantidade_vidas_semestre,
            "total_sincofarma_quantidade_vidas_semestre" => $total_sincofarma_quantidade_vidas_semestre,
            "total_individual_semestre"   => $total_individual_semestre,
            "total_coletivo_semestre"     => $total_coletivo_semestre,
            "total_ss_semestre"           => $total_ss_semestre,
            "total_sindimaco_semestre"    => $total_sindimaco_semestre,
            "total_sincofarma_semestre"   => $total_sincofarma_semestre,
            "total_sindipao_semestre"     => $total_sindipao_semestre,
            "total_vidas_semestre" => $total_coletivo_quantidade_vidas_semestre + $total_individual_quantidade_vidas_semestre + $total_super_simples_quantidade_vidas_semestre +
                $total_sindipao_quantidade_vidas_semestre +  $total_sindimaco_quantidade_vidas_semestre +  $total_sincofarma_quantidade_vidas_semestre,
            "total_valor_semestre" => number_format($total_individual_semestre+$total_coletivo_semestre+$total_ss_semestre+$total_sindimaco_semestre+$total_sincofarma_semestre+$total_sindipao_semestre,2,",","."),
            /*********************************************Semestre*********************************************************/

            /*********************************************Ano*********************************************************/
            "total_coletivo_quantidade_vidas_ano" => $total_coletivo_quantidade_vidas_ano,
            "total_individual_quantidade_vidas_ano" => $total_individual_quantidade_vidas_ano,
            "total_super_simples_quantidade_vidas_ano" => $total_super_simples_quantidade_vidas_ano,
            "total_sindipao_quantidade_vidas_ano" => $total_sindipao_quantidade_vidas_ano,
            "total_sindimaco_quantidade_vidas_ano" => $total_sindimaco_quantidade_vidas_ano,
            "total_sincofarma_quantidade_vidas_ano" => $total_sincofarma_quantidade_vidas_ano,
            "total_quantidade_vidas_ano" => $total_quantidade_vidas_ano,
            "total_individual_ano"   => number_format($total_individual_ano,2,",","."),
            "total_coletivo_ano"     => number_format($total_coletivo_ano,2,",","."),
            "total_ss_ano"           => number_format($total_ss_ano,2,",","."),
            "total_sindimaco_ano"    => number_format($total_sindimaco_ano,2,",","."),
            "total_sincofarma_ano"   => number_format($total_sincofarma_ano,2,",","."),
            "total_sindipao_ano"     => number_format($total_sindipao_ano,2,",","."),
            "total_valor_ano"        => number_format($total_valor_ano,2,",","."),
            /*********************************************Ano*********************************************************/

            /*********************************************Grafico*********************************************************/
            'total_coletivo_quantidade_vidas_janeiro_grafico' => $total_coletivo_quantidade_vidas_janeiro,
            'total_coletivo_quantidade_vidas_fevereiro_grafico' => $total_coletivo_quantidade_vidas_fevereiro,
            'total_coletivo_quantidade_vidas_marco_grafico' => $total_coletivo_quantidade_vidas_marco,
            'total_coletivo_quantidade_vidas_abril_grafico' => $total_coletivo_quantidade_vidas_abril,
            'total_coletivo_quantidade_vidas_maio_grafico' => $total_coletivo_quantidade_vidas_maio,
            'total_coletivo_quantidade_vidas_junho_grafico' => $total_coletivo_quantidade_vidas_junho,
            'total_coletivo_quantidade_vidas_julho_grafico' => $total_coletivo_quantidade_vidas_julho,
            'total_coletivo_quantidade_vidas_agosto_grafico' => $total_coletivo_quantidade_vidas_agosto,
            'total_coletivo_quantidade_vidas_setembro_grafico' => $total_coletivo_quantidade_vidas_setembro,
            'total_coletivo_quantidade_vidas_outubro_grafico' => $total_coletivo_quantidade_vidas_outubro,
            'total_coletivo_quantidade_vidas_novembro_grafico' => $total_coletivo_quantidade_vidas_novembro,
            'total_coletivo_quantidade_vidas_dezembro_grafico' => $total_coletivo_quantidade_vidas_dezembro,
            'total_individual_quantidade_vidas_janeiro_grafico' => $total_individual_quantidade_vidas_janeiro,
            'total_individual_quantidade_vidas_fevereiro_grafico' => $total_individual_quantidade_vidas_fevereiro,

            'total_individual_quantidade_vidas_marco_grafico' => $total_individual_quantidade_vidas_marco,
            'total_individual_quantidade_vidas_abril_grafico' => $total_individual_quantidade_vidas_abril,
            'total_individual_quantidade_vidas_maio_grafico' => $total_individual_quantidade_vidas_maio,
            'total_individual_quantidade_vidas_junho_grafico' => $total_individual_quantidade_vidas_junho,
            'total_individual_quantidade_vidas_julho_grafico' => $total_individual_quantidade_vidas_julho,
            'total_individual_quantidade_vidas_agosto_grafico' => $total_individual_quantidade_vidas_agosto,
            'total_individual_quantidade_vidas_setembro_grafico' => $total_individual_quantidade_vidas_setembro,
            'total_individual_quantidade_vidas_outubro_grafico' => $total_individual_quantidade_vidas_outubro,
            'total_individual_quantidade_vidas_novembro_grafico' => $total_individual_quantidade_vidas_novembro,
            'total_individual_quantidade_vidas_dezembro_grafico' => $total_individual_quantidade_vidas_dezembro,

            'totalContratoEmpresarialJaneiroGrafico' => $totalContratoEmpresarialJaneiro,
            'totalContratoEmpresarialFevereiroGrafico' => $totalContratoEmpresarialFevereiro,
            'totalContratoEmpresarialMarcoGrafico' => $totalContratoEmpresarialMarco,
            'totalContratoEmpresarialAbrilGrafico' => $totalContratoEmpresarialAbril,
            'totalContratoEmpresarialMaioGrafico' => $totalContratoEmpresarialMaio,
            'totalContratoEmpresarialJunhoGrafico' => $totalContratoEmpresarialJunho,
            'totalContratoEmpresarialJulhoGrafico' => $totalContratoEmpresarialJulho,
            'totalContratoEmpresarialAgostoGrafico' => $totalContratoEmpresarialAgosto,
            'totalContratoEmpresarialSetembroGrafico' => $totalContratoEmpresarialSetembro,
            'totalContratoEmpresarialOutubroGrafico' => $totalContratoEmpresarialOutubro,
            'totalContratoEmpresarialNovembroGrafico' => $totalContratoEmpresarialNovembro,
            'totalContratoEmpresarialDezembroGrafico' => $totalContratoEmpresarialDezembro,
            /*********************************************Grafico*********************************************************/

            /*********************************************Geral*********************************************************/
            'total_vidas_geral_individual' => $total_vidas_geral_individual,
            'total_vidas_geral_coletivo' => $total_vidas_geral_coletivo,
            'total_ss_vidas_geral' => $total_ss_vidas_geral,
            'total_sindimaco_vidas_geral' => $total_sindimaco_vidas_geral,
            'total_sincofarma_vidas_geral' => $total_sincofarma_vidas_geral,
            'total_sindipao_vidas_geral' => $total_sindipao_vidas_geral,
            'imagem' => $imagem,
            'nome' => $nome,
            'total_comissao' => number_format($total_comissao,2,",","."),
            'total_vendas' => number_format($total_vendas,2,",","."),
            'total_vidas' => $total_vidas,
            'total_valor_individual_geral' => number_format($total_valor_individual_geral,2,",","."),
            'total_valor_coletivo_geral' => number_format($total_valor_coletivo_geral,2,",","."),
            'total_valor_ss_geral' => number_format($total_valor_ss_geral,2,",","."),
            'total_valor_sindipao_geral' => number_format($total_valor_sindipao_geral,2,",","."),
            'total_valor_sindimaco_geral' => number_format($total_valor_sindimaco_geral,2,",","."),
            'total_valor_sincofarma_geral' => number_format($total_valor_sincofarma_geral,2,",","."),
            /*********************************************Geral*********************************************************/

            /*********************************************Tabela Comissao*********************************************************/

            'tabela_folha_janeiro_comissao' => number_format($tabela_folha_janeiro_comissao,2,",","."),
            'tabela_folha_fevereiro_comissao' => number_format($tabela_folha_fevereiro_comissao,2,",","."),
            'tabela_folha_marco_comissao' => number_format($tabela_folha_marco_comissao,2,",","."),
            'tabela_folha_abril_comissao' => number_format($tabela_folha_abril_comissao,2,",","."),
            'tabela_folha_maio_comissao' => number_format($tabela_folha_maio_comissao,2,",","."),
            'tabela_folha_junho_comissao' => number_format($tabela_folha_junho_comissao,2,",","."),
            'tabela_folha_julho_comissao' => number_format($tabela_folha_julho_comissao,2,",","."),
            'tabela_folha_agosto_comissao' => number_format($tabela_folha_agosto_comissao,2,",","."),
            'tabela_folha_setembro_comissao' => number_format($tabela_folha_setembro_comissao,2,",","."),
            'tabela_folha_outubro_comissao' => number_format($tabela_folha_outubro_comissao,2,",","."),
            'tabela_folha_novembro_comissao' => number_format($tabela_folha_novembro_comissao,2,",","."),
            'tabela_folha_dezembro_comissao' => number_format($tabela_folha_dezembro_comissao,2,",","."),

            'tabela_folha_janeiro_planos' => number_format($tabela_folha_janeiro_planos,2,",","."),
            'tabela_folha_fevereiro_planos' => number_format($tabela_folha_fevereiro_planos,2,",","."),
            'tabela_folha_marco_planos' => number_format($tabela_folha_marco_planos,2,",","."),
            'tabela_folha_abril_planos' => number_format($tabela_folha_abril_planos,2,",","."),
            'tabela_folha_maio_planos' => number_format($tabela_folha_maio_planos,2,",","."),
            'tabela_folha_junho_planos' => number_format($tabela_folha_junho_planos,2,",","."),
            'tabela_folha_julho_planos' => number_format($tabela_folha_julho_planos,2,",","."),
            'tabela_folha_agosto_planos' => number_format($tabela_folha_agosto_planos,2,",","."),
            'tabela_folha_setembro_planos' => number_format($tabela_folha_setembro_planos,2,",","."),
            'tabela_folha_outubro_planos' => number_format($tabela_folha_outubro_planos,2,",","."),
            'tabela_folha_novembro_planos' => number_format($tabela_folha_novembro_planos,2,",","."),
            'tabela_folha_dezembro_planos' => number_format($tabela_folha_dezembro_planos,2,",","."),
            /*********************************************Tabela Comissao*********************************************************/

        ];
    }

    public function dashboardMesUsuario(Request $request)
    {
        $user_id = $request->user_id;
        $mesAnoSelecionado = $request->mes_ano;

        // Extrai o mês e o ano do valor selecionado
        $mesAnoArray = explode("/", $mesAnoSelecionado);
        $mes = $mesAnoArray[0];
        $ano = $mesAnoArray[1];



        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('quantidade_vidas');



        $total_individual = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("plano_id",1)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('valor_plano');

        $total_coletivo = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("plano_id",3)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('valor_plano');

        $total_ss = ContratoEmpresarial
            ::where('plano_id',5)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//SS

        $total_sindipao = ContratoEmpresarial
            ::where('plano_id',6)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial
            ::where('plano_id',9)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sindimaco

        $total_sincofarma = ContratoEmpresarial
            ::where('plano_id',13)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('valor_plano');//Sincofarma

        $valor_adesao_col_ind = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->whereMonth("contratos.created_at",$mes)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at",$ano)
            ->sum('valor_plano');

        $valor_plano_empresar = ContratoEmpresarial::
        whereMonth("created_at",$mes)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("created_at",$ano)
            ->sum('valor_plano');
        $total_valor = $valor_adesao_col_ind + $valor_plano_empresar;

        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->where("contratos.plano_id", 1)
            ->whereYear("contratos.created_at", $ano)
            ->whereMonth("contratos.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sindipao_quantidade_vidas = ContratoEmpresarial
            ::where("plano_id",6)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial
            ::where("plano_id",9)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial
            ::where("plano_id",13)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->whereMonth("contrato_empresarial.created_at", $mes)
            ->sum('quantidade_vidas');

        $quantidade_vidas_mes = $total_coletivo_quantidade_vidas + $total_individual_quantidade_vidas + $total_super_simples_quantidade_vidas
            + $total_sindipao_quantidade_vidas + $total_sindimaco_quantidade_vidas + $total_sincofarma_quantidade_vidas;

        return [
          "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
          "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
          "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
          "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
          "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
          "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "quantidade_vidas_mes" => $quantidade_vidas_mes,

          "total_individual" => number_format($total_individual,2,",","."),
          "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",","."),
            "total_valor" => number_format($total_valor,2,",",".")

        ];




    }


    public function dashboardSemestreUsuario(Request $request)
    {
        $semestreSelecionado = $request->semestre;
        $semestreArray = explode("/", $semestreSelecionado);
        $semestre = $semestreArray[0];
        $ano = $semestreArray[1];
        $user_id = $request->user_id;

        $startDate = "";
        $endDate = "";

        if ($semestre == 1) {
            // Primeiro semestre (de janeiro a junho)
            $startDate = $ano . "-01-01";
            $endDate = $ano . "-06-30";
        } else {
            // Segundo semestre (de julho a dezembro)
            $startDate = $ano . "-07-01";
            $endDate = $ano . "-12-31";
        }

        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where("contratos.plano_id",1)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereYear("contratos.created_at", $ano)
            ->whereBetween("contratos.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('quantidade_vidas');


        $total_semestre_vidas = $total_coletivo_quantidade_vidas +
            $total_individual_quantidade_vidas +
            $total_super_simples_quantidade_vidas +
            $total_sindipao_quantidade_vidas +
            $total_sindimaco_quantidade_vidas +
            $total_sincofarma_quantidade_vidas;

        $total_individual = Cliente::select("*")
            ->join("contratos","contratos.cliente_id","=","clientes.id")
            ->where("plano_id",1)
            ->whereYear("contratos.created_at",$ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id',$user_id);
            })
            ->whereBetween("contratos.created_at", [$startDate,$endDate])
            ->sum('valor_plano');

        $total_coletivo = Cliente::select("*")
            ->join("contratos","contratos.cliente_id","=","clientes.id")
            ->where("plano_id",3)
            ->whereYear("contratos.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contratos.created_at", [$startDate,$endDate])
            ->sum('valor_plano');

        $total_ss = ContratoEmpresarial::where('plano_id',5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');

        $total_sindipao = ContratoEmpresarial::where('plano_id',6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial::where('plano_id',9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sindimaco

        $total_sincofarma = ContratoEmpresarial::where('plano_id',13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->whereBetween("contrato_empresarial.created_at", [$startDate, $endDate])
            ->sum('valor_plano');//Sincofarma





        return [
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "total_semestre" => $total_semestre_vidas,

            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",",".")
        ];
    }


    public function dashboardAnoUsuario(Request $request)
    {
        $ano = $request->ano;
        $user_id = $request->user_id;


        $total_coletivo_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 3)
            ->whereYear("contratos.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');

        $total_individual = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("plano_id",1)
            ->whereYear("contratos.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_adesao');

        $total_coletivo = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("plano_id",3)
            ->whereYear("contratos.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_adesao');

        $total_ss = ContratoEmpresarial
            ::where('plano_id',5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_plano');//SS

        $total_sindipao = ContratoEmpresarial
            ::where('plano_id',6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_plano');//Sindipão

        $total_sindimaco = ContratoEmpresarial::where('plano_id',9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_plano');//Sindimaco
        $total_sincofarma = ContratoEmpresarial::where('plano_id',13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_plano');//Sincofarma

        $valor_adesao_col_ind = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->whereYear("contratos.created_at",$ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_adesao');

        $valor_plano_empresar = ContratoEmpresarial
            ::whereYear("created_at",$ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_plano');
        $total_valor = $valor_adesao_col_ind + $valor_plano_empresar;






        $total_individual_quantidade_vidas = Cliente::select("*")
            ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
            ->where("contratos.plano_id", 1)
            ->whereYear("contratos.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');






        $total_super_simples_quantidade_vidas = ContratoEmpresarial::where("plano_id",5)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');


        $total_sindipao_quantidade_vidas = ContratoEmpresarial::where("plano_id",6)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');

        $total_sindimaco_quantidade_vidas = ContratoEmpresarial::where("plano_id",9)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');

        $total_sincofarma_quantidade_vidas = ContratoEmpresarial::where("plano_id",13)
            ->whereYear("contrato_empresarial.created_at", $ano)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('quantidade_vidas');

        $quantidade_vidas_mes = $total_coletivo_quantidade_vidas + $total_individual_quantidade_vidas + $total_super_simples_quantidade_vidas
            + $total_sindipao_quantidade_vidas + $total_sindimaco_quantidade_vidas + $total_sincofarma_quantidade_vidas;

        return [
            "total_coletivo_quantidade_vidas" => $total_coletivo_quantidade_vidas,
            "total_individual_quantidade_vidas" => $total_individual_quantidade_vidas,
            "total_super_simples_quantidade_vidas" => $total_super_simples_quantidade_vidas,
            "total_sindipao_quantidade_vidas" => $total_sindipao_quantidade_vidas,
            "total_sindimaco_quantidade_vidas" => $total_sindimaco_quantidade_vidas,
            "total_sincofarma_quantidade_vidas" => $total_sincofarma_quantidade_vidas,
            "quantidade_vidas_ano" => $quantidade_vidas_mes,

            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => number_format($total_coletivo,2,",","."),
            "total_ss" => number_format($total_ss,2,",","."),
            "total_sindipao" => number_format($total_sindipao,2,",","."),
            "total_sindimaco" => number_format($total_sindimaco,2,",","."),
            "total_sincofarma" => number_format($total_sincofarma,2,",","."),
            "total_valor" => number_format($total_valor,2,",",".")
        ];
    }


    public function dashboardTabelaAnoUsuario(Request $request)
    {


        $ano_atual = $request->ano;
        $user_id = $request->user_id;


        $tabela_folha_janeiro_comissao = ValoresCorretoresLancados::whereMonth("data",01)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');

        $tabela_folha_fevereiro_comissao = ValoresCorretoresLancados::whereMonth("data",02)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');




        $tabela_folha_marco_comissao = ValoresCorretoresLancados::whereMonth("data",03)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');




        $tabela_folha_abril_comissao = ValoresCorretoresLancados::whereMonth("data",04)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_maio_comissao = ValoresCorretoresLancados::whereMonth("data",05)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_junho_comissao = ValoresCorretoresLancados::whereMonth("data",06)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_julho_comissao = ValoresCorretoresLancados::whereMonth("data",07)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_agosto_comissao = ValoresCorretoresLancados::whereMonth("data",'08')
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_setembro_comissao = ValoresCorretoresLancados::whereMonth("data",'09')
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_outubro_comissao = ValoresCorretoresLancados::whereMonth("data",10)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_novembro_comissao = ValoresCorretoresLancados::whereMonth("data",11)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');


        $tabela_folha_dezembro_comissao = ValoresCorretoresLancados::whereMonth("data",12)
            ->whereYear("data",$ano_atual)
            ->when($user_id != 0, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->sum('valor_total');






        $tabela_folha_janeiro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",01)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",01)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })

                ->whereYear("created_at",$ano_atual)->sum('valor_plano');


        $tabela_folha_fevereiro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",02)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",02)
                ->whereYear("created_at",$ano_atual)->sum('valor_plano');

        $tabela_folha_marco_planos =
            Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",03)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",03)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)->sum('valor_plano');

        $tabela_folha_abril_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",04)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",04)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');

        $tabela_folha_maio_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",05)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",05)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');

        $tabela_folha_junho_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",06)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",06)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)->sum('valor_plano');

        $tabela_folha_julho_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",07)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",07)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');

        $tabela_folha_agosto_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",'08')
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",'08')
                ->where("user_id",$user_id)->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');

        $tabela_folha_setembro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",'09')
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",'09')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');

        $tabela_folha_outubro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",10)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",10)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano_atual)->sum('valor_plano');

        $tabela_folha_novembro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",11)
                ->whereYear("contratos.created_at",$ano_atual)
                ->where("user_id",$user_id)
                ->sum('valor_plano') +
            ContratoEmpresarial::where("user_id",$user_id)->whereMonth("created_at",11)
                ->whereYear("created_at",$ano_atual)->sum('valor_plano');

        $tabela_folha_dezembro_planos = Contrato::select("*")
                ->join('clientes','clientes.id','=','contratos.cliente_id')
                ->whereMonth("contratos.created_at",12)
                ->whereYear("contratos.created_at",$ano_atual)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->sum('valor_plano') +
            ContratoEmpresarial
                ::whereMonth("created_at",12)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })

                ->whereYear("created_at",$ano_atual)
                ->sum('valor_plano');


        return [




            'tabela_folha_janeiro_comissao' => number_format($tabela_folha_janeiro_comissao,2,",","."),
            'tabela_folha_fevereiro_comissao' => number_format($tabela_folha_fevereiro_comissao,2,",","."),
            'tabela_folha_marco_comissao' => number_format($tabela_folha_marco_comissao,2,",","."),
            'tabela_folha_abril_comissao' => number_format($tabela_folha_abril_comissao,2,",","."),
            'tabela_folha_maio_comissao' => number_format($tabela_folha_maio_comissao,2,",","."),
            'tabela_folha_junho_comissao' => number_format($tabela_folha_junho_comissao,2,",","."),
            'tabela_folha_julho_comissao' => number_format($tabela_folha_julho_comissao,2,",","."),
            'tabela_folha_agosto_comissao' => number_format($tabela_folha_agosto_comissao,2,",","."),
            'tabela_folha_setembro_comissao' => number_format($tabela_folha_setembro_comissao,2,",","."),
            'tabela_folha_outubro_comissao' => number_format($tabela_folha_outubro_comissao,2,",","."),
            'tabela_folha_novembro_comissao' => number_format($tabela_folha_novembro_comissao,2,",","."),
            'tabela_folha_dezembro_comissao' => number_format($tabela_folha_dezembro_comissao,2,",","."),

            'tabela_folha_janeiro_planos' => number_format($tabela_folha_janeiro_planos,2,",","."),
            'tabela_folha_fevereiro_planos' => number_format($tabela_folha_fevereiro_planos,2,",","."),
            'tabela_folha_marco_planos' => number_format($tabela_folha_marco_planos,2,",","."),
            'tabela_folha_abril_planos' => number_format($tabela_folha_abril_planos,2,",","."),
            'tabela_folha_maio_planos' => number_format($tabela_folha_maio_planos,2,",","."),
            'tabela_folha_junho_planos' => number_format($tabela_folha_junho_planos,2,",","."),
            'tabela_folha_julho_planos' => number_format($tabela_folha_julho_planos,2,",","."),
            'tabela_folha_agosto_planos' => number_format($tabela_folha_agosto_planos,2,",","."),
            'tabela_folha_setembro_planos' => number_format($tabela_folha_setembro_planos,2,",","."),
            'tabela_folha_outubro_planos' => number_format($tabela_folha_outubro_planos,2,",","."),
            'tabela_folha_novembro_planos' => number_format($tabela_folha_novembro_planos,2,",","."),
            'tabela_folha_dezembro_planos' => number_format($tabela_folha_dezembro_planos,2,",",".")
            /*********************************************Tabela Comissao*********************************************************/

        ];

    }

    public function dashboardVendedorGraficoAno(Request $request)
    {
        $ano = $request->ano;
        $user_id = $request->user_id;
        if($ano != null) {

            $total_coletivo_quantidade_vidas_janeiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",01)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_fevereiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",02)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_marco = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",03)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_abril = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",04)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_maio = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",05)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_junho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",06)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_julho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'07')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_agosto = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'08')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_setembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",'09')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_outubro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",10)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_novembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",11)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_coletivo_quantidade_vidas_dezembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",3)
                ->whereMonth("contratos.created_at",12)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_janeiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",01)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_fevereiro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",02)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_marco = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",03)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');


            $total_individual_quantidade_vidas_abril = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",04)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_maio = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",05)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_junho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",06)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_julho = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",07)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_agosto = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'08')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_setembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'09')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_outubro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'10')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_novembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'11')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $total_individual_quantidade_vidas_dezembro = Cliente::select("*")
                ->join('contratos','contratos.cliente_id','=','clientes.id')
                ->where("contratos.plano_id",1)
                ->whereMonth("contratos.created_at",'12')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("contratos.created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJaneiro = ContratoEmpresarial
                ::whereMonth("created_at",01)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialFevereiro = ContratoEmpresarial
                ::whereMonth("created_at",02)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialMarco = ContratoEmpresarial
                ::whereMonth("created_at",03)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialAbril = ContratoEmpresarial
                ::whereMonth("created_at",04)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialMaio = ContratoEmpresarial
                ::whereMonth("created_at",05)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJunho = ContratoEmpresarial
                ::whereMonth("created_at",06)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialJulho = ContratoEmpresarial
                ::whereMonth("created_at",07)
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialAgosto = ContratoEmpresarial
                ::whereMonth("created_at",'08')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialSetembro = ContratoEmpresarial
                ::whereMonth("created_at",'09')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialOutubro = ContratoEmpresarial
                ::whereMonth("created_at",'10')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialNovembro = ContratoEmpresarial
                ::whereMonth("created_at",'11')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');

            $totalContratoEmpresarialDezembro = ContratoEmpresarial
                ::whereMonth("created_at",'12')
                ->when($user_id != 0, function ($query) use ($user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->whereYear("created_at",$ano)
                ->sum('quantidade_vidas');


            return [

                "total_coletivo_quantidade_vidas_janeiro" => $total_coletivo_quantidade_vidas_janeiro,
                "total_coletivo_quantidade_vidas_fevereiro" => $total_coletivo_quantidade_vidas_fevereiro,
                "total_coletivo_quantidade_vidas_marco" => $total_coletivo_quantidade_vidas_marco,
                "total_coletivo_quantidade_vidas_abril" => $total_coletivo_quantidade_vidas_abril,
                "total_coletivo_quantidade_vidas_maio" => $total_coletivo_quantidade_vidas_maio,
                "total_coletivo_quantidade_vidas_junho" => $total_coletivo_quantidade_vidas_junho,
                "total_coletivo_quantidade_vidas_julho" => $total_coletivo_quantidade_vidas_julho,
                "total_coletivo_quantidade_vidas_agosto" => $total_coletivo_quantidade_vidas_agosto,
                "total_coletivo_quantidade_vidas_setembro" => $total_coletivo_quantidade_vidas_setembro,
                "total_coletivo_quantidade_vidas_outubro" => $total_coletivo_quantidade_vidas_outubro,
                "total_coletivo_quantidade_vidas_novembro" => $total_coletivo_quantidade_vidas_novembro,
                "total_coletivo_quantidade_vidas_dezembro" => $total_coletivo_quantidade_vidas_dezembro,

                "total_individual_quantidade_vidas_janeiro" => $total_individual_quantidade_vidas_janeiro,
                "total_individual_quantidade_vidas_fevereiro" => $total_individual_quantidade_vidas_fevereiro,
                "total_individual_quantidade_vidas_marco" => $total_individual_quantidade_vidas_marco,
                "total_individual_quantidade_vidas_abril" => $total_individual_quantidade_vidas_abril,
                "total_individual_quantidade_vidas_maio" => $total_individual_quantidade_vidas_maio,
                "total_individual_quantidade_vidas_junho" => $total_individual_quantidade_vidas_junho,
                "total_individual_quantidade_vidas_julho" => $total_individual_quantidade_vidas_julho,
                "total_individual_quantidade_vidas_agosto" => $total_individual_quantidade_vidas_agosto,
                "total_individual_quantidade_vidas_setembro" => $total_individual_quantidade_vidas_setembro,
                "total_individual_quantidade_vidas_outubro" => $total_individual_quantidade_vidas_outubro,
                "total_individual_quantidade_vidas_novembro" => $total_individual_quantidade_vidas_novembro,
                "total_individual_quantidade_vidas_dezembro" => $total_individual_quantidade_vidas_dezembro,

                "totalContratoEmpresarialJaneiro" => $totalContratoEmpresarialJaneiro,
                "totalContratoEmpresarialFevereiro" => $totalContratoEmpresarialFevereiro,
                "totalContratoEmpresarialMarco" => $totalContratoEmpresarialMarco,
                "totalContratoEmpresarialAbril" => $totalContratoEmpresarialAbril,
                "totalContratoEmpresarialMaio" => $totalContratoEmpresarialMaio,
                "totalContratoEmpresarialJunho" => $totalContratoEmpresarialJunho,
                "totalContratoEmpresarialJulho" => $totalContratoEmpresarialJulho,
                "totalContratoEmpresarialAgosto" => $totalContratoEmpresarialAgosto,
                "totalContratoEmpresarialSetembro" => $totalContratoEmpresarialSetembro,
                "totalContratoEmpresarialOutubro" => $totalContratoEmpresarialOutubro,
                "totalContratoEmpresarialNovembro" => $totalContratoEmpresarialNovembro,
                "totalContratoEmpresarialDezembro" => $totalContratoEmpresarialDezembro


            ];









        }

    }








}
