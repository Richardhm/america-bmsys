<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Contrato,
    Cliente,
    FolhaMes,
    FolhaPagamento,
    TabelaOrigens,
    Administradoras,
    Planos,
    Acomodacao,
    CotacaoFaixaEtaria,
    User,
    PlanoEmpresarial,
    ContratoEmpresarial,
    Comissoes,
    ComissoesCorretoresLancadas,
    ComissoesCorretoraConfiguracoes,
    ComissoesCorretoraLancadas,
    ComissoesCorretoresConfiguracoes,
    Dependentes,
    Cancelado,
    ComissoesCorretoresDefault,
    MotivoCancelados,
    Corretora,
    Premiacoes,
    PremiacoesCorretoraLancadas,
    PremiacoesCorretoresLancadas,
    PremiacoesCorretoraConfiguracoes,
    PremiacoesCorretoresConfiguracoes,
    ValoresCorretoresLancados};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Cache;


class GerenteController extends Controller
{

    public function __construct()
    {
        return $this->middleware(["can:gerente"]);
    }

    public function tabelaVazia()
    {
        return [];
    }

    public function totalizarMes(Request $request)
    {
        $mes = $request->mes;
        $ano = $request->ano;
        $dados = DB::table('valores_corretores_lancados')
            ->selectRaw("SUM(valor_comissao) as total_comissao")
            ->selectRaw("SUM(valor_salario) as total_salario")
            ->selectRaw("SUM(valor_premiacao) as valor_premiacao")
            ->selectRaw("SUM(valor_desconto) as valor_desconto")
            ->selectRaw("SUM(valor_estorno) as valor_estorno")
            ->selectRaw("SUM(valor_total) as total_mes")
            ->whereMonth("data", $mes)
            ->whereYear("data",$ano)
            ->first();

        $dados->total_comissao = number_format($dados->total_comissao, 2, ',', '.');
        $dados->total_salario = number_format($dados->total_salario, 2, ',', '.');
        $dados->valor_premiacao = number_format($dados->valor_premiacao, 2, ',', '.');
        $dados->valor_desconto = number_format($dados->valor_desconto, 2, ',', '.');
        $dados->valor_estorno = number_format($dados->valor_estorno, 2, ',', '.');
        $dados->total_mes = number_format($dados->total_mes, 2, ',', '.');


        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
            })->count();

        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                    SUM(valor)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} and year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} and year(data_baixa_finalizado) = {$ano}
            ) AS plano3;
        ")[0]->total_individual_valor;

        return [
            "dados" => $dados,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_individual" => number_format($total_individual,2,",","."),
        ];

    }

    public function montarTabelaMesModal(Request $request)
    {
        $mes = $request->mes;
        $users = DB::table('valores_corretores_lancados')
                ->selectRaw("(select name from users where users.id = valores_corretores_lancados.user_id) as user,valor_comissao,valor_salario,valor_premiacao")
                ->selectRaw("valor_total as total")
                ->whereMonth("data",$mes)
                ->get();
        return view('admin.pages.gerente.table-modal',[
            "dados" => $users
        ]);
    }



    public function index()
    {






        $folha_aberto = FolhaMes::where("status", 0);
        $total_comissao = "0,00";
        $total_salario = "0,00";
        $total_premiacao = "0,00";
        $total_desconto = "0,00";
        $total_mes = "0,00";
        $total_estorno = "0,00";
        $total_adiantamento = "0,00";

        if ($folha_aberto->count() == 1) {
            $mes_aberto = $folha_aberto->first()->mes;
            $mes = date('m', strtotime($mes_aberto));
            $ano = date('Y',strtotime($mes_aberto));
            

            $dados_totais = DB::table('valores_corretores_lancados')
                ->selectRaw("SUM(valor_comissao) as total_comissao")
                ->selectRaw("SUM(valor_salario) as total_salario")
                ->selectRaw("SUM(valor_premiacao) as valor_premiacao")
                ->selectRaw("SUM(valor_desconto) as valor_desconto")
                ->selectRaw("SUM(valor_estorno) as valor_estorno")
                ->selectRaw("SUM(valor_adiantamento) as valor_adiantamento")
                ->selectRaw("SUM(valor_total) as total_mes")
                ->whereMonth("data", $mes)
                ->whereYear("data",$ano)
                ->first();

            $total_comissao = number_format($dados_totais->total_comissao, 2, ',', '.');
            $total_salario = number_format($dados_totais->total_salario, 2, ',', '.');
            $total_premiacao = number_format($dados_totais->valor_premiacao, 2, ',', '.');
            $total_desconto = number_format($dados_totais->valor_desconto, 2, ',', '.');
            $total_estorno = number_format($dados_totais->valor_estorno, 2, ',', '.');
            $total_adiantamento = number_format($dados_totais->valor_adiantamento, 2, ',', '.');
            $total_mes = number_format($dados_totais->total_mes, 2, ',', '.');

            $total_individual_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro", 1)
                ->where("status_apto_pagar", 1)
                //->where("finalizado",1)
                ->whereMonth("data_baixa_finalizado", $mes)
                ->whereYear("data_baixa_finalizado",$ano)
                ->whereHas('comissao', function ($query) {
                    $query->where("plano_id", 1);
                })->count();

            $total_individual = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
                SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
                ) AS plano1,
                (
                SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
                ) AS plano3;
            ")[0]->total_individual_valor;


            $folhaMesAberto = FolhaMes::where("status", 0)->first();
            $status_disabled = false;
            if ($folhaMesAberto == null) {
                $mes = 0;
				$ano = 0;
                $status_disabled = true;
            } else {
                $mes = date('m', strtotime($folhaMesAberto->mes));
                $ano = date('Y', strtotime($folhaMesAberto->mes));
            }

            $users_apto_apagar = User::whereIn('id', function ($query) use ($mes,$ano) {
                $query->select('user_id')
                    ->from('valores_corretores_lancados')
                    ->whereMonth('data', $mes)
                    ->whereYear('data',$ano);
            })
                ->selectRaw("id as user_id")
                ->selectRaw("name as user")
                ->selectRaw(
                    "
                    (select valor_total from valores_corretores_lancados where valores_corretores_lancados.user_id = users.id
                    and month(data) = ${mes}
                    and year(data) = ${ano}
                    ) as total

                    ")
                ->orderBy('name')->get();


            $usuarios = DB::table('users')
                ->where('ativo', 1)
                ->whereNotIn('id', function ($query) {
                    $query->select('user_id')
                        ->from('valores_corretores_lancados');
                })->get();


            $quantidade_geral = Contrato::count();
            $total_valor_geral = DB::select("select (select sum(valor_plano) from contratos) + 0 as total_soma_formatado")[0]->total_soma_formatado;
            $quantidade_vidas_geral = DB::select("select (select sum(quantidade_vidas) from clientes) + 0 as total_vidas")[0]->total_vidas;

            $total_quantidade_a_receber = DB::select("SELECT count(*) as total FROM comissoes_corretores_lancadas WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 0 AND comissoes_corretores_lancadas.valor != 0")[0]->total;

            $total_valor_a_receber = DB::select("SELECT sum(if(comissoes_corretores_lancadas.valor_pago != null, valor_pago, valor)) as total FROM comissoes_corretores_lancadas WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 0 AND comissoes_corretores_lancadas.valor != 0")[0]->total;

            $quantidade_vidas_a_receber = Cliente::whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 0);
                $query->where("valor", "!=", 0);
            })->selectRaw("sum(quantidade_vidas) as total_quantidade_vidas_recebidas")->first()->total_quantidade_vidas_recebidas;


            /*********************************************************************Fim Card A Receber******************************************************/
            $total_quantidade_recebidos = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 1);

            })->count();


            $total_valor_recebidos = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 1);
                $query->where("valor", "!=", 0);
            })->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

            $quantidade_vidas_recebidas = Cliente
                ::whereHas('contrato', function ($query) {
                    $query->where('plano_id', 1);
                })
                ->whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                    $query->where("status_financeiro", 1);
                    $query->where("status_gerente", 1);
                    $query->where("valor", "!=", 0);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                ->first()
                ->total_quantidade_vidas_recebidas;


            $qtd_atrasado = Contrato
                ::whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->count();

            $qtd_atrasado_valor = Contrato
                ::whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

            $qtd_atrasado_quantidade_vidas = Cliente
                ::whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('contrato', function ($query) {
                    $query->whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10]);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

            $qtd_finalizado = Contrato::where("financeiro_id", 11)
                ->count();

            $quantidade_valor_finalizado = Contrato::where("financeiro_id", 11)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

            $qtd_finalizado_quantidade_vidas = Cliente::whereHas('contrato', function ($query) {
                $query->where("financeiro_id", 11);

            })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

            $qtd_cancelado = Contrato::where("financeiro_id", 12)->count();

            $quantidade_valor_cancelado = Contrato::where("financeiro_id", 12)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

            $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato', function ($query) {
                $query->where("financeiro_id", 12);
            })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

            //FIM Geral

            //Individual

            $quantidade_individual_geral = Contrato::where("plano_id", 1)->count();
            $total_valor_geral_individual = Contrato::where("plano_id", 1)->selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
            $quantidade_vidas_geral_individual = Cliente::whereHas('contrato', function ($query) {
                $query->where("plano_id", 1);
            })->selectRaw("if(SUM(quantidade_vidas)>=1,SUM(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;

            $total_quantidade_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 1);
                $query->where("valor", "!=", 0);
            })
                ->where("plano_id", 1)
                ->count();

            $total_valor_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 1);
                $query->where("valor", "!=", 0);
            })
                ->where("plano_id", 1)
                ->selectRaw("if(sum(valor_plano)>0,sum(valor_plano),0) as total_valor_plano")
                ->first()
                ->total_valor_plano;

            $quantidade_vidas_recebidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 1);
                $query->where("valor", "!=", 0);
            })
                ->whereHas('contrato', function ($query) {
                    $query->where("plano_id", 1);
                })
                ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                ->first()
                ->total_quantidade_vidas_recebidas;

            $total_quantidade_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 0);
                $query->where("valor", "!=", 0);
            })
                ->where("plano_id", 1)
                ->count();

            $total_valor_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 0);
                $query->where("valor", "!=", 0);
            })
                ->where("plano_id", 1)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

            $quantidade_vidas_a_receber_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 1);
                $query->where("status_gerente", 0);
                $query->where("valor", "!=", 0);
            })
                ->whereHas('contrato', function ($query) {
                    $query->where("plano_id", 1);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                ->first()
                ->total_quantidade_vidas_recebidas;


            $qtd_atrasado_individual = Contrato
                ::whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->where("plano_id", 1)
                ->count();

            $qtd_atrasado_valor_individual = Contrato
                ::whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10])
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes', function ($query) {
                    $query->whereRaw('cateirinha IS NOT NULL');
                })
                ->where("plano_id", 1)
                ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

            $qtd_atrasado_quantidade_vidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas', function ($query) {
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
                ->whereHas('contrato', function ($query) {
                    $query->where("plano_id", 1);
                    $query->whereIn("financeiro_id", [3, 4, 5, 6, 7, 8, 9, 10]);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

            $qtd_finalizado_individual = Contrato::where("financeiro_id", 11)->where('plano_id', 1)->count();

            $quantidade_valor_finalizado_individual = Contrato::where("financeiro_id", 11)->where('plano_id', 1)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

            $qtd_finalizado_quantidade_vidas_individual = Cliente::whereHas('contrato', function ($query) {
                $query->where("financeiro_id", 11);
                $query->where("plano_id", 1);
            })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

            $qtd_cancelado_individual = Contrato::where("financeiro_id", 12)
                ->where('plano_id', 1)
                ->count();

            $quantidade_valor_cancelado_individual = Contrato::where("financeiro_id", 12)->where('plano_id', 1)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

            $qtd_cancelado_quantidade_vidas_individual = Cliente::whereHas('contrato', function ($query) {
                $query->where("financeiro_id", 12);
                $query->where("plano_id", 1);
            })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

            $users = DB::select("
            SELECT users.id AS id, users.name AS name
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN users ON users.id = comissoes.user_id
            WHERE (status_financeiro = 1 or status_gerente = 1) and finalizado != 1 and valor != 0 and users.id NOT IN
			(SELECT user_id FROM valores_corretores_lancados WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name
        ");


            $quat_comissao_a_receber = ComissoesCorretoraLancadas::where("status_financeiro", 1)->where("status_gerente", 0)->count();
            $quat_comissao_recebido = ComissoesCorretoraLancadas::where("status_financeiro", 1)->where("status_gerente", 1)->count();

            $valor_quat_comissao_a_receber = ComissoesCorretoraLancadas
                ::selectRaw("sum(valor) as total")
                ->where("status_financeiro", 1)
                ->where("status_gerente", 0)->first()->total;

            $valor_quat_comissao_recebido = ComissoesCorretoresLancadas
                ::selectRaw("sum(valor) as total")
                ->where("status_financeiro", 1)
                ->where("status_gerente", 1)->first()->total;

            //$datas_select = DB::select("SELECT data_baixa_gerente FROM comissoes_corretora_lancadas WHERE status_financeiro = 1 AND status_gerente = 1 GROUP BY MONTH(data_baixa_gerente)");

            $datas_select = DB::select("SELECT data_baixa_gerente FROM comissoes_corretora_lancadas WHERE status_financeiro = 1 AND status_gerente = 1 GROUP BY MONTH(data_baixa_gerente)");
            $total_mes_comissoes = DB::select(
                "SELECT SUM(valor) AS total FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1 AND MONTH(DATA) = MONTH(NOW())"
            );

            $administradoras_mes = DB::select(
                "SELECT
            SUM(valor) AS total,
            (SELECT nome FROM administradoras WHERE id = comissoes.administradora_id) AS administradora
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 1
            AND MONTH(comissoes_corretores_lancadas.data) = MONTH(NOW())
            GROUP BY comissoes.administradora_id"

            );

            $administradoras = Administradoras::orderBy('id', 'desc')->get();

            return view('admin.pages.gerente.index', [

                "status_disabled" => $status_disabled,
                "quat_comissao_a_receber" => $quat_comissao_a_receber,
                "quat_comissao_recebido" => $quat_comissao_recebido,
                "valor_quat_comissao_a_receber" => $valor_quat_comissao_a_receber,
                "valor_quat_comissao_recebido" => $valor_quat_comissao_recebido,
                "datas_select" => $datas_select,
                "total_mes_comissao" => $total_mes_comissoes[0]->total,
                "administradoras_mes" => $administradoras_mes,
                "administradoras" => $administradoras,
                "users" => $users,
                "users_apto_apagar" => $users_apto_apagar,
                "mes" => $mes,
                "ano" => $ano,
                "total" => $total_mes,

                "quantidade_geral" => $quantidade_geral,
                "total_valor_geral" => $total_valor_geral,
                "quantidade_vidas_geral" => $quantidade_vidas_geral,

                "total_quantidade_recebidos" => $total_quantidade_recebidos,
                "total_valor_recebidos" => $total_valor_recebidos,
                "quantidade_vidas_recebidas" => $quantidade_vidas_recebidas,

                "total_quantidade_a_receber" => $total_quantidade_a_receber,
                "total_valor_a_receber" => $total_valor_a_receber,
                "quantidade_vidas_a_receber" => $quantidade_vidas_a_receber,

                "qtd_atrasado" => $qtd_atrasado,
                "qtd_atrasado_valor" => $qtd_atrasado_valor,
                "qtd_atrasado_quantidade_vidas" => $qtd_atrasado_quantidade_vidas,

                "qtd_finalizado" => $qtd_finalizado,
                "quantidade_valor_finalizado" => $quantidade_valor_finalizado,
                "qtd_finalizado_quantidade_vidas" => $qtd_finalizado_quantidade_vidas,

                "qtd_cancelado" => $qtd_cancelado,
                "quantidade_valor_cancelado" => $quantidade_valor_cancelado,
                "qtd_cancelado_quantidade_vidas" => $qtd_cancelado_quantidade_vidas,


                'total_individual_quantidade' => $total_individual_quantidade,


                'total_individual' => number_format($total_individual, 2, ",", "."),


                'total_estorno' => $total_estorno,

                'total_comissao' => number_format($total_individual, 2, ",", "."),
                'total_salario' => $total_salario,
                'total_premiacao' => $total_premiacao,
                'total_desconto' => $total_desconto,
                'total_adiantamento' => $total_adiantamento,
                'total_mes' => $total_mes,


                /************************* Individual *******************************/

                "quantidade_vidas_geral_individual" => $quantidade_vidas_geral_individual,
                "total_valor_geral_individual" => $total_valor_geral_individual,


                "quantidade_individual_geral" => $quantidade_individual_geral,
                "total_valor_geral_individual" => $total_valor_geral_individual,
                "total_quantidade_recebidos_individual" => $total_quantidade_recebidos_individual,
                "total_valor_recebidos_individual" => $total_valor_recebidos_individual,
                "quantidade_vidas_recebidas_individual" => $quantidade_vidas_recebidas_individual,


                "total_quantidade_a_receber_individual" => $total_quantidade_a_receber_individual,
                "total_valor_a_receber_individual" => $total_valor_a_receber_individual,
                "quantidade_vidas_a_receber_individual" => $quantidade_vidas_a_receber_individual,
                "qtd_atrasado_individual" => $qtd_atrasado_individual,
                "qtd_atrasado_valor_individual" => $qtd_atrasado_valor_individual,
                "qtd_atrasado_quantidade_vidas_individual" => $qtd_atrasado_quantidade_vidas_individual,
                "qtd_cancelado_individual" => $qtd_cancelado_individual,
                "quantidade_valor_cancelado_individual" => $quantidade_valor_cancelado_individual,
                "qtd_cancelado_quantidade_vidas_individual" => $qtd_cancelado_quantidade_vidas_individual,
                "qtd_finalizado_individual" => $qtd_finalizado_individual,
                "quantidade_valor_finalizado_individual" => $quantidade_valor_finalizado_individual,
                "qtd_finalizado_quantidade_vidas_individual" => $qtd_finalizado_quantidade_vidas_individual,


            ]);
        } else {
            return view('admin.pages.gerente.index', [

                "status_disabled" => true,
                "quat_comissao_a_receber" => 0,
                "quat_comissao_recebido" => 0,
                "valor_quat_comissao_a_receber" => 0,
                "valor_quat_comissao_recebido" => 0,
                "datas_select" => [],
                "total_mes_comissao" => 0,
                "administradoras_mes" => 0,
                "administradoras" => "",
                "users" => User::all(),
                "users_apto_apagar" => [],
                "mes" => "",
                "ano" => "",

                "quantidade_geral" => 0,
                "total_valor_geral" => 0,
                "quantidade_vidas_geral" => 0,

                "total_quantidade_recebidos" => 0,
                "total_valor_recebidos" => 0,
                "quantidade_vidas_recebidas" => 0,

                "total_quantidade_a_receber" => 0,
                "total_valor_a_receber" => 0,
                "quantidade_vidas_a_receber" => 0,

                "qtd_atrasado" => 0,
                "qtd_atrasado_valor" => 0,
                "qtd_atrasado_quantidade_vidas" => 0,

                "qtd_finalizado" => 0,
                "quantidade_valor_finalizado" => 0,
                "qtd_finalizado_quantidade_vidas" => 0,

                "qtd_cancelado" => 0,
                "quantidade_valor_cancelado" => 0,
                "qtd_cancelado_quantidade_vidas" => 0,


                'total_individual_quantidade' => 0,


                'total_individual' => 0,


                'total_estorno' => 0,

                'total_comissao' => 0,
                'total_salario' => 0,
                'total_premiacao' => 0,
                'total_desconto' => 0,
                'total_mes' => 0,


                /************************* Individual *******************************/

                "quantidade_vidas_geral_individual" => 0,
                "total_valor_geral_individual" => 0,


                "quantidade_individual_geral" => 0,
                "total_valor_geral_individual" => 0,
                "total_quantidade_recebidos_individual" => 0,
                "total_valor_recebidos_individual" => 0,
                "quantidade_vidas_recebidas_individual" => 0,


                "total_quantidade_a_receber_individual" => 0,
                "total_valor_a_receber_individual" => 0,
                "quantidade_vidas_a_receber_individual" => 0,
                "qtd_atrasado_individual" => 0,
                "qtd_atrasado_valor_individual" => 0,
                "qtd_atrasado_quantidade_vidas_individual" => 0,
                "qtd_cancelado_individual" => 0,
                "quantidade_valor_cancelado_individual" => 0,
                "qtd_cancelado_quantidade_vidas_individual" => 0,
                "qtd_finalizado_individual" => 0,
                "quantidade_valor_finalizado_individual" => 0,
                "qtd_finalizado_quantidade_vidas_individual" => 0,


            ]);
        }





    }

    public function listarGerenteCadastrados(Request $request)
    {
        if($request->ajax()) {
            $cadastrados = DB::select("
                select
                    case when empresarial = 1 then
                        (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                    else
                        (select nome from clientes where id = ((select cliente_id from contratos where contratos.id = comissoes.contrato_id)))
                    end as cliente,
                    (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
                    (select name from users where users.id = comissoes.user_id) as corretor,
                    (select nome from planos where planos.id = comissoes.plano_id) as plano,
                    case when empresarial = 1 then
                       contrato_empresarial_id
                    else
                       contrato_id
                    end as contrato_id,
                    (comissoes.plano_id) as plano_id
                from comissoes
            ");
            return $cadastrados;
        }
    }

    public function mudarSalario(Request $request)
    {

        $param = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes);
        if($param->count() == 1) {
            ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes)
                ->update([
                    "valor_comissao" => $request->comissao,
                    "valor_salario" => $request->salario,
                    "valor_premiacao" => $request->premiacao,
                    "valor_estorno" => $request->estorno,
                    "valor_desconto" => $request->desconto,
                    "valor_adiantamento" => $request->adiantamento,
                    "valor_total" => $request->total
                ]);
        } else {
            $ano = date('Y');
            $co = new ValoresCorretoresLancados();
            $co->user_id = $request->user_id;
            $co->data = date($ano."-".$request->mes."-01");
            $co->valor_comissao = $request->comissao;
            $co->valor_salario = $request->salario;
            $co->valor_premiacao = $request->premiacao;
            $co->valor_total = $request->total;
            $co->valor_adiantamento = $request->adiantamento;
            $co->valor_desconto = $request->desconto;
            $co->valor_estorno = $request->estorno;
            $co->save();
        }




    }

    public function mudarPremiacao(Request $request)
    {
        $param = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes);
        if($param->count() == 1) {
            ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes)
                ->update([
                    "valor_comissao" => $request->comissao,
                    "valor_salario" => $request->salario,
                    "valor_premiacao" => $request->premiacao,
                    "valor_estorno" => $request->estorno,
                    "valor_desconto" => $request->desconto,
                    "valor_total" => $request->total
                ]);
        } else {
            $ano = date('Y');
            $co = new ValoresCorretoresLancados();
            $co->user_id = $request->user_id;
            $co->data = date($ano."-".$request->mes."-01");
            $co->valor_comissao = $request->comissao;
            $co->valor_salario = $request->salario;
            $co->valor_premiacao = $request->premiacao;
            $co->valor_total = $request->total;
            $co->valor_desconto = $request->desconto;
            $co->valor_estorno = $request->estorno;
            $co->save();
        }
    }




    public function contratoEstorno(Request $request)
    {

        $ano = date("Y");
        $cc = ComissoesCorretoresLancadas::where("id",$request->id_parcela)->first();
        $cc->estorno = 1;
        $cc->data_baixa_estorno = date($ano."-".$request->mes."-01");
        $cc->save();

        $va = ValoresCorretoresLancados::where("user_id",$request->user_id)->whereMonth("data",$request->mes);
        if($va->count() == 1) {
            ///return $request->all();
            $alt = $va->first();


            $alt->valor_estorno = $request->valor;
            $alt->valor_total = $request->total;

            $alt->save();
        } else {
            $ca = new ValoresCorretoresLancados();
            $ca->valor_comissao = $request->total_comissao;
            $ca->user_id = $request->user_id;
            $ca->valor_total = $request->total;
            $ca->valor_estorno = $request->valor;
            $ca->data = date($ano."-".$request->mes."-01");
            $ca->save();
        }
    }








    public function cadastrarHistoricoFolhaMes(Request $request)
    {

        $date = \DateTime::createFromFormat('Y-m-d', $request->data);




        $formattedDate = $date->format('Y-m-d');

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));




        $users = DB::table('valores_corretores_lancados')
                ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
                ->selectRaw("valor_total AS total")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->groupBy("user_id")
                ->get();


        $valores = DB::table('valores_corretores_lancados')
                ->selectRaw("FORMAT(SUM(valor_comissao),2) AS comissao")
                ->selectRaw("FORMAT(SUM(valor_salario),2) AS salario")
                ->selectRaw("FORMAT(SUM(valor_premiacao),2) AS premiacao")
                ->selectRaw("FORMAT(SUM(valor_comissao+valor_salario+valor_premiacao),2) AS total")
            ->selectRaw("LPAD(MONTH(data), 2, '0') AS mes")
            ->whereRaw("MONTH(data) = ${mes}")
            ->whereYear("data",$ano)
            ->first();


        $users_select = DB::table('valores_corretores_lancados')
        ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS name,user_id as id")
        ->whereMonth("data",$mes)
        ->whereYear("data",$ano)
        ->groupBy("user_id")
        ->get();


        $dados = DB::table('valores_corretores_lancados')
            ->selectRaw("FORMAT(sum(valor_comissao),2) as total_comissao")
            ->selectRaw("FORMAT(sum(valor_salario),2) as total_salario")
            ->selectRaw("FORMAT(sum(valor_premiacao),2) as valor_premiacao")
            ->selectRaw("FORMAT(sum(valor_desconto),2) as valor_desconto")
            ->selectRaw("FORMAT(sum(valor_total),2) as total_mes")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->first();


        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data",$ano)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
            })->count();








        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                    SUM(valor)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano1,
            (
            SELECT
                SUM(valor)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano3;
        ")[0]->total_individual_valor;

        $total_estorno = ValoresCorretoresLancados::whereMonth('data', '=', $mes)
            ->whereYear('data', '=', $ano)
            ->selectRaw('IF(SUM(valor_estorno) > 0, SUM(valor_estorno), 0) as resultado')
            ->get()[0]->resultado;




        return [
            "view" => view('admin.pages.gerente.list-users-pdf-historico',[
                "users" => $users
            ])->render(),
            "dados" => $dados,
            "users" => $users_select,
            "valores" => $valores,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_individual" => $total_individual,
            "total_estorno" => $total_estorno
        ];


    }





    public function cadastrarFolhaMes(Request $request)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $request->data);
        $formattedDate = $date->format('Y-m-d');

        $mes = date("m",strtotime($formattedDate));
        $ano = date("Y",strtotime($formattedDate));

        $folha = FolhaMes
            ::whereMonth("mes",$mes)
            ->whereYear("mes",$ano)
            ->count();

        if($folha == 0) {
            $folha = new FolhaMes();
            $folha->mes = $formattedDate;
            $folha->save();
            $users_select = DB::select("
                SELECT users.id AS id, users.name AS name
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN users ON users.id = comissoes.user_id
                WHERE (status_financeiro = 1 or status_gerente = 1) and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancados WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
                GROUP BY users.id, users.name
                ORDER BY users.name
            ");
            return [
                "resposta" => "cadastrado",
                "users_select" => $users_select
            ];
        } else {

            $users = DB::table('valores_corretores_lancados')
                ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
                ->selectRaw("valor_total AS total")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->groupBy("user_id")
                ->get();

            $valores = DB::table('valores_corretores_lancados')
                 ->selectRaw("FORMAT(SUM(valor_comissao),2) AS comissao")
                 ->selectRaw("FORMAT(SUM(valor_salario),2) AS salario")
                 ->selectRaw("FORMAT(SUM(valor_premiacao),2) AS premiacao")
                 ->selectRaw("FORMAT(SUM(valor_comissao+valor_salario+valor_premiacao),2) AS total")
                ->selectRaw("LPAD(MONTH(data), 2, '0') AS mes")
                ->whereRaw("MONTH(data) = ${mes}")
                ->whereRaw("MONTH(data) = ${ano}")
                ->first();

            $users_select = DB::table('valores_corretores_lancados')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS name,user_id as id")
            ->whereMonth("data",$mes)
            ->whereYear("data",$ano)
            ->groupBy("user_id")
            ->get();


            $dados = DB::table('valores_corretores_lancados')
                ->selectRaw("FORMAT(sum(valor_comissao),2) as total_comissao")
                ->selectRaw("FORMAT(sum(valor_salario),2) as total_salario")
                ->selectRaw("FORMAT(sum(valor_premiacao),2) as valor_premiacao")
                ->selectRaw("FORMAT(sum(valor_desconto),2) as valor_desconto")
                ->selectRaw("FORMAT(sum(valor_total),2) as total_mes")
                ->whereMonth("data",$mes)
                ->whereYear("data",$ano)
                ->first();


            $total_individual_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                //->where("finalizado",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereHas('comissao',function($query){
                    $query->where("plano_id",1);
                })->count();




            $total_empresarial_quantidade = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereHas('comissao',function($query){
                    $query->where("plano_id","!=",1);
                    $query->where("plano_id","!=",3);
                })->count();


            $total_empresarial = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
                SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = 03
                ) AS plano1,
                (
                SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = 03
                ) AS plano3
            ")[0]->total_empresarial_valor;


            $total_individual = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
                SELECT
                        SUM(valor)
                        AS total_plano1
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} and year(data_baixa_finalizado) = {$ano}
                ) AS plano1,
                (
                SELECT
                    SUM(valor)
                    AS total_plano3
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} and year(data_baixa_finalizado) = {$ano}
                ) AS plano3;
            ")[0]->total_individual_valor;



            $total_coletivo = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
                SELECT
                    SUM(valor)
                    AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano1,
                (
                SELECT
                    SUM(valor)
                    AS total_plano3
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                ) AS plano3
            ")[0]->total_coletivo_valor;


            return [
                "view" => view('admin.pages.gerente.list-users-pdf',[
                    "users" => $users
                ])->render(),
                "dados" => $dados,
                "users" => $users_select,
                "valores" => $valores,
                "total_individual_quantidade" => $total_individual_quantidade,
                "total_empresarial_quantidade" => 0,
                "total_empresarial" => 0,
                "total_individual" => $total_individual,
                "total_coletivo" => 0
            ];






        }






    }

    public function geralFolhaMesEspecifica(Request $request)
    {
        $mes = $request->mes;
        $ano = $request->ano;
        $dados = DB::table('valores_corretores_lancados')
              ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user")
              ->selectRaw("valor_comissao,valor_salario,valor_premiacao")
              ->selectRaw("(valor_comissao+valor_salario+valor_premiacao) AS total")
              ->whereRaw("MONTH(data) = ${mes}")
              ->whereRaw("YEAR(data) = ${ano}")
              ->get();


        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];

        $mes_folha = $meses[$mes];


        $pdf = PDF::loadView('admin.pages.gerente.pdf-folha-mes-geral',[
            "dados" => $dados,
            "mes" => $mes_folha
        ]);

        $nome_pdf = "america_".$mes."_".$ano;
        return $pdf->download($nome_pdf);

    }



    public function pegarTodososDados(Request $request)
    {
        $ano = $request->campo_ano != "todos" ? $request->campo_ano : false;
        $mes = $request->campo_mes != "todos" ? $request->campo_mes : false;
        $id = $request->campo_cor  != "todos" ? $request->campo_cor : false;

        /** QUANTIDADE GERAL */
        $quantidade_sem_empresaria_geral = Contrato::whereHas('clientes',function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $quantidade_com_empresaria_geral = ContratoEmpresarial
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();
        $quantidade_geral = $quantidade_sem_empresaria_geral + $quantidade_com_empresaria_geral;
        /** FIM QUANTIDADE GERAL */

        /** VALOR GERAL */
        $total_sem_empresa_valor_geral = Contrato::whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(SUM(valor_plano)>0,SUM(valor_plano),0) as total_geral")
        ->first()
        ->total_geral;

        $total_com_empresa_valor_geral = ContratoEmpresarial
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_total)>0,sum(valor_total),0) as valor_total")
        ->first()
        ->valor_total;

        $total_valor_geral = $total_sem_empresa_valor_geral + $total_com_empresa_valor_geral;
        /** FIM VALOR GERAL */

        /** QUANTIDADE vidas GERAL */
        $quantidade_sem_empresa_vidas_geral =
        Cliente
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")
        ->first()
        ->quantidade_vidas;

        $quantidade_com_empresa_vidas_geral = ContratoEmpresarial
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as quantidade_vidas")
        ->first()
        ->quantidade_vidas;
        $quantidade_geral_vidas = $quantidade_sem_empresa_vidas_geral + $quantidade_com_empresa_vidas_geral;
        /** FIM QUANTIDADE vidas GERAL */


        /*** QUANTIDADE Recebidos */
        $total_quantidade_recebidos = Contrato::whereHas("clientes",function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();


        $quantidade_recebidas_empresarial = ContratoEmpresarial
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $total_geral_recebidas = $total_quantidade_recebidos + $quantidade_recebidas_empresarial;


        /*** FIM quantidade Recebidos */



        /*** Valor Total a Recebidos */
        $total_valor_recebidos = Contrato::whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        $total_valor_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("sum(valor_total) as total_valor_plano")
        ->first()
        ->total_valor_plano;
        $total_geral_recebidos_valor = $total_valor_recebidos + $total_valor_recebidos_empresarial;
        /*** FIM Valor Total a Recebidos */

        /*****Qunatidade de Vidas a Recebidos */
        $quantidade_vidas_recebidas = Cliente
        ::where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_recebidas_empresarial = ContratoEmpresarial
        ::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_recebidas_geral = $quantidade_vidas_recebidas + $quantidade_vidas_recebidas_empresarial;

        /*****Qunatidade de Vidas a Recebidos */


        /********Quantidade a Receber Geral */
        $total_quantidade_a_receber = Contrato
        ::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $total_quantidade_a_receber_empresarial = ContratoEmpresarial
        ::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $total_quantidade_a_receber_geral = $total_quantidade_a_receber + $total_quantidade_a_receber_empresarial;

        /********FIM Quantidade a Receber Geral */


        /*******Valor A Receber Geral */
        $total_valor_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        $total_valor_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as total_valor_plano")->first()->total_valor_plano;
        $total_valor_a_receber_geral = $total_valor_a_receber + $total_valor_a_receber_empresarial;
         /*******FIM Valor A Receber Geral */


        /*******QUANTIDADe DE VIDAS A RECEBER GERAL */
        $quantidade_vidas_a_receber = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")->first()->total_quantidade_vidas_recebidas;

        $quantidade_vidas_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $quantidade_vidas_a_receber_geral = $quantidade_vidas_a_receber +  $quantidade_vidas_a_receber_empresarial;
        /*******FIM QUANTIDADe DE VIDAS A RECEBER GERAL */


        /****Quantidade Atrasada de Geral */
        $qtd_atrasado = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $qtd_atrasado_empresarial = ContratoEmpresarial::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $quantidade_atrasado_geral = $qtd_atrasado + $qtd_atrasado_empresarial;
        /****FIM Quantidade Atrasada de Geral */

        /****Valor Atrasada de Geral */
        $qtd_atrasado_valor = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("sum(valor_plano) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        $qtd_atrasado_valor_empresarial = ContratoEmpresarial
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("sum(valor_total) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_valor_geral = $qtd_atrasado_valor + $qtd_atrasado_valor_empresarial;
        /****FIM Valor Atrasada de Geral */

        /****Vidas Atrasada de Geral */
        $qtd_atrasado_quantidade_vidas = Cliente::
        whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

            $query->whereIn('financeiro_id',[3,4,5,6,7,8,9,10]);
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")
        ->first()
        ->total_quantidade_vidas_atrasadas;

        $qtd_atrasado_quantidade_vidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_atrasado_quantidade_vidas_geral = $qtd_atrasado_quantidade_vidas + $qtd_atrasado_quantidade_vidas_empresarial;
        /****Vidas Atrasada de Geral */






        /** Quantidade de Finalizado Geral */
        $qtd_finalizado = Contrato
        ::where("financeiro_id",11)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $qtd_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();
        $qtd_finalizado_geral = $qtd_finalizado + $qtd_finalizado_empresarial;
        /** FIM Quantidade de Finalizado Geral */

        /** Valor de Finalizado Geral */
        $quantidade_valor_finalizado = Contrato::where("financeiro_id",11)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $quantidade_valor_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $quantidade_geral_finalizado = $quantidade_valor_finalizado + $quantidade_valor_finalizado_empresarial;
        /** FIM Valor de Finalizado Geral */

        /** Valor de Finalizado Geral */
        $qtd_finalizado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",11);

        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_finalizado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",11)
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $quantidade_finalizado_quantidade_vidas_geral = $qtd_finalizado_quantidade_vidas + $qtd_finalizado_quantidade_vidas_empresarial;
        /** FIM Valor de Finalizado Geral */


        /**** Quantiade de Cancelados */
        $qtd_cancelado = Contrato::where("financeiro_id",12)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $qtd_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $quantidade_geral_cancelado = $qtd_cancelado + $qtd_cancelado_empresarial;
         /**** FIM Quantiade de Cancelados */

        /**** Valor de Cancelados */
        $quantidade_valor_cancelado_valor = Contrato::where("financeiro_id",12)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $quantidade_valor_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $quantidade_geral_cancelado_valor = $quantidade_valor_cancelado_valor + $quantidade_valor_cancelado_empresarial;
        /**** FIM Valor de Cancelados */

        /**** Quantidade de Vidas de Cancelados */
        $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",12);

        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        $qtd_cancelado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",12)->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        $quantidade_cancelado_vidas_geral = $qtd_cancelado_quantidade_vidas + $qtd_cancelado_quantidade_vidas_empresarial;
        /**** FIM Quantidade de Vidas de Cancelados */



        //FIM Geral

        //Individual

        $quantidade_individual_geral = Contrato::where("plano_id",1)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();



        $total_valor_geral_individual = Contrato::where("plano_id",1)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;

        $quantidade_vidas_geral_individual = Cliente::whereHas('contrato',function($query) use($ano,$mes){
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })

        ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;


        $total_quantidade_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->count();

        $total_valor_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->selectRaw("if(sum(valor_plano)>0,sum(valor_plano),0) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        $quantidade_vidas_recebidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })

        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $total_quantidade_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->count();

        $total_valor_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

        $quantidade_vidas_a_receber_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })

        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $qtd_atrasado_individual = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->count();

        $qtd_atrasado_valor_individual = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",1)
        ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_quantidade_vidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",1);
            $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_finalizado_individual = Contrato::where("financeiro_id",11)->where('plano_id',1)
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $quantidade_valor_finalizado_individual = Contrato::where("financeiro_id",11)
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where('plano_id',1)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query)use($mes,$ano){
            $query->where("financeiro_id",11);
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_individual = Contrato::where("financeiro_id",12)
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where('plano_id',1)
        ->count();

        $quantidade_valor_cancelado_individual = Contrato::where("financeiro_id",12)->where('plano_id',1)
        ->whereHas('clientes',function($query)use($id){
            $query->whereRaw('cateirinha IS NOT NULL');
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query)use($mes,$ano){
            $query->where("financeiro_id",12);
            $query->where("plano_id",1);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })

        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;





        //Fim Individual

        //Coletivo

        $quantidade_coletivo_geral     = Contrato::where("plano_id",3)
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->count();

        $total_valor_geral_coletivo = Contrato::where("plano_id",3)
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(SUM(valor_plano)>0,SUM(valor_plano),0) as total_geral")
        ->first()
        ->total_geral;

        $quantidade_vidas_geral_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(SUM(quantidade_vidas)>0,SUM(quantidade_vidas),0) as quantidade_vidas")
        ->first()
        ->quantidade_vidas;


        $total_quantidade_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }

        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->count();

        $total_valor_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->selectRaw("sum(valor_plano) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        $quantidade_vidas_recebidas_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        $total_quantidade_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->count();

        $total_valor_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas("clientes",function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

        $quantidade_vidas_a_receber_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;



        $qtd_atrasado_coletivo = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->count();

        $qtd_atrasado_valor_coletivo = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where("plano_id",3)
        ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

        $qtd_atrasado_quantidade_vidas_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query)use($ano,$mes){
            $query->where("plano_id",3);
            $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")
        ->first()
        ->total_quantidade_vidas_atrasadas;


        $qtd_finalizado_coletivo = Contrato::where("financeiro_id",11)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where('plano_id',3)
        ->count();

        $quantidade_valor_finalizado_coletivo = Contrato::where("financeiro_id",11)->where('plano_id',3)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("financeiro_id",11);
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query)use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_coletivo = Contrato::where("financeiro_id",12)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where('plano_id',3)
        ->count();

        $quantidade_valor_cancelado_coletivo = Contrato::where("financeiro_id",12)->where('plano_id',3)
        ->whereHas('clientes',function($query)use($id){
            if($id) {
                $query->where('user_id',$id);
            }
        })
        ->where(function($query) use($ano,$mes){
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query)use($ano,$mes){
            $query->where("financeiro_id",12);
            $query->where("plano_id",3);
            if($ano) {
                $query->whereYear('created_at',$ano);
            }
            if($mes) {
                $query->whereMonth('created_at',$mes);
            }
        })
        ->where(function($query) use($id){
            if($id) {
                $query->where("user_id",$id);
            }
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")
        ->first()
        ->total_quantidade_vidas_cancelado;


        return [

            "quantidade_geral" => $quantidade_geral,
            "total_valor_geral" => number_format($total_valor_geral,2,",","."),
            "quantidade_geral_vidas" => $quantidade_geral_vidas,

            "total_geral_recebidas" => $total_geral_recebidas,
            "total_geral_recebidos_valor" => number_format($total_geral_recebidos_valor,2,",","."),
            "quantidade_vidas_recebidas_geral" => $quantidade_vidas_recebidas_geral,

            "total_quantidade_a_receber_geral" => $total_quantidade_a_receber_geral,
            "total_valor_a_receber_geral" => number_format($total_valor_a_receber_geral,2,",","."),
            "quantidade_vidas_a_receber_geral" => $quantidade_vidas_a_receber_geral,

            "quantidade_atrasado_geral" => $quantidade_atrasado_geral,
            "quantidade_atrasado_valor_geral" => number_format($qtd_atrasado_valor_geral,2,",","."),
            "qtd_atrasado_quantidade_vidas_geral" => $qtd_atrasado_quantidade_vidas_geral,

            "quantidade_finalizado_geral" => $qtd_finalizado_geral,
            "quantidade_geral_finalizado" => number_format($quantidade_geral_finalizado,2,",","."),
            "quantidade_finalizado_quantidade_vidas_geral" => $quantidade_finalizado_quantidade_vidas_geral,

            "quantidade_geral_cancelado" => $quantidade_geral_cancelado,
            "quantidade_geral_cancelado_valor" => number_format($quantidade_geral_cancelado_valor,2,",","."),
            "quantidade_cancelado_vidas_geral" => $quantidade_cancelado_vidas_geral,

            /****INdividual */

            "quantidade_individual_geral" => $quantidade_individual_geral,
            "total_valor_geral_individual" => number_format($total_valor_geral_individual,2,",","."),
            "quantidade_vidas_geral_individual" => $quantidade_vidas_geral_individual,

            "total_quantidade_recebidos_individual" => $total_quantidade_recebidos_individual,
            "total_valor_recebidos_individual" => number_format($total_valor_recebidos_individual,2,",","."),
            "quantidade_vidas_recebidas_individual" => $quantidade_vidas_recebidas_individual,

            "total_quantidade_a_receber_individual" => $total_quantidade_a_receber_individual,
            "total_valor_a_receber_individual" => number_format($total_valor_a_receber_individual,2,",","."),
            "quantidade_vidas_a_receber_individual" => $quantidade_vidas_a_receber_individual,

            "qtd_atrasado_individual" => $qtd_atrasado_individual,
            "qtd_atrasado_valor_individual" => number_format($qtd_atrasado_valor_individual,2,",","."),
            "qtd_atrasado_quantidade_vidas_individual" => $qtd_atrasado_quantidade_vidas_individual,

            "qtd_finalizado_individual" => $qtd_finalizado_individual,
            "quantidade_valor_finalizado_individual" => $quantidade_valor_finalizado_individual,
            "qtd_finalizado_quantidade_vidas_individual" => $qtd_finalizado_quantidade_vidas_individual,

            "qtd_cancelado_individual" => $qtd_cancelado_individual,
            "quantidade_valor_cancelado_individual" => $quantidade_valor_cancelado_individual,
            "qtd_cancelado_quantidade_vidas_individual" => $qtd_cancelado_quantidade_vidas_individual,

            //////////Coletivo
            'quantidade_coletivo_geral' => $quantidade_coletivo_geral,

            'total_valor_geral_coletivo' => number_format($total_valor_geral_coletivo,2,",","."),

            'quantidade_vidas_geral_coletivo' => $quantidade_vidas_geral_coletivo,

            'total_quantidade_recebidos_coletivo' => $total_quantidade_recebidos_coletivo,
            'total_valor_recebidos_coletivo' => number_format($total_valor_recebidos_coletivo,2,",","."),
            'quantidade_vidas_recebidas_coletivo' => $quantidade_vidas_recebidas_coletivo,

            'total_quantidade_a_receber_coletivo' => $total_quantidade_a_receber_coletivo,
            'total_valor_a_receber_coletivo' => number_format($total_valor_a_receber_coletivo,2,",","."),
            'quantidade_vidas_a_receber_coletivo' => $quantidade_vidas_a_receber_coletivo,

            'qtd_atrasado_coletivo' => $qtd_atrasado_coletivo,
            'qtd_atrasado_valor_coletivo' => number_format($qtd_atrasado_valor_coletivo,2,",","."),
            'qtd_atrasado_quantidade_vidas_coletivo' => $qtd_atrasado_quantidade_vidas_coletivo,

            'qtd_finalizado_coletivo' => $qtd_finalizado_coletivo,
            'quantidade_valor_finalizado_coletivo' => number_format($quantidade_valor_finalizado_coletivo,2,",","."),
            'qtd_finalizado_quantidade_vidas_coletivo' => $qtd_finalizado_quantidade_vidas_coletivo,

            'qtd_cancelado_coletivo' => $qtd_cancelado_coletivo,
            'quantidade_valor_cancelado_coletivo' => number_format($quantidade_valor_cancelado_coletivo,2,",","."),
            'qtd_cancelado_quantidade_vidas_coletivo' => $qtd_cancelado_quantidade_vidas_coletivo,

             ///Empresarial

            "quantidade_com_empresaria_geral" => $quantidade_com_empresaria_geral,
            "total_com_empresa_valor_geral" => number_format($total_com_empresa_valor_geral,2,",","."),
            "quantidade_com_empresa_vidas_geral" => $quantidade_com_empresa_vidas_geral,

            "quantidade_recebidas_empresarial" => $quantidade_recebidas_empresarial,
            "total_valor_recebidos_empresarial" =>  number_format($total_valor_recebidos_empresarial,2,",","."),
            "quantidade_vidas_recebidas_empresarial" => $quantidade_vidas_recebidas_empresarial,


            "total_quantidade_a_receber_empresarial" => $total_quantidade_a_receber_empresarial,
            "total_valor_a_receber_empresarial" => number_format($total_valor_a_receber_empresarial,2,",","."),
            "quantidade_vidas_a_receber_empresarial" => $quantidade_vidas_a_receber_empresarial,

            "qtd_atrasado_empresarial" => $qtd_atrasado_empresarial,
            "qtd_atrasado_valor_empresarial" => number_format($qtd_atrasado_valor_empresarial,2,",","."),
            "qtd_atrasado_quantidade_vidas_empresarial" => $qtd_atrasado_quantidade_vidas_empresarial,

            "qtd_finalizado_empresarial" => $qtd_finalizado_empresarial,
            "quantidade_valor_finalizado_empresarial" => number_format($quantidade_valor_finalizado_empresarial,2,",","."),
            "qtd_finalizado_quantidade_vidas_empresarial" => $qtd_finalizado_quantidade_vidas_empresarial,

            "qtd_cancelado_empresarial" => $qtd_cancelado_empresarial,
            "quantidade_valor_cancelado_empresarial" => number_format($quantidade_valor_cancelado_empresarial,2,",","."),
            "qtd_cancelado_quantidade_vidas_empresarial" => $qtd_cancelado_quantidade_vidas_empresarial



        ];

    }


    public function showDetalhesDadosTodosAll($id_estagio)
    {
        $estagio = 0;

        switch($id_estagio) {

            case 1:
                $quantidade = Contrato::count();
                $valor      = Contrato::selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
                $vidas      = Cliente::selectRaw("SUM(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;
                $quantidade_empresarial_geral  = ContratoEmpresarial::count();
                $quantidade_vidas_geral_empresarial = ContratoEmpresarial::selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;

                $quantidade_total = $quantidade + $quantidade_empresarial_geral;
                $valor_total = DB::select("select (select sum(valor_plano) from contratos) + (select sum(valor_plano) from contrato_empresarial) as total_soma_formatado")[0]->total_soma_formatado;
                $vidas_total = $vidas + $quantidade_vidas_geral_empresarial;
                $estagio = 1;
            break;

            case 2:
                $total_quantidade_recebidos = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })->count();

                $total_valor_recebidos = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

                $quantidade_vidas_recebidas = Cliente
                ::whereHas('contrato',function($query){
                    $query->where('plano_id',1);
                })
                ->whereHas('contrato.comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                ->first()
                ->total_quantidade_vidas_recebidas;


                $total_quantidade_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                ->count();


                $total_valor_recebidos_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                ->selectRaw("sum(valor_total) as total_valor_plano")
                ->first()
                ->total_valor_plano;

                $quantidade_vidas_recebidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",1);
                    $query->where("valor","!=",0);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                ->first()
                ->total_quantidade_vidas_recebidas;

                $quantidade_total = $total_quantidade_recebidos + $total_quantidade_recebidos_empresarial;
                $valor_total = $total_valor_recebidos + $total_valor_recebidos_empresarial;
                $vidas_total = $quantidade_vidas_recebidas + $quantidade_vidas_recebidas_empresarial;
                $estagio = 2;
            break;

            case 3:

                $total_quantidade_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("status_financeiro",1);
                    $query->where("status_gerente",0);
                    $query->where("valor","!=",0);
                 })->count();

                 $total_valor_a_receber = Contrato::whereHas('comissao.comissoesLancadas',function($query){
                   $query->where("status_financeiro",1);
                   $query->where("status_gerente",0);
                   $query->where("valor","!=",0);
                 })
                 ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;

                 $quantidade_vidas_a_receber = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
                   $query->where("status_financeiro",1);
                   $query->where("status_gerente",0);
                   $query->where("valor","!=",0);
                 })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")->first()->total_quantidade_vidas_recebidas;

                 $total_quantidade_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                  $query->where("status_financeiro",1);
                  $query->where("status_gerente",0);
                  $query->where("valor","!=",0);
                 })
                 ->count();

                 $total_valor_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                  $query->where("status_financeiro",1);
                  $query->where("status_gerente",0);
                  $query->where("valor","!=",0);
                 })
                 ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as total_valor_plano")->first()->total_valor_plano;

                 $quantidade_vidas_a_receber_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                  $query->where("status_financeiro",1);
                  $query->where("status_gerente",0);
                  $query->where("valor","!=",0);
                 })
                 ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
                 ->first()
                 ->total_quantidade_vidas_recebidas;

                $quantidade_total = $total_quantidade_a_receber + $total_quantidade_a_receber_empresarial;
                $valor_total = $total_valor_a_receber + $total_valor_a_receber_empresarial;
                $vidas_total = $quantidade_vidas_a_receber + $quantidade_vidas_a_receber_empresarial;
                $estagio = 3;

            break;

            case 4:

                $qtd_atrasado = Contrato::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })->count();


                $qtd_atrasado_valor = Contrato
                ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
                })->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;



                $qtd_atrasado_quantidade_vidas = Cliente
                ::whereHas('contrato.comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })->whereHas('contrato',function($query){
                    $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
                })
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

                $qtd_atrasado_empresarial = ContratoEmpresarial
                ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
                })->count();



                $qtd_atrasado_valor_empresarial = ContratoEmpresarial
                ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
                })->selectRaw("sum(valor_total) as total_valor_plano")->first()->total_valor_plano;



                $qtd_atrasado_quantidade_vidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
                })
                ->whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

                $quantidade_total = $qtd_atrasado + $qtd_atrasado_empresarial;
                $valor_total = $qtd_atrasado_valor + $qtd_atrasado_valor_empresarial;
                $vidas_total = $qtd_atrasado_quantidade_vidas + $qtd_atrasado_quantidade_vidas_empresarial;
                $estagio = 4;

            break;

            case 5:

                $qtd_cancelado = Contrato::where("financeiro_id",12)->count();
                $quantidade_valor_cancelado = Contrato::where("financeiro_id",12)
                ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;
                $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
                $query->where("financeiro_id",12);
                })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;
                $qtd_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)->count();

                $quantidade_valor_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
                ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_cancelado")->first()->valor_total_cancelado;

                $qtd_cancelado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",12)
                ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

                $quantidade_total = $qtd_cancelado + $qtd_cancelado_empresarial;
                $valor_total = $quantidade_valor_cancelado + $quantidade_valor_cancelado_empresarial;
                $vidas_total = $qtd_cancelado_quantidade_vidas + $qtd_cancelado_quantidade_vidas_empresarial;
                $estagio = 5;

            break;


            case 6:

            break;
        }
        return view('admin.pages.gerente.detalhe-card-todos',[
            "quantidade" => $quantidade_total,
            "valor" => $valor_total,
            "vidas" => $vidas_total,
            "estagio" => $estagio
        ]);
    }







    public function verDetalheCard($id_plano="all",$id_tipo="alll",$ano="all",$mes="all",$corretor="all")
    {
        return view('admin.pages.gerente.detalhe-card',[
            "id_plano" => $id_plano,
            "id_tipo" => $id_tipo,
            "ano" => $ano,
            "mes" => $mes,
            "corretor" => $corretor
        ]);
    }

    public function showDetalheCard($id_plano,$id_tipo,$ano,$mes,$corretor)
    {
        //$id_plano = $id_plano == "all" ? null : $id_plano;
        //$id_tipo = $id_tipo == "all" ? null : $id_tipo;
        $ano = $ano == "all" ? null : $ano;
        $mes = $mes == "all" ? null : $mes;
        $corretor = $corretor == "all" ? null : $corretor;








        if($id_plano == 1) {
            switch($id_tipo) {
                case 1:
                    $contratos = Contrato
                    ::where("plano_id",1)
                    //->whereIn('financeiro_id',[1,2,3,4,5,6,7,8,9,10])
                    ->whereHas('clientes',function($query)use($corretor){
                        //$query->whereRaw("cateirinha IS NOT NULL");
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })


                    // ->whereHas('comissao.ultimaComissaoPaga',function($query){
                    //     $query->whereYear("data",2022);
                    //     $query->whereMonth('data','08');
                    // })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;

                break;
                case 2:

                    $contratos = Contrato
                    ::where("plano_id",1)
                    ->whereHas('clientes',function($query)use($corretor){
                        $query->whereRaw("cateirinha IS NOT NULL");
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->where("status_financeiro",1);
                        $query->where("status_gerente",1);
                        $query->where("valor","!=",0);
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;



                break;
                case 3:
                    $contratos = Contrato
                    ::where("plano_id",1)
                    ->whereHas('clientes',function($query)use($corretor){
                        $query->whereRaw("cateirinha IS NOT NULL");
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->where("status_financeiro",1);
                        $query->where("status_gerente",0);
                        $query->where("valor","!=",0);
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;
                break;
                case 4:
                    $contratos = Contrato
                    ::where("plano_id",1)
                    //->where("financeiro_id","!=",12)
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        //$query->whereRaw("valor > 0");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('clientes',function($query)use($corretor){
                        //$query->whereRaw('cateirinha IS NOT NULL');
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;


                break;
                case 5:

                    $contratos = Contrato
                    ::where("financeiro_id",12)
                    ->where("plano_id",1)
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;


                break;
                case 6:

                    $contratos = Contrato
                    ::where("financeiro_id",11)
                    ->where("plano_id",1)
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;

                    break;
                default:
                    return [];
                break;
            }


        } else if($id_plano == 2) {
            switch($id_tipo) {
                case 1:
                    $contratos = Contrato
                    ::where("plano_id",3)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })

                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;

                break;
                case 2:

                    $contratos = Contrato
                    ::where("plano_id",3)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->where("status_financeiro",1);
                        $query->where("status_gerente",1);
                        $query->where("valor","!=",0);
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;



                break;
                case 3:
                    $contratos = Contrato
                    ::where("plano_id",3)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->where("status_financeiro",1);
                        $query->where("status_gerente",0);
                        $query->where("valor","!=",0);
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->orderBy("id","desc")
                    ->get();
                    return $contratos;
                break;
                case 4:
                    $contratos = Contrato
                    ::where("plano_id",3)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->where("financeiro_id","!=",12)
                    ->whereHas('comissao.comissoesLancadas',function($query){
                        $query->whereRaw("DATA < CURDATE()");
                        //$query->whereRaw("valor > 0");
                        $query->whereRaw("data_baixa IS NULL");
                        $query->groupBy("comissoes_id");
                    })
                    ->whereHas('clientes',function($query){
                        $query->whereRaw('cateirinha IS NOT NULL');
                    })
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;


                break;
                case 5:

                    $contratos = Contrato
                    ::where("financeiro_id",12)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->where("plano_id",3)
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;


                break;
                case 6:

                    $contratos = Contrato
                    ::where("financeiro_id",11)
                    ->whereHas('clientes',function($query)use($corretor){
                        if($corretor) {
                            $query->where("user_id",$corretor);
                        }
                    })
                    ->where(function($query)use($ano,$mes){
                        if($ano) {
                            $query->whereYear('created_at',$ano);
                        }
                        if($mes) {
                            $query->whereMonth('created_at',$mes);
                        }
                    })
                    ->where("plano_id",3)
                    ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                    ->get();

                    return $contratos;






                break;
                default:
                    return [];
                break;
            }
        } else if($id_plano == 3) {
            switch($id_tipo) {
                case 1:
                    return [];

                break;
                case 2:
                    return [];

                break;
                case 3:
                    return [];
                break;
                case 4:
                    return [];
                break;
                case 5:
                   return [];
                break;
                case 6:
                    return [];
                break;
                default:
                    return [];
                break;
            }
        }
    }

    public function infoCorretorHistorico(Request $request)
    {
        $id = $request->id;
        $mes = $request->mes;

        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->count();

        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
                $query->where("user_id",$id);
            })->count();



        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano3;
        ")[0]->total_individual_valor;


        $total_empresarial = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND user_id = {$id}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            AND user_id = {$id}
            ) AS plano3
        ")[0]->total_empresarial_valor;

        $total_coletivo = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND user_id = {$id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} AND user_id = {$id}
            ) AS plano3
        ")[0]->total_coletivo_valor;


        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->where("user_id",$id);

        $va = $valores->first();
        $salario = $va->valor_salario;
        $premiacao = $va->valor_premiacao;
        $comissao = $va->valor_comissao;
        $desconto = $va->valor_desconto;
        $total = $va->valor_total;
        $estorno = $va->valor_estorno;


        return [
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,

            "total_individual" => $total_individual,
            "total_empresarial" => $total_empresarial,
            "total_coletivo" => $total_coletivo,

            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),

            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",",".")

        ];

    }








    public function infoCorretor(Request $request)
    {
        
        $premiacao_cad = str_replace([".",","],["","."], $request->premiacao);
        $salario_cad = str_replace([".",","],["","."], $request->salario);
        $total_cad   = str_replace([".",","],["","."], $request->total);
        ValoresCorretoresLancados
            ::where("user_id",$request->user_id)
            ->whereMonth("data",$request->mes)
            ->whereYear("data",$request->ano)
            ->update(["valor_premiacao"=>$premiacao_cad,"valor_total"=>$total_cad,"valor_salario"=>$salario_cad]);
        $id = $request->id;
        $mes = $request->mes;
        $ano = $request->ano;
        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;


        $valor_individual_a_receber = DB::select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 1
        ")[0]->total;






        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth('data_baixa_finalizado',$mes)
            ->whereyear('data_baixa_finalizado',$ano)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->count();





        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor_pago ELSE valor END), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor THEN valor_pago ELSE valor END), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes} AND year(data_baixa_finalizado) = {$ano}
            AND user_id = {$id}
            ) AS plano3;
        ")[0]->total_individual_valor;





        if($comissao == 0 && ($total_individual > 0)) {
            $comissao = $total_individual;

        }




        $ids_confirmados = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
             ->whereyear('data_baixa_finalizado',$ano)
            ->whereHas('comissao.user',function($query) use($id){
                $query->where("id",$id);
            })
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;

        $desconto = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereMonth("data_baixa_finalizado",$mes)
             ->whereyear('data_baixa_finalizado',$ano)
            ->whereHas('comissao.user',function($query)  use($id){
                $query->where("id",$id);
            })
            ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
            ->first()
            ->total;

        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->whereYear('data',$ano)->where("user_id",$id);
        if($valores->count() == 1) {
            $va = $valores->first();
            $salario = $va->valor_salario;
            $premiacao = $va->valor_premiacao;
            $comissao = $va->valor_comissao;
            $desconto = $va->valor_desconto;
            $total = $va->valor_total;
            $estorno = $va->valor_estorno;
        } else {
            $desconto = ComissoesCorretoresLancadas
                ::where("status_financeiro",1)
                ->where("status_apto_pagar",1)
                ->whereMonth("data_baixa_finalizado",$mes)
                ->whereyear('data_baixa_finalizado',$ano)
                ->whereHas('comissao.user',function($query) use($id){
                    $query->where("id",$id);
                })
                ->selectRaw("if(SUM(desconto)>0,SUM(desconto),0) AS total")
                ->first()
                ->total;

            $total = $comissao - $desconto;
        }



        $users = DB::select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancados
            inner join users on users.id = valores_corretores_lancados.user_id
            where MONTH(data) = {$mes} AND YEAR(data) = {$ano} order by users.name
        ");



        $usuarios = DB::select("
            SELECT users.id AS id, users.name AS name
            FROM comissoes_corretores_lancadas
                     INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                     INNER JOIN users ON users.id = comissoes.user_id
            WHERE (status_financeiro = 1 or status_gerente = 1) and finalizado != 1 and valor != 0 and users.id NOT IN (SELECT user_id FROM valores_corretores_lancados WHERE MONTH(data) = {$mes} AND YEAR(data) = {$ano})
            GROUP BY users.id, users.name
            ORDER BY users.name;
         ");

        return [
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => "",
            "total_empresarial_quantidade" => "",
            "total_individual" => number_format($total_individual,2,",","."),
            "total_coletivo" => 0,
            "total_empresarial" => 0,
            "total_comissao" =>  number_format($comissao,2,",","."),
            "total_salario" =>  number_format($salario,2,",","."),
            "total_premiacao" =>  number_format($premiacao,2,",","."),
            "id_confirmados" => $ids_confirmados,
            "desconto" =>  number_format($desconto,2,",","."),
            "total" =>  number_format($total,2,",","."),
            "estorno" => number_format($estorno,2,",","."),
            "view" => view('admin.pages.gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            "usuarios" => $usuarios,
            "valor_individual_a_receber" => $valor_individual_a_receber,
            "valor_coletivo_a_receber" => "",
            "valor_empresarial_a_receber" => ""
        ];


    }

    public function pegarTodosMesCorrente(Request $request) {
        $mes = $request->mes;
        $ano = $request->ano;
        $users = DB::select("
            select name as user,users.id as user_id,valor_total as total from
            valores_corretores_lancados
            inner join users on users.id = valores_corretores_lancados.user_id
            where MONTH(data) = {$mes} AND YEAR(data) = {$ano} order by users.name
        ");
        return [
            "view" => view('admin.pages.gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
        ];
    }




    public function showTodosDetalheCard($estagio)
    {

        if($estagio == 1) {
            $dados = DB::select("
                select
                    case when comissoes.empresarial then
                        date_format(contrato_empresarial.created_at,'%d/%m/%Y')
                    else
                        date_format(contratos.created_at,'%d/%m/%Y')
                    end as data,
                    case when comissoes.empresarial then
                        contrato_empresarial.codigo_externo
                    else
                        contratos.codigo_externo
                    end as orcamento,
                    users.name as corretor,
                    case when comissoes.empresarial then
                        contrato_empresarial.razao_social
                    else
                        (select nome from clientes where clientes.id = contratos.cliente_id)
                    end as cliente,
                    case when comissoes.empresarial then
                        contrato_empresarial.cnpj
                    else
                        (select cpf from clientes where clientes.id = contratos.cliente_id)
                    end as documento,
                    case when comissoes.empresarial then
                        contrato_empresarial.quantidade_vidas
                    else
                        (select quantidade_vidas from clientes where clientes.id = contratos.cliente_id)
                    end as vidas,
                    case when comissoes.empresarial then
                        contrato_empresarial.valor_plano
                    else
                        contratos.valor_plano
                    end as valor,
                    comissoes.plano_id as plano,
                    planos.nome as plano_nome,
                    case when comissoes.empresarial then
                        contrato_empresarial.id
                    else
                        contratos.id
                    end as id
                from comissoes
                    inner join users on users.id = comissoes.user_id
                    inner join planos on planos.id = comissoes.plano_id
                    left join contratos on contratos.id = comissoes.contrato_id
                    left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id
                order by comissoes.created_at
            ");
            return $dados;
        } else if($estagio == 2) {
            $dados = Comissoes
            ::whereHas('comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",1);
                $query->where("valor","!=",0);
            })
            ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();
            return $dados;
        } else if($estagio == 3) {
            $dados = DB::select("
                select
                    case when comissoes.empresarial then
                        date_format(contrato_empresarial.created_at,'%d/%m/%Y')
                    else
                        date_format(contratos.created_at,'%d/%m/%Y')
                    end as data,
                    case when comissoes.empresarial then
                        contrato_empresarial.codigo_externo
                    else
                        contratos.codigo_externo
                    end as orcamento,
                    users.name as corretor,
                    case when comissoes.empresarial then
                        contrato_empresarial.razao_social
                    else
                        (select nome from clientes where clientes.id = contratos.cliente_id)
                    end as cliente,
                    case when comissoes.empresarial then
                        contrato_empresarial.cnpj
                    else
                        (select cpf from clientes where clientes.id = contratos.cliente_id)
                    end as documento,
                    case when comissoes.empresarial then
                        contrato_empresarial.quantidade_vidas
                    else
                        (select quantidade_vidas from clientes where clientes.id = contratos.cliente_id)
                    end as vidas,
                    case when comissoes.empresarial then
                        contrato_empresarial.valor_plano
                    else
                        contratos.valor_plano
                    end as valor,
                    comissoes.plano_id as plano,
                    planos.nome as plano_nome,
                    case when comissoes.empresarial then
                        contrato_empresarial.id
                    else
                        contratos.id
                    end as id
                from comissoes
                    inner join users on users.id = comissoes.user_id
                    inner join planos on planos.id = comissoes.plano_id
                    left join contratos on contratos.id = comissoes.contrato_id
                    left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id

                where
                order by comissoes.created_at
            ");
            return $dados;









            $dados = Comissoes
            ::whereHas('comissoesLancadas',function($query){
                $query->where("status_financeiro",1);
                $query->where("status_gerente",0);
                $query->where("valor","!=",0);
            })
            ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();
            return $dados;
        } else if($estagio == 4) {
            $dados = Comissoes
            ::whereHas('comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
            ->with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])
            ->get();

            return $dados;
        } else if($estagio == 5) {
            $dados = [];
            return $dados;
        } else if($estagio == 6) {
            $dados = [];
            return $dados;
        } else {
            $dados = [];
            return $dados;
        }
    }

    public function concluidos()
    {
        $dados = DB::select(
            "
            SELECT
            (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
            (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
            (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
            (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,
            comissoes_corretores_lancadas.data as vencimento,
            case when empresarial then
                (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
            END AS cliente,
            case when empresarial then
                (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS codigo_externo,
            case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor,
            comissoes.id AS comissao
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE status_financeiro = 1 AND status_gerente = 1 AND valor != 0
            GROUP BY comissao
                ");

            return $dados;
    }




    public function listagem(Request $request)
    {

        if ($request->ajax()) {
            $cacheKey = 'listagemNaoConcluidosParcela';
            $tempoDeExpiracao = 60;

            $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {

                return DB::select('
                    SELECT
                    administradoras.nome AS administradora,
                    users.name AS corretor,
                    planos.nome AS plano,
                    tabela_origens.nome AS tabela_origens,
                    comissoes_corretores_lancadas.data as vencimento,
                        case when comissoes.empresarial then
                            (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                            (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                        END AS cliente,
                        case when comissoes.empresarial then
                            (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                            (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
                        END AS codigo_externo,
                        case when comissoes.empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                        else
                            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                        END AS valor,
                    comissoes.id AS comissao FROM comissoes_corretores_lancadas
                    INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                    INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.comissoes_id = comissoes.id
                    INNER JOIN administradoras ON administradoras.id = comissoes.administradora_id
                    INNER JOIN users ON users.id = comissoes.user_id
                    INNER JOIN planos ON planos.id = comissoes.plano_id
                    INNER JOIN tabela_origens ON tabela_origens.id = comissoes.tabela_origens_id
                    WHERE (comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 0 AND comissoes_corretores_lancadas.valor != 0)
                    or (comissoes_corretora_lancadas.status_financeiro = 1 AND comissoes_corretora_lancadas.status_gerente = 0 AND comissoes_corretora_lancadas.valor != 0)
                    GROUP BY comissao

                ');
            });
            return response()->json($resultado);

        }










        return [];
    }

    public function listarcontratos()
    {
        $dados = DB::select(
            "
            SELECT
            (SELECT nome FROM administradoras WHERE administradoras.id = contratos.administradora_id) AS administradora,
            (SELECT NAME FROM users WHERE users.id = clientes.user_id) AS corretor,
            clientes.nome AS cliente,
            (contratos.codigo_externo) AS codigo_externo,
            (SELECT nome FROM planos WHERE planos.id = contratos.plano_id) AS plano,
            (contratos.valor_plano) AS valor,
            (contratos.created_at) AS data_contrato,
            (SELECT nome FROM tabela_origens WHERE tabela_origens.id = contratos.tabela_origens_id) AS origem,
            (contratos.id) AS detalhe
            FROM clientes
            INNER JOIN contratos ON contratos.cliente_id = clientes.id
            "
        );
        return $dados;
    }

    public function listarcontratosDetalhe($id)
    {
        $contrato = Contrato::where("id",$id)
            ->with(['comissao','comissao.comissoesLancadasCorretora','comissao.comissoesLancadas','clientes','clientes.user'])
            ->first();
        return view('admin.pages.gerente.contrato',[
            "dados" => $contrato
        ]);
    }



    public function listarComissao($id)
    {
        $user = User::find($id);
       $comissao_valor = DB::select(
            "
                SELECT
                SUM(valor) as total
                FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
                comissoes_corretores_lancadas.status_gerente = 1 AND comissoes_corretores_lancadas.status_comissao = 0 AND
                MONTH(comissoes_corretores_lancadas.data) = MONTH(NOW()) AND
                comissoes.user_id = $id
            "
       );

       $ids_confirmados = ComissoesCorretoresLancadas::where("status_financeiro",1)->where("status_apto_pagar",1)->selectRaw("GROUP_CONCAT(id) as ids")->first()->ids;

       $total_individual = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query) use($id){
                $query->where("plano_id",1);
                $query->where("user_id",$id);
            })->selectRaw("if(sum(valor)>0,sum(valor),0) as total_individual")->first()->total_individual;

        $total_coletivo = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->whereHas('comissao',function($query)use($id){
                $query->where("plano_id",3);
                $query->where("user_id",$id);
            })->selectRaw("if(sum(valor)>0,sum(valor),0) as total_coletivo")->first()->total_coletivo;


        $total_individual_quantidade = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        ->where("status_apto_pagar",1)
        ->whereHas('comissao',function($query) use($id){
            $query->where("plano_id",1);
            $query->where("user_id",$id);
        })->count();

        $total_coletivo_quantidade = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        ->where("status_apto_pagar",1)
        ->whereHas('comissao',function($query)use($id){
            $query->where("plano_id",3);
            $query->where("user_id",$id);
        })->count();

        $total_a_pagar = $total_individual + $total_coletivo;

        // $dados = DB::select("
        //     SELECT
        //     comissoes_id,
        //     (SELECT administradora_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id) AS administradora,
        //     (SELECT nome FROM administradoras WHERE administradoras.id = (SELECT administradora_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS nome_administradora,
        //     parcela,data,valor

        //     FROM comissoes_corretores_lancadas
        //     WHERE status_financeiro = 1 AND status_gerente = 1 ORDER BY nome_administradora,parcela
        // ");

        // $inicial = $dados[0]->nome_administradora;

        return view('admin.pages.gerente.comissao',[
            "usuario" => $user->name,
            "id" => $user->id,
            "total_comissao" => $comissao_valor[0]->total,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_a_pagar" => $total_a_pagar,
            "ids_confirmados" => $ids_confirmados
        ]);



    }

    public function mudarStatusParaNaoPago(Request $request)
    {

        $ca = ComissoesCorretoresLancadas::where("id",$request->id)->first();
        $ca->status_apto_pagar = 0;
        $ca->status_comissao = 0;
        $ca->finalizado = 0;
        $ca->data_baixa_finalizado  = null;
        $ca->data_antecipacao = null;
        $ca->save();

        $va = ValoresCorretoresLancados
            ::where("user_id",$request->user_id)
            ->whereMonth("data",$request->mes)
            ->whereYear("data",$request->ano)
            ->first();




        if($va) {
            $va->valor_total = str_replace([".",","],["","."], $request->total);
            $va->valor_desconto = str_replace([".",","],["","."], $request->desconto);
            $va->valor_premiacao = str_replace([".",","],["","."], $request->premiacao);
            $va->valor_comissao = str_replace([".",","],["","."],$request->comissao);
            $va->valor_salario = str_replace([".",","],["","."],$request->salario);
            $va->save();
        }

        if($va->valor_total == 0) {
            $user_name = User::find($request->user_id)->name;
            $del = ValoresCorretoresLancados
                ::where("user_id",$request->user_id)
                ->whereYear("data",$request->ano)
                ->whereMonth("data",$request->mes);
            $del->delete();
            return [
                "resposta" => "deletar",
                "user_id" => $request->user_id,
                "name" => $user_name
            ];



        }

        return [
            "resposta" => "sucesso"
        ];



    }

    public function aptarPagamento(Request $request)
    {

        $id = $request->id;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $ano = $request->ano;
        $data_comissao = date($ano."-".$mes."-01");

        $co = ComissoesCorretoresLancadas::where("id",$id)->first();

        $co->status_apto_pagar = 1;
        $co->status_comissao = 1;
        $co->finalizado = 1;
        //$co->desconto = $request->desconto;
        $co->data_baixa_finalizado = $data_comissao;
        $co->save();

        $va = ValoresCorretoresLancados::where("user_id",$user_id)->whereMonth('data',$request->mes)->whereYear('data',$request->ano);
        if($va->count() == 0) {

            $va = new ValoresCorretoresLancados();
            $va->user_id = $user_id;
            $va->valor_comissao = str_replace([".",","],["","."], $request->comissao);
            $va->valor_salario = str_replace([".",","],["","."], $request->salario);
            $va->valor_premiacao = str_replace([".",","],["","."], $request->premiacao);
            $va->valor_total = str_replace([".",","],["","."], $request->total);
            $va->valor_desconto = $request->desconto;
            $va->data = $data_comissao;
            $va->valor_adiantamento = str_replace([".",","],["","."], $request->adiantamento);
            $va->valor_estorno = str_replace([".",","],["","."], $request->estorno);
            $va->save();

            $id_folha_mes = FolhaMes::whereMonth("mes",$mes)->whereYear('mes',$ano)->first()->id;

            $folha = new FolhaPagamento();
            $folha->folha_mes_id = $id_folha_mes;
            $folha->valores_corretores_lancados_id = $va->id;
            $folha->save();

        } else {

            $alt = $va->first();
            $alt->valor_comissao = str_replace([".",","],["","."], $request->comissao);
            $alt->valor_salario = str_replace([".",","],["","."], $request->salario);
            $alt->valor_premiacao = str_replace([".",","],["","."], $request->premiacao);
            $alt->valor_total = str_replace([".",","],["","."], $request->total);
            $alt->valor_desconto = $request->desconto;
            $alt->valor_estorno = str_replace([".",","],["","."], $request->estorno);
            $alt->valor_adiantamento = str_replace([".",","],["","."], $request->adiantamento);
            $alt->save();

        }

        return $request->all();

    }

    public function comissaoListagemConfirmadasMesEspecifico(Request $request)
    {

        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->id;




        $valores = ValoresCorretoresLancados::whereMonth('data',$mes)->whereYear('data',$ano)->where("user_id",$id);


        $salario = 0;
        $premiacao = 0;
        $comissao = 0;
        $desconto = 0;
        $total = 0;
        $estorno = 0;
        $adiantamento = 0;


        if($valores->count() != 0) {

            $dados = $valores->first();

            $total = number_format($dados->valor_total,2,",",".");
            $salario = number_format($dados->valor_salario,2,",",".");
            $premiacao = number_format($dados->valor_premiacao,2,",",".");
            $comissao = number_format($dados->valor_comissao,2,",",".");
            $desconto = number_format($dados->valor_desconto,2,",",".");
            $estorno = number_format($dados->valor_estorno,2,",",".");
            $adiantamento = number_format($dados->valor_adiantamento,2,",",".");


        }

        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao',function($query) use($id){

                $query->where("user_id",$id);
            })
            ->count();





        $total_individual = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
            SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano}
            ) AS plano1,
            (
            SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 1 AND comissoes.user_id = {$id} AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
            AND year(data_baixa_finalizado) = {$ano}
            ) AS plano3;
        ")[0]->total_individual_valor;




        $ids_confirmados = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            //->where("finalizado",1)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereYear("data_baixa_finalizado",$ano)
            ->whereHas('comissao.user',function($query) use($id){
                $query->where("id",$id);
                $query->where("plano_id",1);
            })
            ->selectRaw("GROUP_CONCAT(id) as ids")
            ->first()
            ->ids;

        $valor_individual_a_receber = DB::select("
            SELECT
            COUNT(*) AS total
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
            comissoes_corretores_lancadas.status_gerente = 0 AND
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND comissoes.plano_id = 1
        ")[0]->total;




        return [
            "valor_individual_a_receber" => $valor_individual_a_receber,

            "total_individual_quantidade" => $total_individual_quantidade,


            "total_individual" => number_format($total_individual,2,",","."),

            "total_comissao" =>  $comissao,
            "id_confirmados" => $ids_confirmados,
            "salario" => $salario,
            "comissao" => $comissao,
            "premiacao" => $premiacao,
            "desconto" => $desconto,
            "total" => $total,
            "estorno" => $estorno,
            "adiantamento" => $adiantamento
        ];
    }


    public function estornoIndividual(Request $request)
    {
        $id = $request->id;
        $contratos = DB::select("
            select
    (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
    date_format((comissoes_corretores_lancadas.data),'%d/%m/%Y') as data,
    (contratos.codigo_externo) as codigo,
    (select nome from clientes where clientes.id = contratos.cliente_id) as cliente,
    (comissoes_corretores_lancadas.parcela) as parcela,
    (contratos.valor_plano) as valor,
    (comissoes_corretores_lancadas.valor) as total_estorno,
    contratos.id,
    comissoes.id as comissoes_id,
    comissoes.plano_id as plano,
    comissoes_corretores_lancadas.id as id_lancadas,
    cancelados
 from comissoes_corretores_lancadas
inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
inner join contratos on contratos.id = comissoes.contrato_id
where
comissoes.plano_id = 1
and comissoes_corretores_lancadas.valor != 0
and comissoes_corretores_lancadas.estorno = 0
and comissoes_corretores_lancadas.cancelados = 0
and comissoes_corretores_lancadas.data_baixa_estorno IS NULL
  and contratos.financeiro_id = 12
  and
    exists (select * from `clientes` where `contratos`.`cliente_id` = `clientes`.`id` and `user_id` = ${id});
        ");
        return response()->json($contratos);
    }













    public function comissaoListagemConfirmadasMesFechado(Request $request)
    {
        $mes = $request->mes;
        $plano = $request->plano;

        if($plano != 0) {
            $dados = DB::select("
SELECT
    (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,

    comissoes_corretores_lancadas.valor AS valor,

    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id

FROM comissoes_corretores_lancadas
         INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
         INNER JOIN contratos ON comissoes.contrato_id = contratos.id
WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        MONTH(data_baixa_finalizado) = {$mes} AND comissoes.plano_id = {$plano}
ORDER BY comissoes.administradora_id
        ");
        } else {
            $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y') as created_at,
        contrato_empresarial.codigo_externo as codigo,
        (contrato_empresarial.razao_social) as cliente,
        comissoes_corretores_lancadas.parcela,
        (contrato_empresarial.valor_plano) as valor_plano,
        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
           if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,

    (comissoes.plano_id) AS plano,
    (quantidade_vidas) AS quantidade_vidas,
    CASE
        WHEN contrato_empresarial.desconto_corretor IS NOT NULL THEN contrato_empresarial.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
        END AS desconto,
    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contrato_empresarial.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1
        AND month(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");
        }



        return $dados;


    }




    public function comissaoListagemConfirmadas(Request $request)
    {
        
        if($request->mes) {
            $id = $request->id;
            $mes = $request->mes;
            $ano = $request->ano;
            $dados = DB::select("
SELECT
    (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
                    WHEN comissoes_corretores_lancadas.porcentagem_paga != '' THEN comissoes_corretores_lancadas.porcentagem_paga
                    WHEN (
                        SELECT COUNT(*)
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    ) > 0 THEN (
                        SELECT valor
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    )
                    ELSE (
                        SELECT valor
                        FROM comissoes_corretores_default
                        WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                    )
                    END AS porcentagem,
    /*if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,*/
        (comissoes_corretores_lancadas.valor) as valor,
    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
    END AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");
        } else {
            $id = $request->id;
            $dados = DB::select("
        SELECT
    (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as created_at,
    contratos.codigo_externo as codigo,
    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))) as cliente,
    comissoes_corretores_lancadas.parcela,
    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id) as valor_plano,
    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS vencimento,
    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y') as data_baixa,
    CASE
                    WHEN comissoes_corretores_lancadas.porcentagem_paga != '' THEN comissoes_corretores_lancadas.porcentagem_paga
                    WHEN (
                        SELECT COUNT(*)
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    ) > 0 THEN (
                        SELECT valor
                        FROM comissoes_corretores_configuracoes
                        WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                    )
                    ELSE (
                        SELECT valor
                        FROM comissoes_corretores_default
                        WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                    )
                    END AS porcentagem,
    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS valor,

    (comissoes.plano_id) AS plano,
    (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
    CASE
        WHEN contratos.desconto_corretor IS NOT NULL THEN contratos.desconto_corretor
        ELSE comissoes_corretores_lancadas.desconto
    END AS desconto,


    comissoes_corretores_lancadas.id,
    comissoes_corretores_lancadas.comissoes_id,
    contratos.id as contrato_id
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND plano_id = 1 AND comissoes_corretores_lancadas.finalizado != 1
        ORDER BY comissoes.administradora_id
        ");
        }
        return $dados;
    }




    public function gerenteBuscarHistorico(Request $request)
    {
        $mes = $request->mes;
        $dados = DB::select("
            select
                (administradoras.nome) as administradora,
                case when comissoes.empresarial = 1 then
                    DATE_FORMAT(contrato_empresarial.created_at,'%d/%m/%Y')
                else
                    DATE_FORMAT(contratos.created_at,'%d/%m/%Y')
                end as data,
                case when comissoes.empresarial = 1 then
                    contrato_empresarial.codigo_externo
                else
                   contratos.codigo_externo
                end as codigo_externo,
                case when comissoes.empresarial = 1 then
                    contrato_empresarial.razao_social
                else
                    (select nome from clientes where clientes.id = contratos.cliente_id)
                end as cliente,
    comissoes_corretores_lancadas.parcela,
    users.name as corretor,
    comissoes_corretores_lancadas.valor,
    case when comissoes.empresarial = 1 then
        contrato_empresarial.valor_plano
    else
        contratos.valor_plano
    end as valor_plano,

    if(
                (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
        ,
                (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND

                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
        ) AS porcentagem,
        planos.nome as plano,
        comissoes_corretores_lancadas.data_baixa_finalizado
from comissoes_corretores_lancadas
         inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
         inner join administradoras on administradoras.id = comissoes.administradora_id
         inner join planos on planos.id = comissoes.plano_id
         inner join users on users.id = comissoes.user_id
         left join contratos on contratos.id = comissoes.contrato_id
         left join contrato_empresarial on contrato_empresarial.id = comissoes.contrato_empresarial_id
where comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND month(data_baixa_finalizado) = {$mes} and valor != 0
        ");


        $total_empresarial_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id","!=",1);
                $query->where("plano_id","!=",3);
            })->count();

        $total_empresarial = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_empresarial_valor FROM (
                SELECT
                    COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                        AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano1,
                (
                SELECT
                    COALESCE(SUM(CASE WHEN comissoes_corretores_lancadas.valor_pago THEN valor_pago ELSE valor END), 0)
                        AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id != 3 AND comissoes.plano_id != 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
                ) AS plano3
            ")[0]->total_empresarial_valor;




        $total_individual_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",1);
            })->count();


        $total_coletivo_quantidade = ComissoesCorretoresLancadas
            ::where("status_financeiro",1)
            ->where("status_apto_pagar",1)
            ->where("finalizado","=",1)
            ->where("valor","!=",0)
            ->whereMonth("data_baixa_finalizado",$mes)
            ->whereHas('comissao',function($query){
                $query->where("plano_id",3);
            })->count();

        $total_individual = DB::select("
                SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_individual_valor FROM (
                SELECT SUM(valor) AS total_plano1 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano1,
                (
                SELECT SUM(valor) AS total_plano3 FROM comissoes_corretores_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                WHERE comissoes.plano_id = 1 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_finalizado) = {$mes}
                ) AS plano3;
            ")[0]->total_individual_valor;




        $total_coletivo = DB::select("
            SELECT IFNULL(total_plano1, 0) - IFNULL(total_plano3, 0) AS total_coletivo_valor FROM (
            SELECT
                COALESCE(SUM(comissoes_corretores_lancadas.valor), 0)
                    AS total_plano1
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar = 1 and month(data_baixa_finalizado) = {$mes}
            ) AS plano1,
            (
            SELECT
                COALESCE(SUM(comissoes_corretores_lancadas.valor), 0)
                AS total_plano3
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            WHERE comissoes.plano_id = 3 AND comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes}
            ) AS plano3
        ")[0]->total_coletivo_valor;



        $valores = DB::select("select
            FORMAT(sum(valor_comissao),2,'de_DE') as comissao,
            FORMAT(sum(valor_salario),2,'de_DE') as salario,
            FORMAT(sum(valor_premiacao),2,'de_DE') as premiacao,
            FORMAT(sum(valor_total),2,'de_DE') as total,
            FORMAT(sum(valor_desconto),2,'de_DE') as desconto,
            FORMAT(sum(valor_estorno),2,'de_DE') as estorno
            from valores_corretores_lancados where month(data) = {$mes}");


        return [
            "valores" => $valores[0],
            "data" => $dados,
            "total_empresarial_quantidade" => $total_empresarial_quantidade,
            "total_individual_quantidade" => $total_individual_quantidade,
            "total_coletivo_quantidade" => $total_coletivo_quantidade,
            "total_empresarial" => $total_empresarial,
            "total_individual" => $total_individual,
            "total_coletivo" => $total_coletivo
        ];
    }


    public function salarioUserHistorico(Request $request)
    {
        $user = $request->user;

        $user = User::where('name', 'like', '%' . $user . '%')->first()->id;
        $dados = ValoresCorretoresLancados::where("user_id",$user)->whereMonth("data",$request->mes)->first();


        return response()->json($dados);
    }







    public function gerenteChangeValorPlano(Request $request)
    {

        $valor = str_replace([".",","],["","."], $request->valor);//110
        $id = $request->id;
        $porcentagem = $request->porcentagem;//50





        $contrato = Contrato::where('id',Comissoes::where("id",ComissoesCorretoresLancadas::find($id)->comissoes_id)->first()->contrato_id)->first();

        $contrato->valor_plano = $valor;
        $contrato->save();

        $comissa_lancada = ComissoesCorretoresLancadas::where("id",$id)->first();





//        $comissa_lancada->valor = ($porcentagem / 100) * $valor;
//        $comissa_lancada->save();

        return $comissa_lancada;

    }



    public function comissaoMesAtual(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
        comissoes_corretores_lancadas.created_at AS data_criacao,
        contratos.codigo_externo AS orcamento,
        (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
        (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,
                       case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y') AS data_baixa_gerente,

                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,

                     case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT desconto_corretor FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS desconto,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela,
                    if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS porcentagem_parcela_corretor,

                if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT id FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT id FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS id_porcentagem_parcela_corretor,
                    porcentagem_paga,
                    contratos.id as contrato_id

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        (comissoes_corretores_lancadas.status_financeiro = 1 AND (comissoes_corretores_lancadas.status_gerente = 1 OR comissoes_corretores_lancadas.status_apto_pagar = 1)) AND
        comissoes.user_id = {$id}  AND valor != 0 AND status_comissao = 0 AND contratos.plano_id = 1 AND comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");

        return $dados;
    }

    public function zerarTabelas()
    {
        return [];
    }












    public function comissaoMesDiferente(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
                SELECT
                comissoes_corretores_lancadas.id,
                comissoes_corretores_lancadas.parcela,
                contratos.created_at AS data_criacao,
                contratos.codigo_externo AS orcamento,
                DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                comissoes_corretores_lancadas.valor,
                (SELECT if(quantidade_vidas >=1,quantidade_vidas,0) FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
                comissoes_corretores_lancadas.data_baixa as data_baixa,
                (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,

                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS valor_plano_contratado,



               CASE
                WHEN comissoes_corretores_lancadas.porcentagem_paga THEN
                    comissoes_corretores_lancadas.porcentagem_paga
                ELSE
                    CASE
                        WHEN (
                                 SELECT COUNT(*) FROM comissoes_corretores_configuracoes
                                 WHERE
                                         comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                                         comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                                         comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                                         comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                                         comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                             ) > 0 THEN
                            (
                                SELECT valor FROM comissoes_corretores_configuracoes
                                WHERE
                                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela
                            )
                        ELSE
                            (
                                SELECT valor FROM comissoes_corretores_default
                                WHERE
                                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela
                            )
                        END
                END AS porcentagem_parcela_corretor,

                case when empresarial then
   				    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
   	            ELSE
				    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                END AS cliente,

                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT desconto_corretor FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS desconto,
                (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
                contratos.id as contrato_id

                FROM comissoes_corretores_lancadas

                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN contratos ON comissoes.contrato_id = contratos.id

                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND
                comissoes_corretores_lancadas.status_gerente = 0 AND
                comissoes_corretores_lancadas.status_apto_pagar != 1 AND
                comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND contratos.plano_id = 1
                ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }



    public function aplicarDescontoCorretor(Request $request)
    {
        $id = $request->id;
        $desconto = $request->desconto;
        $ca = ComissoesCorretoresLancadas::where("id",$id)->first();
        $ca->desconto = $desconto;
        $ca->save();
        return true;
    }


    public function criarPdfPagamento()
    {
       $dados = Administradoras::with(['comissao','comissao.comissoesLancadasCorretoraQuantidade'])->get();
       $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/logo-certa.jpg")));
       return view('admin.pages.gerente.pdf',[
            "dados" => $dados,
            "logo" => $logo
       ]);
    }

    public function finalizarPagamento(Request $request)
    {
        $mes = $request->mes;
        $ano = date("Y");
        $dia = date("d");
        $data = date($ano."-".$mes."-01");
        $data_comissao = date($ano."-".$mes."-01");
        $idFolhaMes = FolhaMes::whereMonth("mes",$mes)->first()->id;
        $ids = explode(",",$request->id);
        DB::table('comissoes_corretores_lancadas')
            ->whereIn('id', $ids)
            ->update(['status_comissao'=>1,"data_baixa_finalizado"=>$data_comissao]);
        $comissao = str_replace([".",","],["","."], $request->comissao);
        $salario = str_replace([".",","],["","."],$request->salario);
        $premiacao = str_replace([".",","],["","."],$request->premiacao);
        $desconto = str_replace([".",","],["","."],$request->desconto);
        $estorno = str_replace([".",","],["","."],$request->estorno);
        $total = str_replace([".",","],["","."],$request->total);
        $existe_valores_lancados = ValoresCorretoresLancados::whereMonth("data",$mes)->where("user_id",$request->user_id);
        if($existe_valores_lancados->count() > 0) {
            $valores_corretores_lancados = $existe_valores_lancados->first();
            $valores_corretores_lancados->valor_comissao = $comissao;
            $valores_corretores_lancados->valor_salario = $salario;
            $valores_corretores_lancados->valor_premiacao = $premiacao;
            $valores_corretores_lancados->valor_desconto = $desconto;
            $valores_corretores_lancados->valor_total = $total;
            $valores_corretores_lancados->valor_estorno = $estorno;
            $valores_corretores_lancados->save();
        } else {
            $valores_corretores_lancados = new ValoresCorretoresLancados();
            $valores_corretores_lancados->user_id = $request->user_id;
            $valores_corretores_lancados->data = $data;
            $valores_corretores_lancados->valor_comissao = $comissao;
            $valores_corretores_lancados->valor_salario = $salario;
            $valores_corretores_lancados->valor_premiacao = $premiacao;
            $valores_corretores_lancados->valor_desconto = $desconto;
            $valores_corretores_lancados->valor_estorno = $estorno;
            $valores_corretores_lancados->valor_total = $total;
            $valores_corretores_lancados->save();
        }


        $folha_existe = FolhaPagamento
            ::where("folha_mes_id",$idFolhaMes)
            ->where("valores_corretores_lancados_id",$valores_corretores_lancados->id);
        if($folha_existe->count() == 0) {
            $folhaPagamento = new FolhaPagamento();
            $folhaPagamento->folha_mes_id = $idFolhaMes;
            $folhaPagamento->valores_corretores_lancados_id = $valores_corretores_lancados->id;
            $folhaPagamento->save();
        }




        $users = DB::table('valores_corretores_lancados')
            ->selectRaw("(SELECT NAME FROM users WHERE users.id = valores_corretores_lancados.user_id) AS user,user_id")
            ->selectRaw("valor_total AS total")
            ->whereMonth('data',$mes)
            ->groupBy("user_id")
            ->get();

        $usuarios = DB::table('users')
            ->where('ativo',1)
            ->whereNotIn('id', function($query) use($mes) {
                $query->select('user_id')
                      ->from('valores_corretores_lancados')
                      ->whereMonth('data',$mes);
            })
            ->orderBy("name")
            ->get();


        return [
            'view' => view('admin.pages.gerente.list-users-pdf',[
                "users" => $users
            ])->render(),
            'users_aptos' => $usuarios
        ];




    }

    public function pagamentoMesFinalizado(Request $request)
    {
        $ano = $request->ano;
        $mes = $request->mes;
        $mes = FolhaMes::whereMonth("mes",$mes)->whereYear("mes",$ano)->where("status",0);
        if($mes->count() == 1) {
            $alt = $mes->first();
            $alt->status = 1;
            $alt->save();
            $dados = DB::table("comissoes_corretores_lancadas")
                ->where('status_financeiro', 1)
                ->where('status_apto_pagar',1)
                ->where('status_comissao',1)
                //->get();
                ->update(['finalizado' => 1]);
            return true;
        } else {
            return "sem_mes";
        }
    }

    public function criarPDFUserHistorico(Request $request)
    {
        $mes = $request->mes;
        $id = $request->user_id;
        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];

        $mes_folha = $meses[$mes];
        $user = User::where("id",$request->user_id)->first()->name;
        $dados = ValoresCorretoresLancados::whereMonth("data",$mes)->where("user_id",$request->user_id)->first();
        $comissao = $dados->valor_comissao;
        $salario = $dados->valor_salario;
        $premiacao = $dados->valor_premiacao;

        $total = $dados->valor_total;
        $desconto = $dados->valor_desconto;
        $estorno = $dados->valor_estorno;

        $logo = "";
        if(Corretora::first()->logo) {
            $img_logo = Corretora::first()->logo;
            $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/".$img_logo)));
        }

        $ids = explode("|",$request->ids);

        DB::table("comissoes_corretores_lancadas")->whereIn('id', $ids)->update(['finalizado' => 1]);

        $individual = DB::select("
        SELECT

        (comissoes_corretores_lancadas.data) as created_at,
        (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,

                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao,


                    comissoes_corretores_lancadas.parcela,
                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");

        $coletivo = DB::select("
        SELECT
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,

            case when comissoes.empresarial then
                               (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                               ELSE
                               (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                       END AS cliente,
            (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
            (comissoes_corretores_lancadas.data) as created_at,
                       DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                       if(
                        comissoes_corretores_lancadas.data_baixa_gerente,
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                        DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,
                    comissoes_corretores_lancadas.desconto AS desconto,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao_esperada,
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,

                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id = 3
        ORDER BY comissoes.administradora_id
        ");

        $empresarial = DB::select("
        SELECT
            (SELECT razao_social FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as cliente,
            (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as codigo_externo,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            (SELECT desconto_corretor FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as desconto,
            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id) as valor_plano_contratado,
            comissoes_corretores_lancadas.valor AS comissao,
            comissoes_corretores_lancadas.parcela
            FROM comissoes_corretores_lancadas
            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE
            comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
            comissoes.user_id = {$id} AND month(data_baixa_finalizado) = {$mes} AND valor != 0 AND comissoes.plano_id != 1 AND comissoes.plano_id != 3 ORDER BY comissoes.administradora_id
        ");


        $estorno_table = DB::select(
            "select
            (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from planos where planos.id = comissoes.plano_id) as plano,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} and comissoes.user_id = {$id}"
        );

        $primeiroDia = date('d/m/Y', strtotime($ano . $mes . '-01'));
        $ultimoDia = date('t/m/Y', strtotime($ano . $mes . '-01'));

        $pdf = PDF::loadView('admin.pages.gerente.pdf-folha-historico',[
            "individual" => $individual,
            "coletivo" => $coletivo,
            "empresarial" => $empresarial,
            "meses" => $mes_folha,
            "salario" => $salario,
            "premiacao" => $premiacao,
            "comissao" => $comissao,
            "total" => $total,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user,
            "desconto" => $desconto,
            "estorno" => $estorno,
            "estorno_table" => $estorno_table
        ]);


        $nome = Str::slug($user,"_");
        $mes_folha_nome = Str::slug($mes_folha);


        $nome_pdf = "folha_" . mb_convert_case($nome, MB_CASE_LOWER, "UTF-8") . "_" . $mes_folha_nome . "_" . date('d') . "_" . date('m') . "_" . date('s') . ".pdf";
        $response = $pdf->stream($nome_pdf, ['Attachment' => false]);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $nome_pdf . '"');
        return $response;



    }








    public function criarPDFUser(Request $request)
    {
        $mes = $request->mes;
        $ano = $request->ano;
        $id = $request->user_id;
        
        $meses = [
            '01'=>"Janeiro",
            '02'=>"Fevereiro",
            '03'=>"Março",
            '04'=>"Abril",
            '05'=>"Maio",
            '06'=>"Junho",
            '07'=>"Julho",
            '08'=>"Agosto",
            '09'=>"Setembro",
            '10'=>"Outubro",
            '11'=>"Novembro",
            '12'=>"Dezembro"
        ];
        $mes_folha = $meses[$mes];
        $user = User::where("id",$request->user_id)->first()->name;
        $dados = ValoresCorretoresLancados::whereMonth("data",$mes)->whereYear("data",$ano)->where("user_id",$request->user_id)->first();
        
        $comissao = $dados->valor_comissao;
        $salario = $dados->valor_salario;
        $adiantamento = $dados->valor_adiantamento;
        $premiacao = $dados->valor_premiacao;
        $total = $dados->valor_total;
        $desconto = $dados->valor_desconto;
        $logo = "";
        $ids = explode("|",$request->ids);
        DB::table("comissoes_corretores_lancadas")->whereIn('id', $ids)->update(['finalizado' => 1]);
        $individual = DB::select("
        SELECT
        (comissoes_corretores_lancadas.data) as created_at,
        (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) as codigo_externo,
        (select quantidade_vidas from clientes where clientes.id = contratos.cliente_id) as quantidade_vidas,
            case when comissoes.empresarial then
                    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                    ELSE
                    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                    END AS cliente,
                    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
                    if(
                    comissoes_corretores_lancadas.data_baixa_gerente,
                    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa_gerente,'%d/%m/%Y'),
                    DATE_FORMAT(comissoes_corretores_lancadas.data_baixa,'%d/%m/%Y')
                    ) AS data_baixa_gerente,
                       case when empresarial then
                            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                      (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                    END AS valor_plano_contratado,
                       comissoes_corretores_lancadas.valor AS comissao,


                    comissoes_corretores_lancadas.parcela,
                    case when empresarial then
                    (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
              else
                    COALESCE((SELECT FORMAT(desconto_corretor, 2) FROM contratos WHERE contratos.id = comissoes.contrato_id AND comissoes_corretores_lancadas.id = (
                            SELECT cc.id
                    FROM comissoes_corretores_lancadas cc
                    WHERE cc.comissoes_id = comissoes.id
                            AND cc.valor != 0
                    ORDER BY cc.id
                    LIMIT 1
                        )),0.00)
                    END AS desconto

        FROM comissoes_corretores_lancadas
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND
        comissoes.user_id = {$id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 1
        ORDER BY comissoes.administradora_id
        ");




        $estorno_table = DB::select(
            "select
            (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from planos where planos.id = comissoes.plano_id) as plano,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(data_baixa_estorno) = {$mes} and year(data_baixa_estorno) = {$ano} and comissoes.user_id = {$id}"
        );

        $primeiroDia = date('d/m/Y', strtotime("{$ano}-" . $mes . '-01'));
        $ultimoDia = date('t/m/Y', strtotime("{$ano}-" . $mes . '-01'));



        $estorno = 0;
        if($estorno_table) {
            $estorno = $dados->valor_estorno;
        }

        $total_individual = DB::select("SELECT SUM((comissoes_corretores_lancadas.valor)) AS total FROM comissoes_corretores_lancadas INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND comissoes.user_id = ${id} AND MONTH(data_baixa_finalizado) = {$mes} AND YEAR(data_baixa_finalizado) = {$ano} AND valor != 0 AND comissoes.plano_id = 1")[0]->total;
        $total_coletivo = 0;
        $total_empresarial = 0;

        //$total =   floatval($total_individual) + floatval($total_coletivo) +  floatval($total_empresarial);

        $total_coletivo_desconto = 0;
        $total_individual_desconto = 0;
        $total_empresarial_desconto = 0;

        $total_desconto = $total_individual_desconto;

        $comissao = floatval($total_individual);
        $desconto = 0;
        //$total = $comissao - $desconto;





        $pdf = PDF::loadView('admin.pages.gerente.pdf-folha',[
            "individual" => $individual,
            "meses" => $mes_folha,
            "ano" => $ano,
            "salario" => $salario,
            "premiacao" => $premiacao,
            "comissao" => $comissao,
            "total" => $dados->valor_total,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user,
            "desconto" => $desconto,
            "adiantamento" => $adiantamento,
            "estorno" => $estorno,
            "estorno_table" => $estorno_table,
            "tipo" => $request->tipo,
            "boolean_individual" => true,
            "boolean_coletivo" => false,
            "boolean_empresarial" => false
        ]);


        $nome = Str::slug($user,"_");
        $mes_folha_nome = Str::slug($mes_folha);


        $nome_pdf = "folha_" . mb_convert_case($nome, MB_CASE_LOWER, "UTF-8") . "_" . $mes_folha_nome . "_" . date('d') . "_" . date('m') . "_" . date('s') . ".pdf";
        $response = $pdf->stream($nome_pdf, ['Attachment' => false]);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $nome_pdf . '"');
        return $response;

    }


    public function geralEstornoMes(Request $request)
    {
        $mes = $request->mes;

        $estorno = DB::select(
            "select
            (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from planos where planos.id = comissoes.plano_id) as plano,
            date_format(comissoes_corretores_lancadas.data,'%d/%m/%Y') as data,
            (comissoes_corretores_lancadas.id) as id_lancadas,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes.id) as id,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and month(comissoes_corretores_lancadas.data_baixa_estorno) = {$mes}"

        );

        return response()->json($estorno);




    }






    public function geralEstorno(Request $request)
    {
        $id = $request->id;
        $mes = $request->mes;

        $estorno = DB::select(
            "select
            (select nome from administradoras where administradoras.id = comissoes.administradora_id) as administradora,
            case when comissoes.empresarial then
                (select razao_social from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (select nome from clientes where clientes.id = (select cliente_id from contratos where contratos.id = comissoes.contrato_id))
            end as cliente,
            (select SUBSTRING_INDEX(nome,' ',1) from planos where planos.id = comissoes.plano_id) as plano,
            date_format(comissoes_corretores_lancadas.data,'%d/%m/%Y') as data,
            (comissoes_corretores_lancadas.id) as id_lancadas,
            case when comissoes.empresarial then
                (select valor_plano from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select valor_plano from contratos where contratos.id = comissoes.contrato_id)
            end as valor,
            (comissoes_corretores_lancadas.valor) as total_estorno,
            case when comissoes.empresarial then
                (select codigo_externo from contrato_empresarial where contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (select codigo_externo from contratos where contratos.id = comissoes.contrato_id)
            end as contrato,
            (comissoes.id) as id,
            (comissoes_corretores_lancadas.parcela) as parcela
            from comissoes_corretores_lancadas inner join comissoes on comissoes.id = comissoes_corretores_lancadas.comissoes_id
            where comissoes_corretores_lancadas.estorno = 1 and comissoes.user_id = {$id}"

        );

        return response()->json($estorno);

    }


    public function estornoVoltar(Request $request)
    {
        $valor_estorno = 0;
        $valor_total = 0;
        $user_id = $request->user_id;
        $mes = $request->mes;
        $id = $request->id;

        $valor = str_replace([".",","],["","."],$request->valor);
        $va = ValoresCorretoresLancados::where("user_id",$user_id)->whereMonth("data",$mes)->first();

        $valor_estorno = $va->valor_estorno - $valor;
        $valor_total = $va->valor_total + $valor;

        $va->valor_estorno = $valor_estorno;
        $va->valor_total = $valor_total;



        $va->save();
        $co = ComissoesCorretoresLancadas::where("id",$id)->first();
        $co->data_baixa_estorno = null;
        $co->estorno = 0;
        $co->save();

        return [
            "valor_estorno" => number_format($valor_estorno,2,",","."),
            "valor_total" => number_format($valor_total,2,",",".")
        ];
    }






    public function listagemRecebido()
    {
        $dados = DB::select(
            "
            SELECT
                (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
                (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
                (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
                case when empresarial then
                    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                    else
                    (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                END AS cliente,
                    (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,
                case when empresarial then
                    (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id) END AS codigo_externo,
                parcela,
                valor,
                data_baixa,
                comissoes_corretora_lancadas.data as vencimento,
                comissoes.id
                FROM comissoes_corretora_lancadas
                INNER JOIN comissoes ON comissoes.id = comissoes_corretora_lancadas.comissoes_id
                WHERE status_financeiro = 1 AND status_gerente = 1
            "
            );

        return $dados;
    }

    public function detalhePagos($id)
    {
        $comissao = Comissoes::find($id);
        $dados = DB::select("
            SELECT
            comissoes_corretores_lancadas.parcela,
            comissoes_corretores_lancadas.id AS id_corretor_comissao,
            comissoes_corretora_lancadas.id AS id_corretora,
            (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS nome_corretor,
            (SELECT id FROM users WHERE users.id = comissoes.user_id) AS id_corretor,
            if(comissoes_corretora_lancadas.valor_pago,comissoes_corretora_lancadas.valor_pago,0) AS valor_pago,
            if(comissoes_corretora_lancadas.porcentagem_paga,comissoes_corretora_lancadas.porcentagem_paga,0) AS porcentagem_paga,
            case when empresarial then
                (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
           END AS codigo_externo,
            comissoes_corretores_lancadas.data AS vencimento,
            case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor_plano_contratado,
            comissoes_corretores_lancadas.data_baixa AS data_baixa,
                (SELECT valor FROM comissoes_corretora_configuracoes  WHERE  plano_id = comissoes.plano_id AND  administradora_id = comissoes.administradora_id AND
                tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora,
                (SELECT id FROM comissoes_corretora_configuracoes WHERE  plano_id = comissoes.plano_id AND administradora_id = comissoes.administradora_id AND
                tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora_id,
                comissoes_corretora_lancadas.valor AS comissao_valor_corretora,
                if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,0) as comissao_valor_pago_corretor,
                if(comissoes_corretores_lancadas.porcentagem_paga,comissoes_corretores_lancadas.porcentagem_paga,0) as comissao_porcentagem_pago_corretor,
                comissoes_corretores_lancadas.valor AS comissao_valor_corretor,
                if(
                        (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                            (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                            ,
                            (SELECT valor FROM comissoes_corretores_default WHERE
                            comissoes_corretores_default.plano_id = comissoes.plano_id AND
                            comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                        )
                    AS porcentagem_parcela_corretores,
                    if(
                            (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                            comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                            comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                            comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                            comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                            comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                                (SELECT id FROM comissoes_corretores_configuracoes WHERE
                                comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                                comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                                comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                                comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                                comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                                ,
                                (SELECT id FROM comissoes_corretores_default WHERE
                                comissoes_corretores_default.plano_id = comissoes.plano_id AND
                                comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                                comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                                comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                            )
                            AS porcentagem_parcela_corretor_id,
                            case when empresarial then
                                (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                                (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                            END AS cliente,
                            case when empresarial then
                                (SELECT cnpj FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                            else
                                (SELECT cpf FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
                            END AS cliente_cpf
                            FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.parcela = comissoes_corretores_lancadas.parcela
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            WHERE comissoes_corretores_lancadas.comissoes_id = $id AND comissoes_corretora_lancadas.comissoes_id = $id AND comissoes_corretores_lancadas.status_financeiro = 1 AND
                            comissoes_corretores_lancadas.status_gerente = 1
                            AND
                                (comissoes_corretores_lancadas.valor != 0 OR comissoes_corretora_lancadas.valor != 0)
                            GROUP BY comissoes_corretores_lancadas.parcela
                    ");

            $desconto_corretora = 0;
            $desconto_corretor = 0;
            $comissao = Comissoes::find($id);
            if($comissao->empresarial == 1) {
                $id = $comissao->contrato_empresarial_id;
                $desconto_corretora = ContratoEmpresarial::find($id)->desconto_corretora;
                $desconto_corretor = ContratoEmpresarial::find($id)->desconto_corretor;
            } else {
                $id = $comissao->contrato_id;
                $desconto_corretora = Contrato::find($id)->desconto_corretora;
                $desconto_corretor = Contrato::find($id)->desconto_corretor;
            }

            return view('admin.pages.gerente.detalhe-pagos',[
                'dados' => $dados,
                "cliente" => isset($dados[0]->cliente) && !empty($dados[0]->cliente) ? $dados[0]->cliente : "",
                "cpf" => isset($dados[0]->cliente_cpf) && !empty($dados[0]->cliente_cpf) ? $dados[0]->cliente_cpf : "",
                "valor_plano" => isset($dados[0]->valor_plano_contratado) && !empty($dados[0]->valor_plano_contratado) ? $dados[0]->valor_plano_contratado : "",
                "valor_corretora" => isset($dados[0]->comissao_valor_corretora) && !empty($dados[0]->comissao_valor_corretora) ? $dados[0]->comissao_valor_corretora : "",
                "desconto_corretora" => $desconto_corretora,
                "desconto_corretor" => $desconto_corretor
            ]);
    }



    public function detalhe($id)
    {
       $dados = DB::select("
       SELECT
       comissoes_corretores_lancadas.parcela,
       comissoes_corretores_lancadas.id AS id_corretor_comissao,
       comissoes_corretora_lancadas.id AS id_corretora,
       (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS nome_corretor,
       (SELECT id FROM users WHERE users.id = comissoes.user_id) AS id_corretor,
       if(comissoes_corretora_lancadas.valor_pago,comissoes_corretora_lancadas.valor_pago,0) AS valor_pago,
       if(comissoes_corretora_lancadas.porcentagem_paga,comissoes_corretora_lancadas.porcentagem_paga,0) AS porcentagem_paga,
       case when empresarial then
           (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
       else
           (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
           END AS codigo_externo,
    comissoes_corretores_lancadas.data AS vencimento,
    case when empresarial then
(SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
  (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
END AS valor_plano_contratado,
comissoes_corretores_lancadas.data_baixa AS data_baixa,
    (SELECT valor FROM comissoes_corretora_configuracoes  WHERE  plano_id = comissoes.plano_id AND  administradora_id = comissoes.administradora_id AND
     tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora,

    (SELECT id FROM comissoes_corretora_configuracoes WHERE  plano_id = comissoes.plano_id AND administradora_id = comissoes.administradora_id AND
    tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora_id,

    comissoes_corretora_lancadas.valor AS comissao_valor_corretora,

    if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,0) as comissao_valor_pago_corretor,
    if(comissoes_corretores_lancadas.porcentagem_paga,comissoes_corretores_lancadas.porcentagem_paga,0) as comissao_porcentagem_pago_corretor,

        comissoes_corretores_lancadas.valor AS comissao_valor_corretor,

             if(
                    (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                    comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                    comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                    comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                    comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                    comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                        (SELECT valor FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                        ,
                        (SELECT valor FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                    )
                AS porcentagem_parcela_corretores,


              if(
                    (SELECT COUNT(*) FROM comissoes_corretores_configuracoes WHERE
                    comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                    comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                    comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                    comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                    comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela) > 0 ,
                        (SELECT id FROM comissoes_corretores_configuracoes WHERE
                        comissoes_corretores_configuracoes.plano_id = comissoes.plano_id AND
                        comissoes_corretores_configuracoes.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_configuracoes.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_configuracoes.user_id = comissoes.user_id AND
                        comissoes_corretores_configuracoes.parcela = comissoes_corretores_lancadas.parcela)
                        ,
                        (SELECT id FROM comissoes_corretores_default WHERE
                        comissoes_corretores_default.plano_id = comissoes.plano_id AND
                        comissoes_corretores_default.administradora_id = comissoes.administradora_id AND
                        comissoes_corretores_default.tabela_origens_id = comissoes.tabela_origens_id AND
                        comissoes_corretores_default.parcela = comissoes_corretores_lancadas.parcela)
                    )
                    AS porcentagem_parcela_corretor_id,
       case when empresarial then
     (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
     (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
  END AS cliente,
  case when empresarial then
     (SELECT cnpj FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
  else
     (SELECT cpf FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
  END AS cliente_cpf
  FROM comissoes_corretores_lancadas
  INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.parcela = comissoes_corretores_lancadas.parcela
  INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
  WHERE comissoes_corretores_lancadas.comissoes_id = $id AND comissoes_corretora_lancadas.comissoes_id = $id AND comissoes_corretores_lancadas.status_financeiro = 1 AND
  comissoes_corretores_lancadas.status_gerente = 0
  AND
    (comissoes_corretores_lancadas.valor != 0 OR comissoes_corretora_lancadas.valor != 0)
  GROUP BY comissoes_corretores_lancadas.parcela
       ");

        $desconto_corretora = 0;
        $desconto_corretor = 0;
        $comissao = Comissoes::find($id);

        if($comissao->empresarial == 1) {

            $id = $comissao->contrato_empresarial_id;
            $desconto_corretora = ContratoEmpresarial::find($id)->desconto_corretora;
            $desconto_corretor = ContratoEmpresarial::find($id)->desconto_corretor;

        } else {

            $id = $comissao->contrato_id;
            $desconto_corretora = Contrato::find($id)->desconto_corretora;
            $desconto_corretor = Contrato::find($id)->desconto_corretor;

        }

        return view('admin.pages.gerente.detalhe',[
            "dados" => $dados,
            "cliente" => isset($dados[0]->cliente) && !empty($dados[0]->cliente) ? $dados[0]->cliente : "",
            "cpf" => isset($dados[0]->cliente_cpf) && !empty($dados[0]->cliente_cpf) ? $dados[0]->cliente_cpf : "",
            "valor_plano" => isset($dados[0]->valor_plano_contratado) && !empty($dados[0]->valor_plano_contratado) ? $dados[0]->valor_plano_contratado : "",
            "valor_corretora" => isset($dados[0]->comissao_valor_corretora) && !empty($dados[0]->comissao_valor_corretora) ? $dados[0]->comissao_valor_corretora : "",
            "desconto_corretora" => $desconto_corretora,
            "desconto_corretor" => $desconto_corretor


        ]);





    }

    public function mudarComissaoCorretora(Request $request)
    {

        if($request->acao == "porcentagem") {

            $valor_plano = $request->valor_plano;
            $porcentagem = $request->valor;
            $resultado = ($valor_plano * $porcentagem) / 100;
            $id = $request->id;
            $alt = ComissoesCorretoraLancadas::where("id",$id)->first();
            $alt->valor_pago = $resultado;

            if($alt->save()) {
                $conf = ComissoesCorretoraConfiguracoes::where("id",$request->id_configuracao_corretora)->first();
                $conf->valor = $porcentagem;
                if($conf->save()) {
                    return [
                        "valor" => number_format($resultado,2,",","."),
                        "porcentagem" => $porcentagem
                    ];
                } else {

                }
            } else {
                return "error";
            }
        } else {
            $total = $request->valor_plano;
            $valor = str_replace([".",","],["","."],$request->valor);
            $porcentagem = floor(($valor / $total) * 100);
            $id = $request->id;
            $alt = ComissoesCorretoraLancadas::where("id",$id)->first();
            $alt->valor_pago = $valor;
            $alt->porcentagem_paga = $porcentagem;

            if($alt->save()) {
                //$conf = ComissoesCorretoraConfiguracoes::where("id",$request->id_configuracao)->first();

                //if($conf->save()) {
                    //return [

                        return $porcentagem;
                    //];

            } else {
                return "error";
            }


            //return $resultado;
        }






    }


    public function mudarComissaoCorretor(Request $request)
    {

        if($request->acao == "porcentagem") {

            $valor_plano = floatval($request->valor_plano);
            $porcentagem = floatval($request->valor);
            $resultado = ($valor_plano * $porcentagem) / 100;

            $id = $request->id;

            $alt = ComissoesCorretoresLancadas::where("id",$request->default_corretor)->first();
            $alt->valor = $resultado;
            $alt->porcentagem_paga = $request->valor;

            if($alt->save()) {

                    return [
                        "valor" => number_format($resultado,2,",","."),
                        "porcentagem" => $request->valor
                    ];

            } else {
                return "error";
            }



        } else {
            // $id = $request->id;
            // $valor = str_replace([".",","],["","."],$request->valor);

            // $valor_plano = $request->valor_plano;
            // $porcentagem = floor(($valor / $valor_plano) * 100);
            // $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
            // $alt->valor = $valor;

            // $alt->porcentagem_paga = $porcentagem;
            // if($alt->save()) {
            //     return $porcentagem;
            // } else {
            //     return "error";
            // }

        }







    }

    public function mudarComissaoCorretorGerente(Request $request)
    {

        $id = $request->id;
        $valor = str_replace([".",","],["","."],$request->valor);
        $valor_plano =  str_replace(["R$ ",".",","],["","","."],$request->valor_plano);
        $porcentagem = round(($valor / $valor_plano) * 100,2);
        $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
        $alt->valor = $valor;
        $alt->porcentagem_paga = $porcentagem;
        $alt->save();
        return [
            "valor" => number_format($valor,2,",","."),
            "porcentagem" => $porcentagem
        ];

    }

    public function administradoraPagouComissaoPagos(Request $request)
    {
        $corretor = $request->corretor;
        $corretora = $request->corretora;

        $alt_corretor = ComissoesCorretoresLancadas::where("id",$corretor)->first();
        $alt_corretor->status_gerente = 0;
        $alt_corretor->data_baixa_gerente = null;
        $alt_corretor->save();


        $alt_corretora = ComissoesCorretoraLancadas::where("id",$corretora)->first();
        $alt_corretora->status_gerente = 0;
        $alt_corretora->data_baixa_gerente = null;
        $alt_corretora->save();

        return "sucesso";
    }




    public function administradoraPagouComissao(Request $request)
    {
        $corretor = $request->corretor;
        $corretora = $request->corretora;

        $alt_corretor = ComissoesCorretoresLancadas::where("id",$corretor)->first();
        $alt_corretor->status_gerente = 1;
        $alt_corretor->data_baixa_gerente = date('Y-m-d');
        $alt_corretor->save();


        $alt_corretora = ComissoesCorretoraLancadas::where("id",$corretora)->first();
        $alt_corretora->status_gerente = 1;
        $alt_corretora->data_baixa_gerente = date('Y-m-d');
        $alt_corretora->save();

        return "sucesso";
    }





    public function mudarStatus(Request $request)
    {
        $id = $request->id;
        if($request->corretora) {
            $comissao = ComissoesCorretoraLancadas::where("id",$id)->first();
            $comissao->status_gerente = 1;
            if($comissao->save()) {
                return "sucesso";
            } else {
                return "error";
            }
        } else {
            $comissao = ComissoesCorretoresLancadas::where("id",$id)->first();
            $comissao->status_gerente = 1;
            if($comissao->save()) {
                return "sucesso";
            } else {
                return "error";
            }
        }
        //$comissao =





    }
    /*
    public function listarUserComissoesAll()
    {
        $users = DB::select(
            "SELECT id,name,
            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 1)) AS valor_allcare,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 2)) AS valor_alter,

												(SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 3)) AS valor_qualicorp,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id AND comissoes.administradora_id = 4)) AS valor_hapvida,

            (SELECT if(SUM(valor)>0,SUM(valor),0) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1
             AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id)) AS valor,

            (SELECT COUNT(*) FROM comissoes_corretores_lancadas WHERE status_financeiro = 1 AND status_gerente = 1 AND status_comissao = 1
		    						 AND comissoes_id
            IN(SELECT id FROM comissoes WHERE user_id = users.id)) AS status

            FROM users WHERE cargo_id IS NOT NULL"
        );

        return $users;
    }

    */








}
