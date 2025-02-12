<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTimeInterface;
use Carbon\Carbon;

use App\Models\{cidadeCodigoVendedor,
    Contrato,
    Cliente,
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
    ComissoesCorretoresCancelados,
    Dependentes,
    Cancelado,
    MotivoCancelados,
    Premiacoes,
    PremiacoesCorretoraLancadas,
    PremiacoesCorretoresLancadas,
    PremiacoesCorretoraConfiguracoes,
    PremiacoesCorretoresConfiguracoes,
    ComissoesCorretoresDefault};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;



class FinanceiroController extends Controller
{


    public function index(Request $request)
    {
//        $nome = "Iarla Da Silva Rio Branco";
//        $codi = 2040387;
//        $carteirinha = Cliente
//            ::select('clientes.*')
//            ->join('users','users.id','=','clientes.user_id')
//            ->join('contratos','contratos.cliente_id','=','clientes.id')
//            ->where('clientes.nome','like',$nome)
//            ->where('users.codigo_vendedor',$codi)
//            ->whereNull("cateirinha")
//            ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
//            ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
//            ->first();
//
//        dd($carteirinha);


        return view('admin.pages.financeiro.index');
    }

    public function individualPagamentoPrimeiraParcela(Request $request)
    {
        if($request->ajax()) {
            if ($request->mes && $request->id) {
                $mes = $request->mes;
                $id  = $request->id;
                $cacheKey = "individualPagamentoPrimeiraParcelaAll{$mes}{$id}";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($request) {
                    return DB::select("
                        SELECT
                        DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as data,
                        (contratos.codigo_externo) as orcamento,
                        users.name as corretor,
                        clientes.nome as cliente,
                        clientes.cpf as cpf,
                        clientes.quantidade_vidas as quantidade_vidas,
                        (contratos.valor_plano) as valor_plano,
                        contratos.id,
                        estagio_financeiros.nome as parcelas,
                        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento
                    FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                            INNER JOIN clientes ON clientes.id = contratos.cliente_id
                            INNER JOIN users ON users.id = clientes.user_id
                            INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                    WHERE
                        contratos.plano_id = 1 AND financeiro_id = 5 and clientes.cateirinha IS NOT NULL and clientes.user_id = {$request->id}
                        and month(contratos.created_at) = {$request->mes} GROUP BY contratos.id;
                    ");
                });
                return response()->json($resultado);
            } else if ($request->mes && !$request->id) {
                $mes = $request->mes;
                $cacheKey = "individualPagamentoPrimeiraParcelaMes{$mes}";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($mes) {
                    return DB::select("
                    SELECT
                    DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as data,
                    (contratos.codigo_externo) as orcamento,
                    users.name as corretor,
                    clientes.nome as cliente,
                    clientes.cpf as cpf,
                    clientes.quantidade_vidas as quantidade_vidas,
                    (contratos.valor_plano) as valor_plano,
                    contratos.id,
                    estagio_financeiros.nome as parcelas,
                    DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento
                FROM comissoes_corretores_lancadas
                        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                        INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                        INNER JOIN clientes ON clientes.id = contratos.cliente_id
                        INNER JOIN users ON users.id = clientes.user_id
                        INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                WHERE
                    contratos.plano_id = 1 AND financeiro_id = 5 and clientes.cateirinha IS NOT NULL
                    and month(contratos.created_at) = {$mes} GROUP BY contratos.id;
                    ");
                });
                $mes = "";
                return response()->json($resultado);
            } else if (!$request->mes && $request->id) {
                $id = $request->id;
                $cacheKey = "individualPagamentoPrimeiraParcelaId{$id}";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($request) {
                    return DB::select("
                    SELECT
                        DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as data,
                        (contratos.codigo_externo) as orcamento,
                        users.name as corretor,
                        clientes.nome as cliente,
                        clientes.cpf as cpf,
                        clientes.quantidade_vidas as quantidade_vidas,
                        (contratos.valor_plano) as valor_plano,
                        contratos.id,
                        estagio_financeiros.nome as parcelas,
                        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento
                    FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                            INNER JOIN clientes ON clientes.id = contratos.cliente_id
                            INNER JOIN users ON users.id = clientes.user_id
                            INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                    WHERE
                        contratos.plano_id = 1 AND financeiro_id = 5 and clientes.cateirinha IS NOT NULL
                        and clientes.user_id = {$request->id} GROUP BY contratos.id;

                    ");
                });
                return response()->json($resultado);
            } else {

                $cacheKey = "individualPagamentoPrimeiraParcelaAllElse";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($request) {
                   return DB::select("
                   SELECT
                   DATE_FORMAT(contratos.created_at,'%d/%m/%Y') as data,
                   (contratos.codigo_externo) as orcamento,
                   users.name as corretor,
                   clientes.nome as cliente,
                   clientes.cpf as cpf,
                   clientes.quantidade_vidas as quantidade_vidas,
                   (contratos.valor_plano) as valor_plano,
                   contratos.id,
                   estagio_financeiros.nome as parcelas,
                   DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento
               FROM comissoes_corretores_lancadas
                       INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                       INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                       INNER JOIN clientes ON clientes.id = contratos.cliente_id
                       INNER JOIN users ON users.id = clientes.user_id
                       INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
               WHERE
                   contratos.plano_id = 1 AND contratos.financeiro_id = 5 and clientes.cateirinha IS NOT NULL
                   GROUP BY contratos.id;

                    ");
                });
                return response()->json($resultado);
            }
            return [];
        }
    }
    
    public function uploadCobranca(Request $request)
    {
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($rowNumber >= 2) {
                        $carteirinha = Cliente::whereRaw('LEFT(cateirinha, 11) = ?', [$cells[1]->getValue()])->first();
                        if ($carteirinha && $carteirinha->count() > 0) {
                            $contrato = Contrato::where('cliente_id', $carteirinha->id)->first();
                            $data_processado = $cells[2]->getValue()->format('Y-m-d');
                            $contrato->data_processado = $data_processado;
                            $contrato->cancelado = $cells[4]->getValue() == "CANCELADO" ? 1 : 0;
                            $contrato->save();
                            //}
                        }
                    }
                }
            }
            return "sucesso";
        }
    }
    
   public function geralIndividualPendentesCobranca(Request $request)
    {
        if ($request->ajax()) {

            if($request->mes == '00' || !isset($request->mes)) {

                $cacheKey = 'geralIndividualPendentesCobranca'.time();
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {
                    return DB::select("
                        SELECT
                        DATE_FORMAT(contratos.data_processado,'%d/%m/%Y') as data,
                        (contratos.codigo_externo) as orcamento,
                        users.name as corretor,
                        clientes.nome as cliente,
                        DATE_FORMAT(clientes.data_nascimento,'%d/%m/%Y') AS data_nascimento,
                        clientes.celular AS telefone,
                        clientes.cpf as cpf,
                        clientes.quantidade_vidas as quantidade_vidas,
                        (contratos.valor_plano) as valor_plano,
                        contratos.id,
                        estagio_financeiros.nome as parcelas,
                        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento,
                        CASE
                            WHEN comissoes_corretores_lancadas.data < CURDATE() AND estagio_financeiros.id != 10 THEN 'Atrasado'
                            ELSE 'Aprovado'
                        END AS status,
                        (contratos.cancelado_confirmacao) as cancelado
                    FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                            INNER JOIN clientes ON clientes.id = contratos.cliente_id
                            INNER JOIN users ON users.id = clientes.user_id
                            INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                    WHERE
                    contratos.plano_id = 1 AND ( (comissoes_corretores_lancadas.data_baixa IS NULL AND comissoes_corretores_lancadas.status_financeiro = 0)
	                OR (comissoes_corretores_lancadas.parcela = 6 AND comissoes_corretores_lancadas.status_financeiro = 1))
                    AND contratos.data_processado >= '2023-11-01'
                    GROUP BY comissoes_corretores_lancadas.comissoes_id;
                    ");
                });







            } else {
                $mes = $request->mes;
                $cacheKey = "geralIndividualPendentesCobranca{$mes}";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($mes) {
                    return DB::select("
                        SELECT
                        DATE_FORMAT(contratos.data_processado,'%d/%m/%Y') as data,
                        (contratos.codigo_externo) as orcamento,
                        users.name as corretor,
                        clientes.nome as cliente,
                        DATE_FORMAT(clientes.data_nascimento,'%d/%m/%Y') AS data_nascimento,
                        clientes.celular AS telefone,
                        clientes.cpf as cpf,
                        clientes.quantidade_vidas as quantidade_vidas,
                        (contratos.valor_plano) as valor_plano,
                        contratos.id,
                        estagio_financeiros.nome as parcelas,
                        DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') as vencimento,
                        CASE
                            WHEN comissoes_corretores_lancadas.data < CURDATE() AND estagio_financeiros.id != 10 THEN 'Atrasado'
                            ELSE 'Aprovado'
                        END AS status,
                        (contratos.cancelado_confirmacao) as cancelado
                    FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                            INNER JOIN clientes ON clientes.id = contratos.cliente_id
                            INNER JOIN users ON users.id = clientes.user_id
                            INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                    WHERE
                        (status_financeiro = 0 OR (status_financeiro = 1 AND parcela = 6))
                    AND contratos.plano_id = 1
                    AND clientes.cateirinha IS NOT NULL and month(contratos.created_at) = {$mes}
                    AND contratos.data_processado >= '2023-11-01'
                    GROUP BY contratos.id;
                    ");
                });
            }
            return response()->json($resultado);
        }
    }
    
    
    
    
    
    


    public function financeiroMontarSelect(Request $request)
    {
        $mes = $request->mes;
        if($mes != 00) {
            $qtd_individual_parcela_01 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 5)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_01 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 5)

                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }

        if($mes != 00) {
            $qtd_individual_parcela_02 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 6)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_02 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 6)

                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }

        if($mes != 00) {
            $qtd_individual_parcela_03 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 7)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_03 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 7)

                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }


        if($mes != 00) {
            $qtd_individual_parcela_04 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 8)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_04 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 8)

                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }

        if($mes != 00) {
            $qtd_individual_parcela_05 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 9)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_05 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 9)

                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }


        if($mes != 00) {
            $qtd_individual_parcela_06 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 10)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_parcela_06 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 10)

                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }



        if($mes != 00) {
            $qtd_individual_finalizado = Contrato
                ::where("financeiro_id", 11)
                ->where("plano_id", 1)
                ->whereMonth("created_at", $mes)
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        } else {
            $qtd_individual_finalizado = Contrato
                ::where("financeiro_id", 11)
                ->where("plano_id", 1)

                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
        }

        if($mes != 00) {
            $qtd_individual_cancelado = Contrato::join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
            ->where(function ($query) use($mes) {
                $query->where('contratos.plano_id', 1)
                    ->whereMonth('contratos.created_at', $mes)
                    ->where('contratos.financeiro_id', 12);
            })

            ->orWhere(function ($query) use($mes) {
                $query->where('contratos.plano_id', 1)
                    ->whereMonth('contratos.created_at', $mes)
                    ->whereNull('clientes.cateirinha');
            })
            ->count();
        } else {
            $qtd_individual_cancelado = Contrato::join('clientes', 'clientes.id', '=', 'contratos.cliente_id')
            ->where(function ($query) {
                $query->where('contratos.plano_id', 1)
                    ->where('contratos.financeiro_id', 12);
            })

            ->orWhere(function ($query) {
                $query->where('contratos.plano_id', 1)
                    ->whereNull('clientes.cateirinha');
            })
            ->count();
        }

        if($mes != 00) {
            $qtd_cliente = Cliente
                ::whereMonth("created_at", $mes)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })->count();
        } else {
            $qtd_cliente = Cliente
                ::whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })->count();
        }


        if($mes != 00) {
            $qtd_vidas = Cliente
                ::whereMonth("created_at", $mes)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })->selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first();
        } else {
            $qtd_vidas = Cliente
                ::whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })->selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first();
        }


        if($mes != 00) {
            $contratos = DB::table('users')
                ->selectRaw('name')
                ->selectRaw('id')
                ->whereRaw('id IN (SELECT user_id FROM clientes WHERE id IN (SELECT cliente_id FROM contratos WHERE plano_id = 1 AND EXISTS (SELECT * FROM clientes WHERE contratos.cliente_id = clientes.id AND cateirinha IS NOT NULL) AND MONTH(data_vigencia) = ?))', [$mes])
                ->get();
        } else {
            $contratos = DB::table('users')
                ->selectRaw('name')
                ->selectRaw('id')
                ->whereRaw('ativo = 1')
                ->orderBy("name")
                ->get();
        }

        if($mes != 00) {
            $qtd_individual_atrasado = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", "!=", 12)
                ->whereMonth("created_at", $mes)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes', function ($query) {
                    $query->whereRaw('cateirinha IS NOT NULL');
                })
                ->count();
        } else {
            $qtd_individual_atrasado = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", "!=", 12)

                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA < CURDATE()");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes', function ($query) {
                    $query->whereRaw('cateirinha IS NOT NULL');
                })
                ->count();
        }



        return [
            "qtd_individual_parcela_01" => $qtd_individual_parcela_01,
            "qtd_individual_parcela_02" => $qtd_individual_parcela_02,
            "qtd_individual_parcela_03" => $qtd_individual_parcela_03,
            "qtd_individual_parcela_04" => $qtd_individual_parcela_04,
            "qtd_individual_parcela_05" => $qtd_individual_parcela_05,
            "qtd_individual_parcela_06" => $qtd_individual_parcela_06,
            "qtd_individual_cancelado" => $qtd_individual_cancelado,
            "qtd_individual_atrsado" => $qtd_individual_atrasado,
            "contratos" => $contratos
        ];


    }




    public function semCarteirinha()
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id","!=",12)
            ->whereHas('clientes', function ($query) {
                $query->whereRaw("cateirinha IS NULL");
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'plano', 'comissao.comissaoAtualFinanceiro', 'clientes', 'clientes.user'])
            ->get();
        return $contratos;
    }




    public function geralTodosContratosPendentes(Request $request)
    {
        // $comissoes = Comissoes::with('contrato')->get();
        // return $comissoes;


        $contratos = Contrato
            // ::where("plano_id",1)
            // ->whereHas('clientes',function($query){
            //     $query->whereRaw("cateirinha IS NOT NULL");
            // })

            // ->whereHas('comissao.ultimaComissaoPaga',function($query){
            //     $query->whereYear("data",2022);
            //     $query->whereMonth('data','08');
            // })
            ::with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'comissao.ultimaComissaoPaga', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->whereYear("data_vigencia", date('Y'))
            ->whereMonth("data_vigencia", date('m'))
            //->orderBy("id","desc")
            ->get();

        return $contratos;
    }












    public function atualizarCarteirinha(Request $request)
    {
        $carteirinha = $request->cateirinha;
        $id = $request->id_cliente;

        $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario/$carteirinha/financeiro/historico";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $dados = json_decode($resp);


        if ($dados != null) {
            $cliente = Cliente::where("id", $id)->first();
            $cliente->cateirinha = $carteirinha;
            $cliente->save();

            $contrato = Contrato::where('cliente_id', $id)->first();
            $contrato->financeiro_id = 12;
            $contrato->save();

            $comissao = Comissoes::where("contrato_id", $contrato->id)->first()->id;


            foreach ($dados as $d) {

                $data_vencimento = implode("-", array_reverse(explode("/", $d->dtVencimento)));
                $comissoesLancadas = ComissoesCorretoresLancadas::where("comissoes_id", $comissao)->where("data", $data_vencimento)->first();

                $comissoesLancadas->valor_pago = $d->vlObrigacao;
                $comissoesLancadas->status_financeiro = 1;
                //$comissoesLancadas->status_gerente = 1;
                $comissoesLancadas->data_baixa = implode("-", array_reverse(explode("/", $d->dtPagamento)));
                $comissoesLancadas->save();

            }

            $qtd_individual_parcela_01 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 5)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 1);
                })
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_02 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 6)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 2);
                })
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_03 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 7)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 3);
                })
                ->whereHas('clientes', function ($query) use ($request) {

                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_04 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 8)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 4);
                })
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
            $qtd_individual_parcela_05 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 9)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 5);
                })
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_06 = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", 10)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->where("parcela", 6);
                })
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_finalizado = Contrato
                ::where("financeiro_id", 11)
                ->where("plano_id", 1)
                ->whereHas('clientes', function ($query) use ($request) {

                })
                ->count();

            $qtd_individual_cancelado = Contrato
                ::where("financeiro_id", 12)
                ->where("plano_id", 1)
                ->whereHas('clientes', function ($query) use ($request) {

                })
                ->count();

            $qtd_cliente = Cliente
                ::where("user_id", $request->id)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })->count();

            $qtd_vidas = Cliente
                ::where("user_id", $request->id)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato', function ($query) {
                    $query->whereRaw('plano_id = 1');
                })
                ->selectRaw("sum(quantidade_vidas) as quantidade_vidas")
                ->first();

            $qtd_individual_atrasado = Contrato
                ::where("plano_id", 1)
                ->where("financeiro_id", "!=", 12)
                ->whereHas('comissao.comissoesLancadas', function ($query) {
                    $query->whereRaw("DATA <= NOW()");
                    $query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes', function ($query) use ($request) {
                    $query->whereRaw('cateirinha IS NOT NULL');
                    $query->where("user_id", $request->id);
                })
                ->count();

            return [
                "qtd_individual_parcela_01" => $qtd_individual_parcela_01,
                "qtd_individual_parcela_02" => $qtd_individual_parcela_02,
                "qtd_individual_parcela_03" => $qtd_individual_parcela_03,
                "qtd_individual_parcela_04" => $qtd_individual_parcela_04,
                "qtd_individual_parcela_05" => $qtd_individual_parcela_05,
                "qtd_individual_parcela_06" => $qtd_individual_parcela_06,
                "qtd_individual_finalizado" => $qtd_individual_finalizado,
                "qtd_individual_cancelado" => $qtd_individual_cancelado,
                "qtd_individual_atrasado" => $qtd_individual_atrasado,
                "qtd_clientes" => $qtd_cliente,
                "qtd_vidas" => $qtd_vidas->quantidade_vidas
            ];


        } else {
            return "error";
        }


    }



    public function getAtrasadosCorretor()
    {
        $atrasados = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", "!=", 12)
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                $query->whereRaw("DATA <= NOW()");
                //$query->whereRaw("valor > 0");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
            ->whereHas('clientes', function ($query) {
                $query->whereRaw('cateirinha IS NOT NULL');
                $query->where("user_id", auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->get();
        return $atrasados;
    }




















    public function emAnaliseIndividualCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 1)
            ->whereHas('clientes', function ($query) {
                $query->where("user_id", auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }











    public function coletivoPagamentoAdesaoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 3)
            ->where("financeiro_id", 3)
            ->whereHas('clientes', function ($query) {
                $query->where("user_id", auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                $query->where("status_financeiro", 0);
                $query->where("status_gerente", 0);
                $query->where("parcela", 1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }













    public function getAtrasadoEmpresarial(Request $request)
    {
        if ($request->ajax()) {
            $cacheKey = 'getAtrsadoEmpresarial';
            $tempoDeExpiracao = 60;
            $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {

            });
        }
    }





















    public function individualPagamentoPrimeiraParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 5)
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 1);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }




    public function individualPagamentoSegundaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 6)
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 2);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }








    public function individualPagamentoTerceiraParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 7)
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 3);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }




    public function zerarTabelaFinanceiro()
    {
        return [];
    }









    public function individualPagamentoQuartaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 8)
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 4);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }








    public function individualPagamentoQuintaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ::where("financeiro_id", 9)
            ->whereHas('clientes', function ($query) {
                $query->where("user_id", auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 5);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }






    public function individualPagamentoSextaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 10)
            ->whereHas('comissao.comissoesLancadas', function ($query) {
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela", 6);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }














    public function coletivoFinalizadoColetivo(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 3)
            ->whereHas("clientes", function ($query) {
                $query->where("user_id", auth()->user()->id);
            })
            ->where("financeiro_id", 11)
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }




    public function individualFinalizadoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 11)
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }






    public function estornoColetivo(Request $request)
    {
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
        comissoes.user_id = {$id} AND plano_id = 3 AND comissoes_corretores_lancadas.finalizado != 1
        ORDER BY comissoes.administradora_id
        ");
    }



    public function individualCanceladosCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id", 1)
            ->where("financeiro_id", 12)
            ->whereHas('clientes', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'comissao.cancelado', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("tempo >= now()")
            ->orderBy("id", "desc")
            ->get();
        return $contratos;
    }







    public function mudarEstadosIndividual(Request $request)
    {
        $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = Contrato::find($id_contrato);
        switch ($contrato->financeiro_id) {
            case 1:
                $contrato->financeiro_id = 5;
                break;
            default:
                return "abrir_modal_individual";
                break;
        }
        $contrato->save();
        return $this->recalcularIndividual();
    }







    public function verContrato(Request $request)
    {

        //return $request->all();

        // if($request->financeiro_id == 1) {

        // } else {

        // }

        if ($request->janela != "aba_empresarial") {
            $plano_id = Contrato::where("id", $request->contrato_id)->first()->plano_id;
            $id_comissao = Comissoes::where("contrato_id", $request->contrato_id)->first()->id;
            $comissoes = DB::table('comissoes_corretores_lancadas')
                ->selectRaw('parcela')
                ->selectRaw('DATA AS vencimento')
                ->selectRaw('data_baixa AS data_baixa')
                ->selectRaw('(SELECT valor_plano FROM contratos WHERE id = (SELECT contrato_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS valor')
                ->selectRaw('DATEDIFF(data, data_baixa) as dias_faltando')
                ->selectRaw('(SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE id = (SELECT contrato_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id))) AS cliente')
                ->whereRaw("comissoes_id = ?", $id_comissao)
                ->get();


        } else {
            $plano_id = "";
            $id_comissao = Comissoes::where("contrato_empresarial_id", $request->contrato_id)->first()->id;

            $comissoes = DB::table('comissoes_corretores_lancadas')
                ->selectRaw('parcela')
                ->selectRaw('DATA AS vencimento')
                ->selectRaw('data_baixa AS data_baixa')
                ->selectRaw('(SELECT valor_plano FROM contrato_empresarial WHERE id = (SELECT contrato_empresarial_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS valor')
                ->selectRaw('DATEDIFF(data, data_baixa) as dias_faltando')
                ->selectRaw('(SELECT responsavel FROM contrato_empresarial WHERE id = (SELECT contrato_empresarial_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS cliente')
                ->whereRaw("comissoes_id = ?", $id_comissao)
                ->get();


        }
        //return $comissoes;


        return view('admin.pages.comissao.ver', [
            "comissoes" => $comissoes,
            "cliente" => $comissoes[0]->cliente,
            "plano_id" => $plano_id
        ]);
    }

    public function clienteCancelado($id)
    {
        $contrato = Contrato::where("id", $id)->first();
        $cliente = Cliente::where("id", $contrato->cliente_id)->first();
        $comissao = Comissoes::where("contrato_id", $contrato->id)->first();
        $comissoesLancadas = ComissoesCorretoresLancadas
            ::where("comissoes_id", $comissao->id)
            ->whereRaw("valor_pago IS NOT NULL")
            ->selectRaw("parcela,data,valor_pago,data_baixa")
            ->selectRaw("(if(data_baixa != '0000-00-00','LIQUIDADO','CANCELADO') ) status")
            ->get();

        $contratos = Contrato
            ::where("id", $id)
            ->with(['administradora', 'financeiro', 'cidade', 'comissao', 'acomodacao', 'plano', 'comissao.comissaoAtualFinanceiro', 'comissao.comissoesLancadas', 'somarCotacaoFaixaEtaria', 'clientes', 'clientes.user', 'clientes.dependentes'])
            ->orderBy("id", "desc")
            ->first();


        return view('admin.pages.financeiro.cancelados', [
            "comissao" => $comissoesLancadas,
            "cliente" => $cliente,
            "dados" => $contratos
        ]);

    }






    public function cancelarContrato(Request $request)
    {
        $contrato_id = Comissoes::where("id", $request->comissao_id_cancelado)->first()->contrato_id;
        $contrato = Contrato::where("id", $contrato_id)->first();
        $comissaoLancadas = ComissoesCorretoresLancadas
            ::where('comissoes_id', $request->comissao_id_cancelado)
            ->where('data_baixa', null)
            ->update(["atual" => 0, "cancelados" => 1]);
        if (!$comissaoLancadas) {
            return "error";
        }
        Contrato::where("id", $contrato->id)->update(['financeiro_id' => 12]);
        return "sucesso";
    }


    public function excluirClienteIndividual(Request $request)
    {
        if ($request->ajax()) {
            $id_cliente = $request->id_cliente;
            if ($id_cliente != null) {
                $d = Cliente::where("id", $id_cliente)->delete();
                if ($d) {
                    return $this->recalcularIndividual();
                } else {
                    return "error";
                }
            }
        }

    }



    private function clienteJaExiste($cpf)
    {
        $cliente = Cliente::where("cpf", $cpf)->get();
        if (count($cliente) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    public function sincronizarDadosColetivo(Request $request)
    {

        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($rowNumber >= 2) {

                        $tabela_origens_id = TabelaOrigens::where("nome","LIKE",$cells[3]->getValue())->first()->id;
                        $administradora_id = Administradoras::where("nome","LIKE",$cells[2]->getValue())->first()->id;



                        $user_id = cidadeCodigoVendedor::where("codigo_vendedor",$cells[0]->getValue())
                            ->where("tabela_origens_id",$tabela_origens_id)
                            ->first()
                            ->user_id;





                        $cpf = mb_strlen($cells[6]->getValue()) == 11 ? $cells[6]->getValue() : str_pad($cells[6]->getValue(), 11, "000", STR_PAD_LEFT);
                        //$user_id = User::where('codigo_vendedor', $cells[0]->getValue())->first()->id;

                        $nascimento = $cells[9]->getValue()->format('Y-m-d');
                        $criacao = $cells[18]->getValue()->format('Y-m-d');

                        $alvo = trim($cells[21]->getValue());
                        $id_acomodacao = Acomodacao::where("nome", "LIKE", "%$alvo%")->first()->id;
                        $cliente = new Cliente();
                        $cliente->user_id = $user_id;
                        $cliente->nome = mb_convert_case($cells[4]->getValue(), MB_CASE_TITLE, "UTF-8");
                        $cliente->celular = $cells[11]->getValue();
                        $cliente->cpf = $cpf;
                        $cliente->data_nascimento = $nascimento;
                        $cliente->pessoa_fisica = 1;
                        $cliente->pessoa_juridica = 0;
                        $cliente->codigo_externo = $cells[5]->getValue();
                        $cliente->cep = $cells[12]->getValue();
                        $cliente->cidade = $cells[13]->getValue();
                        $cliente->bairro = $cells[14]->getValue();
                        $cliente->rua = $cells[15]->getValue();
                        $cliente->complemento = $cells[16]->getValue();
                        $cliente->uf = $cells[17]->getValue();

                        $cliente->created_at = $criacao;
                        $cliente->quantidade_vidas = $cells[25]->getValue();
                        $cliente->email = mb_convert_case($cells[10]->getValue(), MB_CASE_LOWER, "UTF-8");
                        $cliente->save();

                        if (!empty($cells[7]->getValue()) && $cells[7]->getValue() != null) {
                            $dependente = new Dependentes();
                            $cpf_responsavel = mb_strlen($cells[8]->getValue()) == 11 ? $cells[8]->getValue() : str_pad($cells[8]->getValue(), 11, "000", STR_PAD_LEFT);
                            $dependente->cliente_id = $cliente->id;
                            $dependente->nome = mb_convert_case($cells[7]->getValue(), MB_CASE_TITLE, "UTF-8");
                            $dependente->cpf = $cpf_responsavel;
                            $dependente->save();
                        }



                        $coparticipacao = 0;
                        if($cells[22]->getValue() == 1 && $cells[23]->getValue() == 0) {
                            $coparticipacao = 1;
                        } else if($cells[22]->getValue() == 0 && $cells[23]->getValue() == 1) {
                            $coparticipacao = 0;
                        } else {
                            $coparticipacao = 0;
                        }



                        $contrato = new Contrato();
                        $contrato->acomodacao_id = $id_acomodacao;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = $administradora_id;
                        $contrato->tabela_origens_id = $tabela_origens_id;
                        $contrato->plano_id = 3;
                        $contrato->financeiro_id = 1;
                        $contrato->data_vigencia = $cells[19]->getValue()->format('Y-m-d');
                        $contrato->codigo_externo = $cells[5]->getValue();
                        // $contrato->data_boleto = implode("-",array_reverse(explode("/",$cells[21]->getValue())));
                        $contrato->data_boleto = $cells[20]->getValue()->format('Y-m-d');
                        $contrato->valor_adesao = empty($cells[27]->getValue()) && $cells[27]->getValue() == null ? $cells[27]->getValue() : $cells[27]->getValue();
                        $contrato->valor_plano = $cells[26]->getValue();
                        $contrato->coparticipacao = $coparticipacao;
                        $contrato->odonto = $cells[24];
                        $contrato->created_at = $cells[18]->getValue()->format('Y-m-d');
                        $contrato->desconto_corretor = "0,00";
                        $contrato->desconto_corretora = "0,00";
                        $contrato->save();

                        $comissao = new Comissoes();
                        $comissao->contrato_id = $contrato->id;
                        // $comissao->cliente_id = $contrato->cliente_id;
                        $comissao->user_id = $user_id;
                        // $comissao->status = 1;
                        $comissao->plano_id = 3;
                        $comissao->administradora_id = $administradora_id;
                        $comissao->tabela_origens_id = $tabela_origens_id;
                        $comissao->data = date('Y-m-d');
                        $comissao->save();

                        /* Comissao Corretor */
                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                            ::where("plano_id", 3)
                            ->where("administradora_id", $administradora_id)
                            ->where("user_id", $user_id)
                            //->where("tabela_origens_id", $tabela_origens_id)
                            ->get();
                        $data_vigencia = $cells[19]->getValue()->format('Y-m-d');
                        $comissao_corretor_contagem = 0;
                        $comissao_corretor_default = 0;
                        if (count($comissoes_configuradas_corretor) >= 1) {
                            foreach ($comissoes_configuradas_corretor as $c) {
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                //$comissaoVendedor->user_id = auth()->user()->id;
                                $comissaoVendedor->parcela = $c->parcela;
                                if ($comissao_corretor_contagem == 0) {
                                    $comissaoVendedor->data = $cells[20]->getValue()->format('Y-m-d');
                                    //$comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                        //$comissaoVendedor->status_gerente = 1;
                                    }

                                } elseif ($comissao_corretor_contagem == 1) {
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia));
                                } else {
                                    $mes = $comissao_corretor_contagem - 1;
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia . "+{$mes}month"));

                                }
                                $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                                $comissaoVendedor->save();
                                $comissao_corretor_contagem++;
                            }
                        } else {

                            $dados = ComissoesCorretoresDefault
                                ::where("plano_id", 3)
                                ->where("administradora_id", $administradora_id)
                                ->where("tabela_origens_id", $tabela_origens_id)
                                ->get();
                            foreach ($dados as $c) {
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                $comissaoVendedor->parcela = $c->parcela;


                                if ($comissao_corretor_default == 0) {
                                    $comissaoVendedor->data = $cells[20]->getValue()->format('Y-m-d');
                                    //$comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                        //$comissaoVendedor->status_gerente = 1;
                                    }

                                } elseif ($comissao_corretor_default == 1) {
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia));
                                } else {
                                    $mes = $comissao_corretor_default - 1;
                                    $comissaoVendedor->data = date("Y-m-d H:i:s", strtotime($data_vigencia . "+{$mes}month"));

                                }
                                $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                                $comissaoVendedor->save();
                                $comissao_corretor_default++;
                            }
                        }

                        $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes
                            ::where("administradora_id", $administradora_id)
                            ->where('plano_id', 3)
                            ->where('tabela_origens_id', $tabela_origens_id)
                            ->get();

                        $comissoes_corretora_contagem = 0;
                        if (count($comissoes_configurada_corretora) >= 1) {
                            foreach ($comissoes_configurada_corretora as $cc) {
                                $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                                $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                                $comissaoCorretoraLancadas->parcela = $cc->parcela;
                                if ($comissoes_corretora_contagem == 0) {
                                    $comissaoCorretoraLancadas->data = $data_vigencia;

                                    // } else if($comissoes_corretora_contagem == 1) {
                                    //     $comissaoCorretoraLancadas->data = date("Y-m-d H:i:s",strtotime($data_vigencia));
                                    // } else {
                                    //     $mes = $comissoes_corretora_contagem - 1;
                                    //     $comissaoCorretoraLancadas->data = date("Y-m-d",strtotime($data_vigencia."+{$mes}month"));
                                } else {
                                    $comissaoCorretoraLancadas->data = date("Y-m-d", strtotime($data_vigencia . "+{$comissoes_corretora_contagem}month"));
                                }
                                $comissaoCorretoraLancadas->valor = ($cells[27]->getValue() * $cc->valor) / 100;
                                $comissaoCorretoraLancadas->save();
                                $comissoes_corretora_contagem++;
                            }
                        }


                    }
                }
            }


            return "sucesso";
        }
    }

    /*
    public function sincronizarDados(Request $request)
    {
        set_time_limit(300);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($rowNumber === 3) {
                        $cidade = $cells[2]->getValue();
                    }
                    if ($rowNumber >= 5 && !in_array($cells[0]->getValue(), $cpfs)) {
                        $cpf = mb_strlen($cells[4]->getValue()) == 11 ? $cells[4]->getValue() : str_pad($cells[4]->getValue(), 11, "000", STR_PAD_LEFT);
                        $dia = str_pad($cells[18]->getValue(), 2, "0", STR_PAD_LEFT);
                        array_push($cpfs, $cells[0]->getValue());
                        //$user_count = User::where('codigo_vendedor', $cells[2]->getValue())->count();
                        $user_count = cidadeCodigoVendedor::where("codigo_vendedor",$cells[2]->getValue());
                        if (!$user_count && $user_count == 0) {
                            $user = new User();
                            $user->name = Str::title(Str::lower($cells[3]->getValue()));
                            $user->codigo_vendedor = $cells[2]->getValue();
                            $user->password = bcrypt("12345678");
                            $user->cargo_id = 2;
                            $email = Str::title($cells[3]->getValue()) . "@accert.connectlife.app.br";
                            $user->email = Str::snake($email, "_");
                            $user->save();
                            $user_id = $user->id;
                        } else {
                            $user_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->id;
                            //$user_id = cidadeCodigoVendedor::where("codigo_tabela_origem",$cidade)->where("codigo_vendedor",$cells[2]->getValue())->first()->user_id;
                            //$cidade_id = cidadeCodigoVendedor::where("codigo_tabela_origem",$cidade)->where("codigo_vendedor",$cells[2]->getValue())->first()->tabela_origens_id;
                        }
                        $cidade_id = 2;
                        $cliente = new Cliente();
                        $cliente->user_id = $user_id;
                        $cliente->nome = mb_convert_case($cells[5]->getValue(), MB_CASE_TITLE, "UTF-8");
                        $cliente->celular = $cells[7]->getValue();
                        $cliente->cpf = $cpf;
                        $cliente->data_nascimento = implode("-", array_reverse(explode("/", $cells[6]->getValue())));
                        $cliente->pessoa_fisica = 1;
                        $cliente->pessoa_juridica = 0;
                        $cliente->codigo_externo = $cells[0]->getValue();

                        if ($cells[8]->getValue() == "RESPONSÁVEL FINANCEIRO") {
                            $cliente->quantidade_vidas = $cells[15]->getValue();
                        } else {
                            $cliente->quantidade_vidas = $cells[15]->getValue() + 1;
                        }
//
                        $cliente->save();
                        $data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato = new Contrato();
                        //$contrato->acomodacao_id = $acomodacao_id;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = 4;
                        $contrato->tabela_origens_id = $cidade_id;
                        $contrato->plano_id = 1;
                        $contrato->financeiro_id = 5;
                        $contrato->data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato->codigo_externo = $cells[0]->getValue();
                        $contrato->data_boleto = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato->valor_adesao = str_replace([".",","],["","."], $cells[12]->getValue());
                        $contrato->valor_plano = str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                        $contrato->coparticipacao = 1;
                        $contrato->odonto = 0;
                        $contrato->created_at = $data_vigencia;
                        $contrato->desconto_corretor = "0,00";
                        $contrato->desconto_corretora = "0,00";
                        $contrato->save();
                        $comissao = new Comissoes();
                        $comissao->contrato_id = $contrato->id;
                        // $comissao->cliente_id = $contrato->cliente_id;
                        $comissao->user_id = $user_id;
                        // $comissao->status = 1;
                        $comissao->plano_id = 1;
                        $comissao->administradora_id = 4;
                        $comissao->tabela_origens_id = $cidade_id;
                        $comissao->data = date('Y-m-d');
                        $comissao->save();

                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                            ::where("plano_id", 1)
                            ->where("administradora_id", 4)
                            ->where("user_id", $user_id)
                            //->where("tabela_origens_id", 2)
                            ->get();
//
                        $comissao_corretor_contagem = 0;
                        $comissao_corretor_default = 0;
//
//
                        if (count($comissoes_configuradas_corretor) >= 1) {
                            foreach ($comissoes_configuradas_corretor as $c) {
                                $valor_comissao = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                //$comissaoVendedor->user_id = auth()->user()->id;
                                // $comissaoVendedor->documento_gerador = "12345678";
                                $comissaoVendedor->parcela = $c->parcela;
                                $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                if ($comissao_corretor_contagem == 0) {
                                    $comissaoVendedor->data = $data_vigencia;
                                    $comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                    }
                                    $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                    $comissaoVendedor->valor_pago = $cells[12]->getValue();
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));

                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {

                                        $ano = explode("-", $dates)[0];

                                        $comissaoVendedor->data = date($ano."-02-28");
                                        $ano = explode("-", $comissaoVendedor->data)[0];
                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));

                                        if ($bissexto == 1) {
                                            $comissaoVendedor->data = date($ano."-02-29");
                                        } else {

                                            $comissaoVendedor->data = date($ano."-02-28");
                                        }

                                    } else {
                                        $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
//
                                }
                                $comissaoVendedor->save();
                                $comissao_corretor_contagem++;
                            }
                        } else {

                            $dados = ComissoesCorretoresDefault
                                ::where("plano_id", 1)
                                ->where("administradora_id", 4)
                                //->where("tabela_origens_id", 2)
                                ->get();
                            foreach ($dados as $c) {
                                $valor_comissao_default = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                $comissaoVendedor->parcela = $c->parcela;
                                $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                                if ($comissao_corretor_default == 0) {
                                    $comissaoVendedor->data = $data_vigencia;
                                    $comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                    }
                                    $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                    $comissaoVendedor->valor_pago = $cells[12]->getValue();
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_default}month"));

                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {
                                        $ano = explode("-", $dates)[0];
                                        $comissaoVendedor->data = date($ano."-02-28");

                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                        if ($bissexto == 1) {
                                            $comissaoVendedor->data = date($ano."-02-29");
                                        } else {
                                            $comissaoVendedor->data = date($ano."-02-28");
                                        }
                                    } else {
                                        $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
                                }
                                $comissaoVendedor->save();
                                $comissao_corretor_default++;
                            }
                        //}

                    }


                        $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes
                                ::where("administradora_id", 4)
                                ->where('plano_id', 1)
                                //->where('tabela_origens_id', 2)
                                ->get();
                        $comissoes_corretora_contagem = 0;
                        if (count($comissoes_configurada_corretora) >= 1) {
                            foreach ($comissoes_configurada_corretora as $cc) {
                                $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                                $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                                $comissaoCorretoraLancadas->parcela = $cc->parcela;
                                if ($comissoes_corretora_contagem == 0) {
                                    $comissaoCorretoraLancadas->data = $data_vigencia;
                                    $comissaoCorretoraLancadas->status_financeiro = 1;
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissoes_corretora_contagem}month"));
                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {
                                        $comissaoCorretoraLancadas->data = date("Y-02-28");
                                        $ano = explode("-", $comissaoCorretoraLancadas->data)[0];
                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                        if ($bissexto == 1) {
                                            $comissaoCorretoraLancadas->data = date("Y-02-29");
                                        } else {
                                            $comissaoCorretoraLancadas->data = date("Y-02-28");
                                        }
                                    } else {
                                        $comissaoCorretoraLancadas->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
                                }
                                $valor_cc = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoCorretoraLancadas->valor = ($valor_cc * $cc->valor) / 100;
                                $comissaoCorretoraLancadas->save();
                                $comissoes_corretora_contagem++;
                            }
                        }
                }
            //unlink("public/".$filename);
        }



    }
    }






        //Cliente::orderBy("id","desc")->first()->update(["last"=>1]);
        return "sucesso";
    }




    */

    public function codigoExterno($codigo)
    {
        $cliente = Cliente::where("codigo_externo",$codigo)->count();
        return $cliente;
    }



    public function sincronizarCancelados(Request $request)
    {
        set_time_limit(300);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    if($rowNumber > 1) {
                        $cells = $row->getCells();
                        $cart = $cells[1]->getValue();
                        $status = $cells[4]->getValue();
                        $nome = $cells[3]->getValue();

                        if($status == "CANCELADO") {
                            $carteirinha = Cliente
                            ::select('clientes.*')
                            ->join('users','users.id','=','clientes.user_id')
                            ->join('contratos','contratos.cliente_id','=','clientes.id')
                            ->where('clientes.cateirinha',$cart)
                            ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
                            ->first();

                            if($carteirinha) {
                                $c = Contrato::find($carteirinha->contrato->id);
                                $c->financeiro_id = 12;
                                $c->save();
                            }

                        }






                    }
                }
            }
            return "sucesso";
        }
    }
    











//     public function sincronizarCancelados(Request $request)
//     {
//         set_time_limit(300);
//         $filename = uniqid() . ".xlsx";
//         if (move_uploaded_file($request->file, $filename)) {
//             $filePath = base_path("public/{$filename}");
//             $cpfs = [];
//             $reader = ReaderEntityFactory::createReaderFromFile($filePath);
//             $reader->open($filePath);
//             $cidade = "";
//             $i = 0;
//             foreach ($reader->getSheetIterator() as $sheet) {
//                 foreach ($sheet->getRowIterator() as $rowNumber => $row) {

//                         $cells = $row->getCells();
//                         $cart = $cells[1]->getValue();
//                         $status = $cells[4]->getValue();
//                         $nome = $cells[3]->getValue();
//                         $valor =  str_replace([".",""],[",",""], $cells[5]->getValue());

//                         $codigo_vendedor = $cells[0]->getValue();
//                         //if($status == "CANCELADO") {

// //                            $carteirinha = Cliente
// //                                ::select('clientes.*')
// //                                ->join('users','users.id','=','clientes.user_id')
// //                                ->join('contratos','contratos.cliente_id','=','clientes.id')
// //                                //->where('clientes.cateirinha',$cart)
// //                                ->where("nome","LIKE",$nome)
// //
// //                                //->where("users.codigo_vendedor",$codigo_vendedor)
// //                                //->where("contratos.valor_adesao",$valor)
// //                                //->whereRaw("clientes.cateirinha IS NULL")
// //                                //->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
// //                                ->first();

//                     $carteirinha = Cliente::select('clientes.*')
//                         ->join('users', 'users.id', '=', 'clientes.user_id')
//                         ->join('contratos', 'contratos.cliente_id', '=', 'clientes.id')
//                         ->whereRaw("SUBSTRING(clientes.cateirinha, 1, LENGTH(clientes.cateirinha) - 3) = ?", [$cart])
//                         ->where("nome", "LIKE", $nome)
//                         ->first();








// //                            echo "<pre>";
// //                            echo $i." - ".$nome;RENATA MACEDO DE SOUZA
// //                            echo "</pre>";
// //                            $carteirinha = Cliente
// //                                ::select('clientes.*')
// //                                ->join('users','users.id','=','clientes.user_id')
// //                                ->join('contratos','contratos.cliente_id','=','clientes.id')
// //                                //->where('clientes.cateirinha',$cart)
// //                                ->where("nome","LIKE",$nome)
// //                                ->where("users.codigo_vendedor",$codigo_vendedor)
// //                                //->where("contratos.valor_adesao",$valor)
// //                                //->whereRaw("clientes.cateirinha IS NULL")
// //                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
// //                                ->first();
// ////
// //                            $carteirinha = Cliente
// //                                ::select('clientes.*')
// //                                ->join('users','users.id','=','clientes.user_id')
// //                                ->join('contratos','contratos.cliente_id','=','clientes.id')
// //                                ->where('clientes.cateirinha',$cart)
// //                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
// //                                ->first();







//                             if($carteirinha)
//                             {
//                                 $c = Contrato::find($carteirinha->contrato->id);
//                                 $c->financeiro_id = 12;
//                                 $c->save();

//                             }









//                         //}




// //                        if($status == "CANCELADO") {
// //                            $carteirinha = Cliente
// //                                ::select('clientes.*')
// //                                ->join('users','users.id','=','clientes.user_id')
// //                                ->join('contratos','contratos.cliente_id','=','clientes.id')
// //                                ->where('clientes.cateirinha',$cart)
// //                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
// //                                ->first();
// //
// //                            if($carteirinha) {
// //                                $c = Contrato::find($carteirinha->contrato->id);
// //                                $c->financeiro_id = 12;
// //                                $c->save();
// //                            }
// //
// //                        }






//                 }
//             }
//             return "sucesso";
//         }
//     }



    public function sincronizarDados(Request $request)
    {
        set_time_limit(0);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if ($rowNumber === 3) {
                        $cidade = $cells[2]->getValue();
                    }
                    if ($rowNumber >= 5 && !in_array($cells[0]->getValue(), $cpfs) && $this->codigoExterno($cells[0]->getValue()) == 0) {
                        $cpf = mb_strlen($cells[4]->getValue()) == 11 ? $cells[4]->getValue() : str_pad($cells[4]->getValue(), 11, "000", STR_PAD_LEFT);
                        $dia = str_pad($cells[18]->getValue(), 2, "0", STR_PAD_LEFT);
                        array_push($cpfs, $cells[0]->getValue());
                        //$user_count = User::where('codigo_vendedor', $cells[2]->getValue())->count();
                       
                        $user_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->id;
                        // $user_count = cidadeCodigoVendedor::where("codigo_vendedor",$cells[2]->getValue());
                        // if (!$user_count && $user_count == 0) {
                        //     $user = new User();
                        //     $user->name = Str::title(Str::lower($cells[3]->getValue()));
                        //     $user->codigo_vendedor = $cells[2]->getValue();
                        //     $user->password = bcrypt("12345678");
                        //     $user->cargo_id = 2;
                        //     $email = Str::title($cells[3]->getValue()) . "@accert.connectlife.app.br";
                        //     $user->email = Str::snake($email, "_");
                        //     $user->save();
                        //     $user_id = $user->id;
                        // } else {
                        //     $user_id = User::where('codigo_vendedor', $cells[2]->getValue())->first()->id;
                        //     //$user_id = cidadeCodigoVendedor::where("codigo_tabela_origem",$cidade)->where("codigo_vendedor",$cells[2]->getValue())->first()->user_id;
                        //     //$cidade_id = cidadeCodigoVendedor::where("codigo_tabela_origem",$cidade)->where("codigo_vendedor",$cells[2]->getValue())->first()->tabela_origens_id;
                        // }
                        $cidade_id = 2;
                        $cliente = new Cliente();
                        $cliente->user_id = $user_id;
                        $cliente->nome = mb_convert_case($cells[5]->getValue(), MB_CASE_TITLE, "UTF-8");
                        $cliente->celular = $cells[7]->getValue();
                        $cliente->cpf = $cpf;
                        $cliente->data_nascimento = implode("-", array_reverse(explode("/", $cells[6]->getValue())));
                        $cliente->pessoa_fisica = 1;
                        $cliente->pessoa_juridica = 0;
                        $cliente->codigo_externo = $cells[0]->getValue();

                        if ($cells[8]->getValue() == "RESPONSÁVEL FINANCEIRO") {
                            $cliente->quantidade_vidas = $cells[15]->getValue();
                        } else {
                            $cliente->quantidade_vidas = $cells[15]->getValue() + 1;
                        }
//
                        $cliente->save();
                        $data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato = new Contrato();
                        //$contrato->acomodacao_id = $acomodacao_id;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = 4;
                        $contrato->tabela_origens_id = $cidade_id;
                        $contrato->plano_id = 1;
                        $contrato->financeiro_id = 5;
                        $contrato->data_vigencia = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato->codigo_externo = $cells[0]->getValue();
                        $contrato->data_boleto = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                        $contrato->valor_adesao = str_replace([".",","],["","."], $cells[12]->getValue());
                        $contrato->valor_plano = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                        $contrato->coparticipacao = 1;
                        $contrato->odonto = 0;
                        $contrato->created_at = $data_vigencia;
                        $contrato->desconto_corretor = "0,00";
                        $contrato->desconto_corretora = "0,00";
                        $contrato->save();
                        $comissao = new Comissoes();
                        $comissao->contrato_id = $contrato->id;
                        // $comissao->cliente_id = $contrato->cliente_id;
                        $comissao->user_id = $user_id;
                        // $comissao->status = 1;
                        $comissao->plano_id = 1;
                        $comissao->administradora_id = 4;
                        $comissao->tabela_origens_id = $cidade_id;
                        $comissao->data = date('Y-m-d');
                        $comissao->save();

                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                            ::where("plano_id", 1)
                            ->where("administradora_id", 4)
                            ->where("user_id", $user_id)
                            //->where("tabela_origens_id", 2)
                            ->get();
//
                        $comissao_corretor_contagem = 0;
                        $comissao_corretor_default = 0;
//
//
                        if (count($comissoes_configuradas_corretor) >= 1) {
                            foreach ($comissoes_configuradas_corretor as $c) {
                                $valor_comissao = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                //$comissaoVendedor->user_id = auth()->user()->id;
                                // $comissaoVendedor->documento_gerador = "12345678";
                                $comissaoVendedor->parcela = $c->parcela;
                                $comissaoVendedor->valor = ($valor_comissao * $c->valor) / 100;
                                if ($comissao_corretor_contagem == 0) {
                                    $comissaoVendedor->data = $data_vigencia;
                                    $comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                    }
                                    $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                    $comissaoVendedor->valor_pago = str_replace([".",","],["","."], $cells[12]->getValue());
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_contagem}month"));

                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {

                                        $ano = explode("-", $dates)[0];

                                        $comissaoVendedor->data = date($ano."-02-28");
                                        $ano = explode("-", $comissaoVendedor->data)[0];
                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));

                                        if ($bissexto == 1) {
                                            $comissaoVendedor->data = date($ano."-02-29");
                                        } else {

                                            $comissaoVendedor->data = date($ano."-02-28");
                                        }

                                    } else {
                                        $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
//
                                }
                                $comissaoVendedor->save();
                                $comissao_corretor_contagem++;
                            }
                        } else {

                            $dados = ComissoesCorretoresDefault
                                ::where("plano_id", 1)
                                ->where("administradora_id", 4)
                                //->where("tabela_origens_id", 2)
                                ->get();
                            foreach ($dados as $c) {
                                $valor_comissao_default = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                $comissaoVendedor->parcela = $c->parcela;
                                $comissaoVendedor->valor = ($valor_comissao_default * $c->valor) / 100;
                                if ($comissao_corretor_default == 0) {
                                    $comissaoVendedor->data = $data_vigencia;
                                    $comissaoVendedor->status_financeiro = 1;
                                    if ($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {

                                    }
                                    $comissaoVendedor->data_baixa = implode("-", array_reverse(explode("/", $cells[17]->getValue())));
                                    $comissaoVendedor->valor_pago = str_replace([".",","],["","."], $cells[12]->getValue());
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissao_corretor_default}month"));

                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {
                                        $ano = explode("-", $dates)[0];
                                        $comissaoVendedor->data = date($ano."-02-28");

                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                        if ($bissexto == 1) {
                                            $comissaoVendedor->data = date($ano."-02-29");
                                        } else {
                                            $comissaoVendedor->data = date($ano."-02-28");
                                        }
                                    } else {
                                        $comissaoVendedor->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
                                }
                                $comissaoVendedor->save();
                                $comissao_corretor_default++;
                            }
                        //}
                        /****FIm SE Comissoes Lancadas */
                    }


                        $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes
                                ::where("administradora_id", 4)
                                ->where('plano_id', 1)
                                //->where('tabela_origens_id', 2)
                                ->get();
                        $comissoes_corretora_contagem = 0;
                        if (count($comissoes_configurada_corretora) >= 1) {
                            foreach ($comissoes_configurada_corretora as $cc) {
                                $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                                $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                                $comissaoCorretoraLancadas->parcela = $cc->parcela;
                                if ($comissoes_corretora_contagem == 0) {
                                    $comissaoCorretoraLancadas->data = $data_vigencia;
                                    $comissaoCorretoraLancadas->status_financeiro = 1;
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m", strtotime($data_vigencia));
                                    $dates = date("Y-m", strtotime($data_vigencia_sem_dia . "+{$comissoes_corretora_contagem}month"));
                                    $mes = explode("-", $dates)[1];
                                    if ($dia == 30 && $mes == 02) {
                                        $comissaoCorretoraLancadas->data = date("Y-02-28");
                                        $ano = explode("-", $comissaoCorretoraLancadas->data)[0];
                                        $bissexto = date('L', mktime(0, 0, 0, 1, 1, $ano));
                                        if ($bissexto == 1) {
                                            $comissaoCorretoraLancadas->data = date("Y-02-29");
                                        } else {
                                            $comissaoCorretoraLancadas->data = date("Y-02-28");
                                        }
                                    } else {
                                        $comissaoCorretoraLancadas->data = date("Y-m-" . $dia, strtotime($dates));
                                    }
                                }
                                $valor_cc = (float) str_replace([".",","],["","."], $cells[12]->getValue()) - 25;
                                $comissaoCorretoraLancadas->valor = ($valor_cc * $cc->valor) / 100;
                                $comissaoCorretoraLancadas->save();
                                $comissoes_corretora_contagem++;
                            }
                        }
                }
            //unlink("public/".$filename);
        }



    }
    }






        //Cliente::orderBy("id","desc")->first()->update(["last"=>1]);
        return "sucesso";
    }




    public function sincronizarBaixas(Request $request)
    {
        Cliente::where("dados",1)->whereRaw("baixa IS NULL")
        ->chunkById(50,function($clientes) {
            foreach($clientes as $cc) {
                $contrato = Contrato::where('cliente_id',$cc->id)->first()->id;
                $comissao_id = Comissoes::where("contrato_id",$contrato)->first()->id;
                ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->update(['documento_gerador'=>substr($cc->cateirinha,0,-3)]);
                $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario/$cc->cateirinha/financeiro/historico";
                $curl = curl_init($url);
                curl_setopt($curl,CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
                curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
                $resp=curl_exec($curl);
                curl_close($curl);
                $dados=json_decode($resp);
                $data="";
                $docu="";
                if(!empty($dados) && $dados != null) {
                    foreach($dados as $d) {
                        if($d->dtPagamento != null && $d->cdStatus != 16) {
                            // $data = implode("-",array_reverse(explode('/',$d->dtVencimento)));
                            $mes = explode("/",$d->dtVencimento);
                            $data_baixa = implode("-",array_reverse(explode('/',$d->dtPagamento)));
                            $docu = $d->cdDocumentoGerador;
                            ComissoesCorretoresLancadas
                                ::whereRaw("DATE_FORMAT(data,'%m') = ?",$mes[1])
                                ->where("documento_gerador",$docu)
                                ->where("status_financeiro","!=",1)
                                ->update([
                                        'status_financeiro'=>1,
                                        'valor_pago' => $d->vlObrigacao,
                                        'data_baixa'=>$data_baixa
                                ]);

                            ComissoesCorretoraLancadas
                                ::whereRaw("DATE_FORMAT(data,'%m') = ?",$mes[1])
                                ->where("comissoes_id",$comissao_id)
                                ->where("status_financeiro","!=",1)
                                ->update(['status_financeiro'=>1]);



                        }

                        if($d->cdStatus == 8 && $d->dsStatus == "CANCELADO") {
                            $canc = new ComissoesCorretoresCancelados();
                            $canc->comissoes_id = $comissao_id;
                            $canc->data = implode("-",array_reverse(explode('/',$d->dtVencimento)));
                            $canc->documento_gerador = $d->cdDocumentoGerador;
                            $canc->save();
                        }


                    }
                }

            }

        });


        $comissoes = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        //->where("status_gerente",1)
        ->where("parcela","!=",1)
        //->where("comissoes_id",$comissoes>id)

        ->get();
        foreach($comissoes as $cc) {

            switch($cc->parcela) {
                case 2:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 6
                    ]);

                break;

                case 3:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 7
                    ]);
                break;

                case 4:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 8
                    ]);
                break;

                case 5:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 9
                    ]);
                break;

                case 6:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 10
                    ]);
                break;

                default:
                    $contrato_id = Comissoes::where("id",$cc->comissoes_id)->first()->contrato_id;
                    Contrato::where("id",$contrato_id)->update([
                        "financeiro_id" => 11
                    ]);
                break;
            }
        }

        $dados = DB::select('
            SELECT * FROM comissoes_corretores_cancelados
            INNER JOIN comissoes_corretores_lancadas ON
            comissoes_corretores_cancelados.documento_gerador = comissoes_corretores_lancadas.documento_gerador
            WHERE MONTH(comissoes_corretores_lancadas.`data`) = MONTH(comissoes_corretores_cancelados.data)
            AND valor_pago IS NULL
            GROUP BY comissoes_corretores_cancelados.documento_gerador
        ');

        foreach($dados as $d) {
            $contrato_id = Comissoes::where("id",$d->comissoes_id)->first()->contrato_id;
            Contrato::where("id",$contrato_id)->update(["financeiro_id"=>12]);
        }

        $canc = DB::select("
            SELECT * FROM comissoes_corretores_cancelados
            INNER JOIN comissoes_corretores_lancadas ON
            comissoes_corretores_cancelados.documento_gerador = comissoes_corretores_lancadas.documento_gerador
            WHERE MONTH(comissoes_corretores_lancadas.`data`) = MONTH(comissoes_corretores_cancelados.data)
            AND valor_pago IS NULL AND comissoes_corretores_lancadas.`data` >= DATE(NOW() - INTERVAL 6 MONTH)
            GROUP BY comissoes_corretores_cancelados.documento_gerador
        ");

        foreach($canc as $c) {
            DB::table('comissoes_corretores_lancadas')
            ->where("id","=",$c->id)
            ->whereRaw("data_baixa IS NULL")
            ->update(['cancelados'=>1]);
        }

        Cliente::whereRaw("baixa IS NULL")->update(["baixa"=>date('Y-m-d')]);


        return "sucesso";
    }


    private function verificarCarteirinha($nome,$codigo)
    {
        $carteirinha = Cliente
            ::select('clientes.*')
            ->join('users','users.id','=','clientes.user_id')
            ->join('contratos','contratos.cliente_id','=','clientes.id')
            ->where('clientes.nome','like',$nome)
            ->where('users.codigo_vendedor',$codigo)
            ->whereNull("cateirinha")
            ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
            ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
            ->first();

        if($carteirinha) {
            return $carteirinha->id;
        } else {
            return null;
        }

    }
    
    
    public function sincronizarBaixasJaExiste(Request $request)
    {
        set_time_limit(300);
        $filename = uniqid() . ".xlsx";
        if (move_uploaded_file($request->file, $filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            $cidade = "";
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    if($rowNumber > 1) {




                        $cells = $row->getCells();

                        $nome = $cells[7]->getValue();
                        $codi = $cells[4]->getValue();

                        //echo $cells[11]->getValue()."<br />";

                        if($cells[11]->getValue() == "CANCELADO") {

                            $spreadsheetCodeCancelado = $cells[5]->getValue();

                            $carteirinha_existe_cancelado = Cliente
                                ::select('clientes.*')
                                ->join('users','users.id','=','clientes.user_id')
                                ->join('contratos','contratos.cliente_id','=','clientes.id')
                                ->whereRaw("LEFT(cateirinha, 11) = ?", [$spreadsheetCodeCancelado])
                                ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
                                ->with(['user','contrato'])
                                ->first();
                            $contrato_id_cancelado = $carteirinha_existe_cancelado->contrato->id;
                            //Contrato::where("id",$contrato_id_cancelado)->update(["financeiro_id" => 6]);
                            $alt = Contrato::find($contrato_id_cancelado);
                            $alt->cancelado_confirmacao = 1;
                            $alt->save();
                            //echo $nome_existe."<br />";
                            //$contrato_id_cancelado = Contrato::where("cliente_id",$id_alt)->first()->id;
                            //echo $contrato_id_cancelado."<br />";
                        }






                        if($id_alt = $this->verificarCarteirinha($nome,$codi)) {




                              $cliente_alt = Cliente::find($id_alt);
                              $cliente_alt->cateirinha = $cells[5]->getValue();
                              $cliente_alt->save();

                              $contrato_id = Contrato::where("cliente_id",$id_alt)->first()->id;
                              $cc = Contrato::find($contrato_id);
                              $cc->created_at = $cells[6]->getValue()->format('Y-m-d');
                              $cc->save();


                              //$contrato_id_cancelado = $cliente_alt->contrato->id;




                            if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {
                                if($cliente_alt) {
                                    $comissoes = $cliente_alt->contrato->comissao->comissoesLancadas;
                                    $vencimento = $cells[13]->getValue()->format('Y-m-d');
                                    $dt_pagamento = $cells[12]->getValue()->format('Y-m-d');
                                    foreach($comissoes as $c) {
                                        if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
                                            ComissoesCorretoresLancadas::find($c->id)->update([
                                                'status_financeiro'=>1,
                                                'data_baixa' => $dt_pagamento,
                                                'valor_pago' => $cells[10]->getValue()
                                            ]);
                                            switch($c->parcela) {
                                                case 2:
                                                    $contrato_id=Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 6]);
                                                break;

                                                case 3:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 7]);
                                                break;

                                                case 4:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 8]);
                                                break;

                                                case 5:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 9]);
                                                    break;

                                                case 6:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 11]);
                                                    break;

                                                default:

                                                    break;
                                            }
                                        }
                                    }
                                }
                            }
                        } else {

                            $nome_existe = $cells[7]->getValue();
                            $codi_existe = $cells[4]->getValue();




                            $spreadsheetCode = $cells[5]->getValue();

                            $carteirinha_existe = Cliente
                                ::select('clientes.*')
                                ->join('users','users.id','=','clientes.user_id')
                                ->join('contratos','contratos.cliente_id','=','clientes.id')
                                ->whereRaw("LEFT(cateirinha, 11) = ?", [$spreadsheetCode])
                                ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
                                ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
                                ->first();

                            if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {
                                if($carteirinha_existe) {
                                    $comissoes = $carteirinha_existe->contrato->comissao->comissoesLancadas;
                                    $vencimento = $cells[13]->getValue()->format('Y-m-d');
                                    $dt_pagamento = $cells[12]->getValue()->format('Y-m-d');
                                    foreach($comissoes as $c) {
                                        if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
                                            ComissoesCorretoresLancadas::find($c->id)->update([
                                                'status_financeiro'=>1,
                                                'data_baixa' => $dt_pagamento,
                                                'valor_pago' => $cells[10]->getValue()
                                            ]);
                                            switch($c->parcela) {
                                                case 2:
                                                    $contrato_id=Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 6]);
                                                    break;

                                                case 3:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 7]);
                                                    break;

                                                case 4:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 8]);
                                                    break;

                                                case 5:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 9]);
                                                    break;

                                                case 6:
                                                    $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
                                                    Contrato::where("id",$contrato_id)->update(["financeiro_id" => 11]);
                                                    break;

                                                default:

                                                    break;
                                            }
                                        }
                                    }
                                }
                            }


                        }
                    }
                }
            }
        }
        return "successo";
    } 
    
    
    
    
    
    

    // public function sincronizarBaixasJaExiste(Request $request)
    // {
    //     set_time_limit(300);
    //     $filename = uniqid() . ".xlsx";
    //     if (move_uploaded_file($request->file, $filename)) {
    //         $filePath = base_path("public/{$filename}");
    //         $cpfs = [];
    //         $reader = ReaderEntityFactory::createReaderFromFile($filePath);
    //         $reader->open($filePath);
    //         $cidade = "";
    //         foreach ($reader->getSheetIterator() as $sheet) {
    //             foreach ($sheet->getRowIterator() as $rowNumber => $row) {
    //                 if($rowNumber > 1) {
    //                     $cells = $row->getCells();

    //                     $nome = $cells[7]->getValue();
    //                     $codi = $cells[4]->getValue();

    //                     if($id_alt = $this->verificarCarteirinha($nome,$codi)) {
    //                           $cliente_alt = Cliente::find($id_alt);
    //                           $cliente_alt->cateirinha = $cells[5]->getValue();
    //                           $cliente_alt->save();
    //                           $contrato_id = Contrato::where("cliente_id",$id_alt)->first()->id;

    //                           $cc = Contrato::find($contrato_id);
    //                           $cc->created_at = $cells[6]->getValue()->format('Y-m-d');
    //                           $cc->save();

    //                         if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {



    //                             if($cliente_alt) {

    //                                 $comissoes = $cliente_alt->contrato->comissao->comissoesLancadas;
    //                                 $vencimento = $cells[13]->getValue()->format('Y-m-d');
    //                                 $dt_pagamento = $cells[12]->getValue()->format('Y-m-d');
    //                                 foreach($comissoes as $c) {
    //                                     if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
    //                                         ComissoesCorretoresLancadas::find($c->id)->update([
    //                                             'status_financeiro'=>1,
    //                                             'data_baixa' => $dt_pagamento,
    //                                             'valor_pago' => $cells[10]->getValue()
    //                                         ]);
    //                                         switch($c->parcela) {
    //                                             case 2:
    //                                                 $contrato_id=Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 6]);
    //                                             break;

    //                                             case 3:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 7]);
    //                                             break;

    //                                             case 4:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 8]);
    //                                             break;

    //                                             case 5:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 9]);
    //                                                 break;

    //                                             case 6:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 11]);
    //                                                 break;

    //                                             default:

    //                                                 break;
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                     } else {

    //                         $nome_existe = $cells[7]->getValue();
    //                         $codi_existe = $cells[4]->getValue();

    //                         $spreadsheetCode = $cells[5]->getValue();

    //                         $carteirinha_existe = Cliente
    //                             ::select('clientes.*')
    //                             ->join('users','users.id','=','clientes.user_id')
    //                             ->join('contratos','contratos.cliente_id','=','clientes.id')
    //                             ->whereRaw("LEFT(cateirinha, 11) = ?", [$spreadsheetCode])
    //                             ///->whereDate('contratos.created_at',$cells[6]->getValue()->format('Y-m-d'))
    //                             ->with(['user','contrato','contrato.comissao','contrato.comissao.comissoesLancadas'])
    //                             ->first();

    //                         if($cells[11]->getValue() == "LIQUIDADO" || $cells[11]->getValue() == "LIQUIDADO N/COB") {
    //                             if($carteirinha_existe) {
    //                                 $comissoes = $carteirinha_existe->contrato->comissao->comissoesLancadas;
    //                                 $vencimento = $cells[13]->getValue()->format('Y-m-d');
    //                                 $dt_pagamento = $cells[12]->getValue()->format('Y-m-d');
    //                                 foreach($comissoes as $c) {
    //                                     if(date('m', strtotime($c->data)) == date('m', strtotime($vencimento))) {
    //                                         ComissoesCorretoresLancadas::find($c->id)->update([
    //                                             'status_financeiro'=>1,
    //                                             'data_baixa' => $dt_pagamento,
    //                                             'valor_pago' => $cells[10]->getValue()
    //                                         ]);
    //                                         switch($c->parcela) {
    //                                             case 2:
    //                                                 $contrato_id=Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 6]);
    //                                                 break;

    //                                             case 3:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 7]);
    //                                                 break;

    //                                             case 4:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 8]);
    //                                                 break;

    //                                             case 5:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 9]);
    //                                                 break;

    //                                             case 6:
    //                                                 $contrato_id = Comissoes::where("id",$c->comissoes_id)->first()->contrato_id;
    //                                                 Contrato::where("id",$contrato_id)->update(["financeiro_id" => 11]);
    //                                                 break;

    //                                             default:

    //                                                 break;
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }


    //                     }
    //                 }
    //             }
    //         }
    //     }
    //     return "successo";
    // }


    public function verificardependentesuser(Request $request)
    {
        $id = $request->id;
        $cpf = Cliente::find($id)->cpf;
        $cliente = Cliente::find($id);
        $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario?cpf=$cpf";
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $resultado = (array) json_decode(curl_exec($ch),true);
        $dados = [];
        foreach($resultado as $rr) {
            if($rr['tipoUsuarioC'] == "DEPENDENTE") {
                $cliente->dependente = 1;
                $cliente->save();
                if(!in_array($rr['nmUsuarioC'],$dados) && !in_array($rr['nuCpfC'],$dados)) {
                    $dependentes = new Dependentes();
                    $dependentes->cliente_id = $id;
                    $dependentes->nome = mb_convert_case($rr['nmUsuarioC'], MB_CASE_TITLE, "UTF-8");
                    $dependentes->cpf = $rr['nuCpfC'];
                    $dependentes->save();
                }
                array_push($dados,$rr['nmUsuarioC']);
                array_push($dados,$rr['nuCpfC']);
            } else {
                $cliente->dependente = 0;
                $cliente->save();
            }
        }
        return true;
    }






    public function changeclienteuser(Request $request)
    {
        // return $request->id."-".$request->cliente;

        $cliente = Cliente::find($request->cliente);
        $cliente->user_id = $request->id;
        $cliente->save();

        $contrato = Contrato::where("cliente_id",$request->cliente)->first()->id;

        $comissao = Comissoes::where("contrato_id",$contrato)->first();
        $comissao->user_id = $request->id;
        $comissao->save();



        $user = User::find($request->id)->name;

        return $user;


    }





    public function detalhesContrato($id)
    {
        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->first();

        $users = User::where("id","!=",auth()->user()->id)->get();
        $dependente = $contratos->clientes->dependente;

        return view('admin.pages.financeiro.detalhe',[
            "dados" => $contratos,
            "users" => $users,
            "dependente" => $dependente,
            "plano" => $contratos->plano->id
        ]);
    }




    private function getjaCarteirinha($carteirinha)
    {
        $cliente = Cliente::where("cateirinha",$carteirinha);
        if($cliente->first()) {
            return true;
        } else {
            return false;
        }
    }



    public function atualizarDados(Request $request)
    {
        $clientes = Cliente::with('contrato')->where("dados",0)->whereRaw("baixa IS NULL")
        ->chunkById(50,function($clientes) {
            foreach($clientes as $v) {
                $url = "https://api-hapvida.sensedia.com/DESATIVADO_/wssrvonline/v1/beneficiario?cpf=$v->cpf";
                $ch = curl_init($url);
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $resultado = (array) json_decode(curl_exec($ch),true);
                foreach($resultado as $rr) {
                    if($rr['tipoPlanoC'] == "SAUDE" AND $rr['nomeEmpresa'] == "I N D I V I D U A L" AND $rr['nuMatriculaEmpresa'] == $v->codigo_externo) {
                            $cliente = Cliente::where("codigo_externo",$v->codigo_externo)->first();
                            $cliente->cidade = mb_convert_case($rr['cidadeEndereco'], MB_CASE_TITLE, "UTF-8");
                            $cliente->cep = $rr['cepEndereco'];
                            $cliente->rua = $rr['ruaEndereco'];
                            $cliente->bairro =  mb_convert_case($rr['bairroEndereco'], MB_CASE_TITLE, "UTF-8");
                            $cliente->complemento = ($rr['complementoEndereco'] != null ? mb_convert_case($rr['complementoEndereco'], MB_CASE_TITLE, "UTF-8") : null);
                            $cliente->uf = $rr['ufEndereco'];
                            $cliente->pessoa_fisica = 1;
                            $cliente->pessoa_juridica = 0;
                            $cliente->nm_plano = $rr['nmPlano'];
                            $cliente->numero_registro_plano = $rr['nuRegistroPlano'];
                            $cliente->rede_plano = $rr['redePlano'];
                            $cliente->tipo_acomodacao_plano = $rr['tipoAcomodacaoPlano'];
                            $cliente->segmentacao_plano = $rr['segmentacaoPlano'];
                            $cliente->cateirinha = $rr['cdUsuario'];
                            $cliente->save();
                    }
                }
            }
        });

        Cliente::where("dados",0)->update(["dados"=>1]);
        //Cliente::whereRaw("baixa IS NULL")->update(["baixa"=>date('Y-m-d')]);

        return "sucesso";
    }

    public function baixaDaData(Request $request)
    {


        $parcela = ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("status_financeiro",0)->where("status_gerente",0)->first()->parcela;
        // $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = Contrato::find($id_contrato);

        switch ($parcela) {
            case 1:
                $contrato->financeiro_id = 4;
                $contrato->data_baixa = $request->data_baixa;
                $comissaoCorretor = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",1)
                    ->first();
                if($comissaoCorretor) {
                    $comissaoCorretor->status_financeiro = 1;
                    $comissaoCorretor->data_baixa = $request->data_baixa;
                    $comissaoCorretor->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",2)->update(['atual'=>1]);




                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',1)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }

            break;




            case 2:
                $contrato->financeiro_id = 6;
                $contrato->data_baixa = $request->data_baixa;
                $comissaoCorretor = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",2)
                    ->first();
                if($comissaoCorretor) {
                    $comissaoCorretor->status_financeiro = 1;
                    $comissaoCorretor->data_baixa = $request->data_baixa;
                    $comissaoCorretor->atual = 0;
                    $comissaoCorretor->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",3)->update(['atual'=>1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',2)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }

            break;

            case 3:

                $contrato->financeiro_id = 7;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",3)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",4)->update(['atual'=>1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',3)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }
            break;

            case 4:
                $contrato->financeiro_id = 8;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",4)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",5)->update(['atual'=>1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',4)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }
            break;

            case 5:
                $contrato->financeiro_id = 9;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",5)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",6)->update(['atual'=>1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',5)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }
            break;

            case 6:
                $contrato->financeiro_id = 10;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",6)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas::where("comissoes_id",$request->comissao_id)->where("parcela",7)->update(['atual'=>1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',6)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }
            break;

            case 7:
                $contrato->financeiro_id = 11;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",7)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',7)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }

            break;

            // case 10:

            //     //$contrato->financeiro_id = 11;
            //     $contrato->data_baixa = $request->data_baixa;
            //     $comissao = ComissoesCorretoresLancadas
            //         ::where("comissoes_id",$request->comissao_id)
            //         ->where("parcela",7)
            //         ->first();
            //     if($comissao) {
            //         $comissao->status_financeiro = 1;
            //         $comissao->data_baixa = $request->data_baixa;
            //         $comissao->save();
            //     }

            //     $comissaoCorretora = ComissoesCorretoraLancadas
            //         ::where('comissoes_id',$request->comissao_id)
            //         ->where('parcela',7)
            //         ->first();
            //     if(isset($comissaoCorretora) && $comissaoCorretora) {
            //         $comissaoCorretora->status_financeiro = 1;
            //         $comissaoCorretora->data_baixa = $request->data_baixa;
            //         $comissaoCorretora->save();
            //     }




            // break;


            default:
                return "error";
            break;
        }
        $contrato->save();
        return $this->recalcularColetivo();
    }

    public function baixaDaDataIndividual(Request $request)
    {
        $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = Contrato::find($id_contrato);

        switch ($contrato->financeiro_id) {
            case 5:
                //$contrato->financeiro_id = 6;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",1)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',1)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }


            break;

            case 6:
                //$contrato->financeiro_id = 7;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",2)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',2)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }




            break;

            case 7:
                //$contrato->financeiro_id = 8;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",3)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',3)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }



            break;

            case 8:
                //$contrato->financeiro_id = 9;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",4)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',4)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }


            break;

            case 9:
                //$contrato->financeiro_id = 10;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",5)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }
                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',5)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }




            break;

            case 10:
                //$contrato->financeiro_id = 11;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$request->comissao_id)
                    ->where("parcela",6)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                $comissaoCorretora = ComissoesCorretoraLancadas
                    ::where('comissoes_id',$request->comissao_id)
                    ->where('parcela',6)
                    ->first();
                if(isset($comissaoCorretora) && $comissaoCorretora) {
                    $comissaoCorretora->status_financeiro = 1;
                    $comissaoCorretora->data_baixa = $request->data_baixa;
                    $comissaoCorretora->save();
                }


            break;
        }
        $contrato->save();
        return $this->recalcularIndividual();
    }







    public function editarCampoIndividualmente(Request $request)
    {
        $cliente = Cliente::where("id",$request->id_cliente)->first();
        $dependente = Dependentes::where('cliente_id',$request->id_cliente)->first();
        $contrato = Contrato::where("cliente_id",$request->id_cliente)->first();

        switch($request->alvo) {

            case "codigo_externo":
                $contrato->codigo_externo = implode("-",array_reverse(explode("/",$request->valor)));
                $contrato->save();
            break;

            case "data_vigencia":
                $contrato->data_vigencia = implode("-",array_reverse(explode("/",$request->valor)));
                $contrato->save();
            break;

            case "desconto_corretora":
                $contrato->desconto_corretora = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
            break;

            case "desconto_corretor":
                $contrato->desconto_corretor = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
            break;



            case "cliente":

                $cliente->nome = $request->valor;
                $cliente->save();

            break;

            case "data_nascimento":

                $data = implode("-",array_reverse(explode("/",$request->valor)));
                $cliente->data_nascimento = $data;
                $cliente->save();

            break;

            case "cpf":

                $cliente->cpf = $request->valor;
                $cliente->save();

            break;

            case "nome_responsavel":

                if(!$dependente) {

                    $cad = new Dependentes();
                    $cad->cliente_id = $request->id_cliente;
                    $cad->nome = $request->valor;
                    $cad->save();
                } else {
                    $dependente->nome = $request->valor;
                    $dependente->save();
                }

            break;

            case "cpf_responsavel":

                if(!$dependente) {
                    $cad = new Dependentes();
                    $cad->cliente_id = $request->id_cliente;
                    $cad->cpf = $request->valor;
                    $cad->save();
                } else {
                    $dependente->cpf = $request->valor;
                    $dependente->save();
                }

            break;

            case "celular":

                $cliente->celular = $request->valor;
                $cliente->save();

            break;

            case "telefone":

                $cliente->telefone = $request->valor;
                $cliente->save();

            break;

            case "cep":

                $cliente->cep = $request->valor;
                $cliente->save();

            break;


            case "email":

                $cliente->email = $request->valor;
                $cliente->save();

            break;

            case "cidade":

                $cliente->cidade = $request->valor;
                $cliente->save();

            break;

            case "uf":

                $cliente->uf = $request->valor;
                $cliente->save();

            break;

            case "bairro":

                $cliente->bairro = $request->valor;
                $cliente->save();

            break;

            case "rua":

                $cliente->rua = $request->valor;
                $cliente->save();

            break;

            case "complemento":

                $cliente->complemento = $request->valor;
                $cliente->save();

            break;

            default:

            break;

            //$cliente->save();

        }
    }


    public function editarIndividualCampoIndividualmente(Request $request)
    {

        $cliente = Cliente::where("id",$request->id_cliente)->first();
        $dependente = Dependentes::where('cliente_id',$request->id_cliente)->first();

        switch($request->alvo) {

            case "cliente":

                $cliente->nome = $request->valor;
                $cliente->save();

            break;

            case "data_nascimento":

                $data = implode("-",array_reverse(explode("/",$request->valor)));
                $cliente->data_nascimento = $data;
                $cliente->save();

            break;

            case "cpf":

                $cliente->cpf = $request->valor;
                $cliente->save();

            break;

            case "responsavel_financeiro":

                $dependente->nome = $request->valor;
                $dependente->save();

            break;

            case "cpf_financeiro":

                $dependente->cpf = $request->valor;
                $dependente->save();

            break;

            case "celular_individual_view_input":

                $cliente->celular = $request->valor;
                $cliente->save();

            break;

            case "telefone_individual_view_input":

                $cliente->telefone = $request->valor;
                $cliente->save();

            break;

            case "cep_individual_cadastro":

                $cliente->cep = $request->valor;
                $cliente->save();

            break;


            case "email":

                $cliente->email = $request->valor;
                $cliente->save();

            break;

            case "cidade":

                $cliente->cidade = $request->valor;
                $cliente->save();

            break;

            case "uf":

                $cliente->uf = $request->valor;
                $cliente->save();

            break;

            case "bairro_individual_cadastro":

                $cliente->bairro = $request->valor;
                $cliente->save();

            break;

            case "rua_individual_cadastro":

                $cliente->rua = $request->valor;
                $cliente->save();

            break;

            case "complemento_individual_cadastro":

                $cliente->complemento = $request->valor;
                $cliente->save();

            break;

            default:

            break;



        }
    }







    public function recalcularColetivo()
    {


        $qtd_coletivo_em_analise = Contrato::where("financeiro_id",1)
            ->where("plano_id",3)
            ->count();

        $qtd_coletivo_emissao_boleto = Contrato::where("financeiro_id",2)
            ->where("plano_id",3)
            ->count();

        $qtd_coletivo_pg_adesao = Contrato::where('financeiro_id',3)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();


        $qtd_coletivo_pg_vigencia = Contrato::where('financeiro_id',4)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();



        $qtd_coletivo_02_parcela = Contrato::where('financeiro_id',6)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_03_parcela = Contrato::where('financeiro_id',7)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",4);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_04_parcela = Contrato::where('financeiro_id',8)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",5);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_05_parcela = Contrato::where('financeiro_id',9)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",6);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_06_parcela = Contrato::where('financeiro_id',10)
            ->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",7);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_coletivo_finalizado = Contrato::where('financeiro_id',11)->where("plano_id",3)->count();
        $qtd_coletivo_cancelado = Contrato::where('financeiro_id',12)->where("plano_id",3)->count();

        return [
            "qtd_coletivo_em_analise" => $qtd_coletivo_em_analise,
            "qtd_coletivo_emissao_boleto" => $qtd_coletivo_emissao_boleto,
            "qtd_coletivo_pg_adesao" => $qtd_coletivo_pg_adesao,
            "qtd_coletivo_pg_vigencia" => $qtd_coletivo_pg_vigencia,
            "qtd_coletivo_02_parcela" => $qtd_coletivo_02_parcela,
            "qtd_coletivo_03_parcela" => $qtd_coletivo_03_parcela,
            "qtd_coletivo_04_parcela" =>  $qtd_coletivo_04_parcela,
            "qtd_coletivo_05_parcela" => $qtd_coletivo_05_parcela,
            "qtd_coletivo_06_parcela" => $qtd_coletivo_06_parcela,
            "qtd_coletivo_finalizado" => $qtd_coletivo_finalizado,
            "qtd_coletivo_cancelado" => $qtd_coletivo_cancelado
        ];
    }

    public function recalcularIndividual()
    {
        $qtd_individual_pendentes = Contrato::where("plano_id",1)->count();



        $qtd_individual_em_analise = Contrato::where("financeiro_id",1)->where("plano_id",1)->count();
        $qtd_individual_01_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",5)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })->count();

        $qtd_individual_02_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",6)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_03_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",7)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_04_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",8)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",4);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_05_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",9)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro","=",0);
                $query->where("status_gerente",0);
                $query->where("parcela",5);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_06_parcela = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",10)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                //$query->where("parcela",6);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_finalizado = Contrato::where('financeiro_id',11)->where("plano_id",1)->count();
        $qtd_individual_cancelado = Contrato::where('financeiro_id',12)->where("plano_id",1)->count();

        return [
            "qtd_individual_em_analise" => $qtd_individual_em_analise,
            "qtd_individual_01_parcela" => $qtd_individual_01_parcela,
            "qtd_individual_02_parcela" => $qtd_individual_02_parcela,
            "qtd_individual_03_parcela" => $qtd_individual_03_parcela,
            "qtd_individual_04_parcela" =>  $qtd_individual_04_parcela,
            "qtd_individual_05_parcela" => $qtd_individual_05_parcela,
            "qtd_individual_06_parcela" => $qtd_individual_06_parcela,
            "qtd_individual_finalizado" => $qtd_individual_finalizado,
            "qtd_individual_cancelado" => $qtd_individual_cancelado,
            "qtd_individual_pendentes" => $qtd_individual_pendentes
        ];
    }

    public function recalcularEmpresarial()
    {
        $qtd_empresarial_em_analise = ContratoEmpresarial::where("financeiro_id",1)->count();

        $qtd_empresarial_pendentes = ContratoEmpresarial::count();

        $qtd_empresarial_parcela_01 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",1);
            $query->whereRaw("data_baixa IS NULL");
        })

        ->where("financeiro_id",5)
        ->count();

        $qtd_empresarial_parcela_02 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",2);
            $query->whereRaw("data_baixa IS NULL");
        })

        ->where("financeiro_id",6)
        ->count();




        $qtd_empresarial_parcela_03 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",3);
            $query->whereRaw("data_baixa IS NULL");
        })

        ->where("financeiro_id",7)
        ->count();

        $qtd_empresarial_parcela_04 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",4);
            $query->whereRaw("data_baixa IS NULL");
        })
        ->where("financeiro_id",8)
        ->count();

        $qtd_empresarial_parcela_05 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",5);
            $query->whereRaw("data_baixa IS NULL");
        })
        ->where("financeiro_id",9)
        ->count();


        $qtd_empresarial_parcela_06 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",6);
            $query->whereRaw("data_baixa IS NULL");
        })
        ->where("financeiro_id",10)->count();

        $qtd_empresarial_finalizado = ContratoEmpresarial::where("financeiro_id",11)->count();

        $qtd_empresarial_cancelado = ContratoEmpresarial::where("financeiro_id",12)->count();


        return [
            "qtd_empresarial_em_analise" => $qtd_empresarial_em_analise,
            "qtd_empresarial_01_parcela" => $qtd_empresarial_parcela_01,
            "qtd_empresarial_02_parcela" => $qtd_empresarial_parcela_02,
            "qtd_empresarial_03_parcela" => $qtd_empresarial_parcela_03,
            "qtd_empresarial_04_parcela" => $qtd_empresarial_parcela_04,
            "qtd_empresarial_05_parcela" => $qtd_empresarial_parcela_05,
            "qtd_empresarial_06_parcela" => $qtd_empresarial_parcela_06,
            "qtd_empresarial_finalizado" => $qtd_empresarial_finalizado,
            "qtd_empresarial_cancelado" => $qtd_empresarial_cancelado,
            "qtd_empresarial_pendentes" => $qtd_empresarial_pendentes
        ];
    }

    public function geralIndividualPendentes(Request $request)
    {
        if ($request->ajax()) {

            if($request->mes == '00' || !isset($request->mes)) {

                $cacheKey = 'geralIndividualPendentesSemNada';
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () {
                    return DB::select("
                        SELECT
                            DATE_FORMAT(contratos.created_at, '%d/%m/%Y') AS data,
                            contratos.codigo_externo AS orcamento,
                            users.name AS corretor,
                            clientes.nome AS cliente,
                            clientes.data_nascimento AS data_nascimento,
                            clientes.celular AS telefone,
                            clientes.cpf AS cpf,
                            clientes.quantidade_vidas AS quantidade_vidas,
                            contratos.valor_plano AS valor_plano,
                            contratos.id,
                            estagio_financeiros.nome AS parcelas,
                            (SELECT nome FROM tabela_origens WHERE tabela_origens.id = contratos.tabela_origens_id) as cidade,
                            DATE_FORMAT(comissoes_corretores_lancadas.data, '%d/%m/%Y') AS vencimento,
                            CASE
                                WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                ELSE 'Aprovado'
                            END AS status,
                            IFNULL(atrasos.qtd_atrasos, 0) AS parcelas_atrasadas,
                            CASE
                                WHEN IFNULL(atrasos.qtd_atrasos, 0) >= 3 THEN 'Sim'
                                ELSE 'Não'
                            END AS acima,
                            CASE
                                WHEN estagio_financeiros.nome IN ('Cancelado', 'Finalizado') THEN 'nao_interessa'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Aprovado' THEN 'nao_interessa'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' AND IFNULL(atrasos.qtd_atrasos, 0) >= 3 THEN
                                    CASE
                                        WHEN DATEDIFF(CURDATE(), comissoes_corretores_lancadas.data) <= 90 THEN 'nao_interessa'
                                        ELSE 'provavel_cancelado'
                                    END
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' AND IFNULL(atrasos.qtd_atrasos, 0) < 3 AND DATEDIFF(CURDATE(), comissoes_corretores_lancadas.data) > 90 THEN 'provavel_cancelado'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' THEN 'provavel_atrasado'
                                ELSE 'certo'
                            END AS possivel
                        FROM comissoes_corretores_lancadas
                        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                        INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                        INNER JOIN clientes ON clientes.id = contratos.cliente_id
                        INNER JOIN users ON users.id = clientes.user_id
                        INNER JOIN tabela_origens ON tabela_origens.id = contratos.tabela_origens_id
                        INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                        LEFT JOIN (
                            SELECT
                                comissoes.contrato_id,
                                COUNT(*) AS qtd_atrasos
                            FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            WHERE comissoes_corretores_lancadas.status_financeiro = 0
                            GROUP BY comissoes.contrato_id
                        ) AS atrasos ON atrasos.contrato_id = contratos.id
                        WHERE
                            contratos.plano_id = 1 AND (
                                (comissoes_corretores_lancadas.data_baixa IS NULL AND comissoes_corretores_lancadas.status_financeiro = 0)
                                OR (comissoes_corretores_lancadas.parcela = 6 AND comissoes_corretores_lancadas.status_financeiro = 1)
                            )
                        GROUP BY comissoes_corretores_lancadas.comissoes_id;



                    ");
                });







            } else {
                $mes = $request->mes;
                $cacheKey = "geralIndividualPendentes{$mes}";
                $tempoDeExpiracao = 60;
                $resultado = Cache::remember($cacheKey, $tempoDeExpiracao, function () use($mes) {
                    return DB::select("
                        SELECT
                            DATE_FORMAT(contratos.created_at, '%d/%m/%Y') AS data,
                            contratos.codigo_externo AS orcamento,
                            users.name AS corretor,
                            clientes.nome AS cliente,
                            clientes.data_nascimento AS data_nascimento,
                            clientes.celular AS telefone,
                            clientes.cpf AS cpf,
                            clientes.quantidade_vidas AS quantidade_vidas,
                            contratos.valor_plano AS valor_plano,
                            contratos.id,
                            estagio_financeiros.nome AS parcelas,
                            DATE_FORMAT(comissoes_corretores_lancadas.data, '%d/%m/%Y') AS vencimento,
                            CASE
                                WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                ELSE 'Aprovado'
                            END AS status,
                            IFNULL(atrasos.qtd_atrasos, 0) AS parcelas_atrasadas,
                            CASE
                                WHEN IFNULL(atrasos.qtd_atrasos, 0) >= 3 THEN 'Sim'
                                ELSE 'Não'
                            END AS acima,
                            CASE
                                WHEN estagio_financeiros.nome IN ('Cancelado', 'Finalizado') THEN 'nao_interessa'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Aprovado' THEN 'nao_interessa'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' AND IFNULL(atrasos.qtd_atrasos, 0) >= 3 THEN
                                    CASE
                                        WHEN DATEDIFF(CURDATE(), comissoes_corretores_lancadas.data) <= 90 THEN 'nao_interessa'
                                        ELSE 'provavel_cancelado'
                                    END
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' AND IFNULL(atrasos.qtd_atrasos, 0) < 3 AND DATEDIFF(CURDATE(), comissoes_corretores_lancadas.data) > 90 THEN 'provavel_cancelado'
                                WHEN (CASE
                                          WHEN comissoes_corretores_lancadas.data < DATE_SUB(CURDATE(), INTERVAL 3 DAY) AND estagio_financeiros.id != 10 THEN 'Atrasado'
                                          ELSE 'Aprovado'
                                      END) = 'Atrasado' THEN 'provavel_atrasado'
                                ELSE 'certo'
                            END AS possivel
                        FROM comissoes_corretores_lancadas
                        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                        INNER JOIN contratos ON contratos.id = comissoes.contrato_id
                        INNER JOIN clientes ON clientes.id = contratos.cliente_id
                        INNER JOIN users ON users.id = clientes.user_id
                        INNER JOIN estagio_financeiros ON estagio_financeiros.id = contratos.financeiro_id
                        LEFT JOIN (
                            SELECT
                                comissoes.contrato_id,
                                COUNT(*) AS qtd_atrasos
                            FROM comissoes_corretores_lancadas
                            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                            WHERE comissoes_corretores_lancadas.status_financeiro = 0
                            GROUP BY comissoes.contrato_id
                        ) AS atrasos ON atrasos.contrato_id = contratos.id
                        WHERE
                            contratos.plano_id = 1 AND (
                                (comissoes_corretores_lancadas.data_baixa IS NULL AND comissoes_corretores_lancadas.status_financeiro = 0)
                                OR (comissoes_corretores_lancadas.parcela = 6 AND comissoes_corretores_lancadas.status_financeiro = 1
										  )
                            )
                        AND
										  clientes.cateirinha IS NOT NULL and month(contratos.created_at) = {$mes}
                        GROUP BY comissoes_corretores_lancadas.comissoes_id;
                    ");
                });
            }
            return response()->json($resultado);
        }
    }








}
