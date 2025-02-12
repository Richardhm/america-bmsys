<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTimeInterface;

use App\Models\{
    Contrato,Cliente,TabelaOrigens,Administradoras,Planos,Acomodacao,CotacaoFaixaEtaria,User,PlanoEmpresarial,ContratoEmpresarial,
    Comissoes,ComissoesCorretoresLancadas,ComissoesCorretoraConfiguracoes,ComissoesCorretoraLancadas,ComissoesCorretoresConfiguracoes,ComissoesCorretoresCancelados,
    Dependentes,Cancelado,MotivoCancelados,
    Premiacoes,PremiacoesCorretoraLancadas,PremiacoesCorretoresLancadas,PremiacoesCorretoraConfiguracoes,PremiacoesCorretoresConfiguracoes,ComissoesCorretoresDefault
};
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class FinanceiroController extends Controller
{
    public function __construct()
    {
        $this->middleware(["can:financeiro"]);
    }

    public function index()
    {

        




        
//        $atrasados = Contrato
//            ::where("contratos.plano_id",1)
//            ->join("comissoes","comissoes.contrato_id","=","contratos.id")
//            //            //->where("financeiro_id","!=",12)
//            ->whereHas('comissao.comissoesLancadas',function($query){
//                $query->whereRaw("DATA < CURDATE()");
//                //$query->whereRaw("valor > 0");
//                $query->whereRaw("data_baixa IS NULL");
//                $query->groupBy("comissoes_id");
//                //$query->orderBy("data","desc");
//            })
//
//            // ->whereHas('comissao.comissoesLancadas.comissaoAtualFinanceiro',function($query){
//            //     $query->orderBy("data","desc");
//            // })
//
//
//
//            ->first();
//
//
//
//        dd($atrasados);

        // $contratos = Contrato
        // ::where("plano_id",1)
        // ->whereHas('clientes',function($query){
        //     $query->whereRaw("cateirinha IS NULL");
        // })
        // ->with(['administradora','financeiro','cidade','comissao','plano','comissao.comissaoAtualFinanceiro','clientes','clientes.user'])
        // ->first();
        // dd($contratos);





          // $qtd_individual_atrasado = Contrato
          //       ::where("plano_id",1)
          //       ->where("financeiro_id","!=",12)
          //       ->whereHas('comissao.comissoesLancadas',function($query){
          //           $query->whereRaw("DATA < CURDATE()");
          //           $query->whereRaw("data_baixa IS NULL");
          //           $query->groupBy("comissoes_id");
          //       })
          //       ->whereHas('clientes',function($query){
          //           $query->whereRaw('cateirinha IS NOT NULL');
          //           $query->where("user_id",21);
          //       })

          //       ->get();

          //   foreach($qtd_individual_atrasado as $qia) {
          //       echo $qia->clientes->nome."<br />";
          //   }

          //   dd($qtd_individual_atrasado);




        $clientes = Contrato
                ::where("plano_id",1)
                    ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    //$query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                    $query->orderBy("data");
                })
                ->whereHas('clientes',function($query){
                    $query->whereRaw('cateirinha IS NOT NULL');
                })
                ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                ->get();

        $anos_coletivo = DB::select('SELECT YEAR(created_at) as anos FROM contratos WHERE plano_id = 3 GROUP BY YEAR(created_at)');
        $meses = ["01" => "Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro"];

        $contratos_coletivo_total = Contrato
        ::where("plano_id",3)
        ->count();


        $cidades = TabelaOrigens::all();
        $administradoras = Administradoras::whereRaw("id != (SELECT id FROM administradoras WHERE nome LIKE '%hapvida%')")->get();

        $planos = Planos::all();
        $plano_empresarial = PlanoEmpresarial::all();
        $users = User::where("id","!=",auth()->user()->id)->orderBy('name')->get();

        $tabela_origem = TabelaOrigens::all();

        $qtd_individual_pendentes = Contrato::where("plano_id",1)->whereHas('clientes',function($query){
            $query->whereRaw('cateirinha IS NOT NULL');
        })->count();


        $qtd_individual_atrasado = Contrato
        ::where("plano_id",1)
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
        ->count();

        $qtd_individual_em_analise = Contrato::where("financeiro_id",1)->where("plano_id",1)->count();

        $qtd_individual_parcela_01 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",5)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",1);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        


        //dd($qtd_individual_parcela_01);

        $qtd_individual_parcela_02 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",6)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",2);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_parcela_03 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",7)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",3);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_parcela_04 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",8)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",4);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_parcela_05 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",9)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",5);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->count();

        $qtd_individual_parcela_06 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",10)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            // ->whereHas('comissao.comissoesLancadas',function($query){
            //     $query->where("parcela",6);
            // })
            ->whereDoesntHave('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
            })
            ->count();






        $qtd_individual_cancelado = Contrato::where("financeiro_id",12)
            ->where("plano_id",1)
            ->count();



        $qtd_coletivo_em_analise = Contrato::where("financeiro_id",1)->where("plano_id",3)->count();

        $qtd_coletivo_emissao_boleto = Contrato::where("financeiro_id",2)->where("plano_id",3)->count();

        $qtd_coletivo_pg_adesao = Contrato::where('financeiro_id',3)->where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->count();
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            //->count();

        $qtd_coletivo_pg_vigencia = Contrato
            //::where('financeiro_id',4)
            ::where("plano_id",3)

            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
                //$query->where("atual",1);
            })
            ->count();
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")


        $qtd_coletivo_03_parcela = Contrato
            //::where('financeiro_id',6)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->whereRaw("data_baixa IS NULL");
                $query->where("atual",1);
            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            // ->whereRaw("tempo >= now()")
            ->count();

        // $qtd_coletivo_03_parcela = Contrato
        //     //::where('financeiro_id',7)
        //     ::where("plano_id",3)
        //     ->whereHas('comissao.comissoesLancadas',function($query){
        //         $query->where("status_financeiro",0);
        //         $query->where("status_gerente",0);
        //         $query->where("parcela",3);
        //         $query->whereRaw("data_baixa IS NULL");
        //         $query->where("atual",1);
        //     })
        //     //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
        //     //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
        //     // ->whereRaw("tempo >= now()")
        //     ->count();

        $qtd_coletivo_04_parcela = Contrato
            //::where('financeiro_id',8)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",4);
                $query->whereRaw("data_baixa IS NULL");
                $query->where("atual",1);
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();

        $qtd_coletivo_05_parcela = Contrato
            //::where('financeiro_id',9)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",5);
                $query->whereRaw("data_baixa IS NULL");
                $query->where("atual",1);
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();

        $qtd_coletivo_06_parcela = Contrato
            //::where('financeiro_id',10)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",6);
                $query->whereRaw("data_baixa IS NULL");
                $query->where("atual",1);
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();

        $qtd_coletivo_07_parcela = Contrato
            //::where('financeiro_id',10)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",7);
                $query->whereRaw("data_baixa IS NULL");
                $query->where("atual",1);
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();

        $qtd_total_vidas_coletivo = CotacaoFaixaEtaria::selectRaw("sum(quantidade) as total_vidas")->first()->total_vidas;
        $qtd_total_valor_coletivo = Contrato::selectRaw("sum(valor_plano) as total_valor")->first()->total_valor;



        //$total = $qtd_coletivo_em_analise + $qtd_coletivo_emissao_boleto + $qtd_coletivo_pg_adesao + $qtd_coletivo_pg_vigencia + $qtd_coletivo_03_parcela + $qtd_coletivo_04_parcela + $qtd_coletivo_05_parcela + $qtd_coletivo_06_parcela;

        $qtd_coletivo_finalizados = Contrato
            //->where('financeiro_id',11)
            ::where("plano_id",3)
            ->whereHas('comissao.comissoesLancadas',function($query){

                $query->where("parcela",7);
                $query->where("status_financeiro",1);
                $query->whereRaw("data_baixa IS NOT NULL");
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();

        $qtd_coletivo_cancelados = Contrato::where('financeiro_id',12)
            ->where("plano_id",3)
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->count();
        $qtd_empresarial_pendentes = ContratoEmpresarial::count();
        $qtd_empresarial_em_analise = ContratoEmpresarial::where("financeiro_id",1)->count();
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
            $query->where("atual",1);
            $query->whereRaw("data_baixa IS NULL");
        })
        //->where("financeiro_id",6)
        ->count();
        $qtd_empresarial_parcela_03 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",3);
            $query->where("atual",1);
            $query->whereRaw("data_baixa IS NULL");
        })
        //->where("financeiro_id",7)
        ->count();
        $qtd_empresarial_parcela_04 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",4);
            $query->where("atual",1);
            $query->whereRaw("data_baixa IS NULL");
        })
        //->where("financeiro_id",8)
        ->count();
        $qtd_empresarial_parcela_05 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",5);
            $query->where("atual",1);
            $query->whereRaw("data_baixa IS NULL");
        })
        //->where("financeiro_id",9)
        ->count();
        $qtd_empresarial_parcela_06 = ContratoEmpresarial
        ::with("comissao")
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",0);
            $query->where("status_gerente",0);
            $query->where("parcela",6);
            $query->where("atual",1);
            $query->whereRaw("data_baixa IS NULL");
        })
        //->where("financeiro_id",10)
        ->count();
        $qtd_empresarial_finalizado = ContratoEmpresarial::where("financeiro_id",11)->count();
        $qtd_empresarial_cancelado = ContratoEmpresarial::where("financeiro_id",12)->count();
        return view('admin.pages.financeiro.index',[
            "cidades" => $cidades,
            "administradoras" => $administradoras,
            "planos" => $planos,
            "planos_empresarial" => $plano_empresarial,
            "users" => $users,
            "origem_tabela" => $tabela_origem,
            "anos_coletivo" => $anos_coletivo,
            "meses" => $meses,
            "qtd_individual_pendentes" => $qtd_individual_pendentes,
            "qtd_individual_parcela_01" => $qtd_individual_parcela_01,
            "qtd_individual_parcela_02" => $qtd_individual_parcela_02,
            "qtd_individual_parcela_03" => $qtd_individual_parcela_03,
            "qtd_individual_parcela_04" => $qtd_individual_parcela_04,
            "qtd_individual_parcela_05" => $qtd_individual_parcela_05,
            "qtd_individual_parcela_06" => $qtd_individual_parcela_06,
            "qtd_individual_em_analise" => $qtd_individual_em_analise,

            "qtd_individual_cancelado" => $qtd_individual_cancelado,

            "qtd_individual_atrasado" => $qtd_individual_atrasado,

            "qtd_coletivo_em_analise" => $qtd_coletivo_em_analise,
            "qtd_coletivo_emissao_boleto" => $qtd_coletivo_emissao_boleto,
            "qtd_coletivo_pg_adesao" => $qtd_coletivo_pg_adesao,
            "qtd_coletivo_pg_vigencia" => $qtd_coletivo_pg_vigencia,
            "qtd_coletivo_07_parcela" => $qtd_coletivo_07_parcela,
            "qtd_coletivo_03_parcela" => $qtd_coletivo_03_parcela,
            "qtd_coletivo_04_parcela" => $qtd_coletivo_04_parcela,
            "qtd_coletivo_05_parcela" => $qtd_coletivo_05_parcela,
            "qtd_coletivo_06_parcela" => $qtd_coletivo_06_parcela,
            "qtd_coletivo_finalizados" => $qtd_coletivo_finalizados,
            "qtd_coletivo_cancelados" => $qtd_coletivo_cancelados,
            "contratos_coletivo_total" => $contratos_coletivo_total,
            "qtd_total_vidas_coletivo" => $qtd_total_vidas_coletivo,
            "qtd_total_valor_coletivo" => $qtd_total_valor_coletivo,

            "qtd_empresarial_pendentes" => $qtd_empresarial_pendentes,
            "qtd_empresarial_parcela_01" => $qtd_empresarial_parcela_01,
            "qtd_empresarial_parcela_02" => $qtd_empresarial_parcela_02,
            "qtd_empresarial_parcela_03" => $qtd_empresarial_parcela_03,
            "qtd_empresarial_parcela_04" => $qtd_empresarial_parcela_04,
            "qtd_empresarial_parcela_05" => $qtd_empresarial_parcela_05,
            "qtd_empresarial_parcela_06" => $qtd_empresarial_parcela_06,
            "qtd_empresarial_em_analise" => $qtd_empresarial_em_analise,
            "qtd_empresarial_finalizado" => $qtd_empresarial_finalizado,
            "qtd_empresarial_cancelado" => $qtd_empresarial_cancelado
            //"total" => $total
        ]);
    }

    public function detalheEmpresarial($id)
    {
        $contratos = ContratoEmpresarial
        ::where("id",$id)
        ->select("*")
        ->selectRaw("(select name from users where users.id = contrato_empresarial.user_id) as vendedor")
        ->selectRaw("(select nome from planos where planos.id = contrato_empresarial.plano_id) as plano")
        ->selectRaw("(select nome from tabela_origens where tabela_origens.id = contrato_empresarial.tabela_origens_id) as tabela_origem")
        ->with(["financeiro","comissao","comissao.comissoesLancadas",'comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast'])
        ->first();

        //dd($contratos);

        $texto_empresarial = "";
        if($contratos->plano_contrado == 1) {
            $texto_empresarial = "C/ Copart + Odonto";
        } else if($contratos->plano_contrado == 2) {
            $texto_empresarial = "C/ Copart Sem Odonto";
        } else if($contratos->plano_contrado == 3) {
            $texto_empresarial = "Sem Copart + Odonto";
        } else if($contratos->plano_contrado == 4){
            $texto_empresarial = "Sem Copart Sem Odonto";
        } else {
            $texto_empresarial = "";
        }

        return view('admin.pages.financeiro.detalhe-empresarial',[
            "dados" => $contratos,
            "texto_empresarial" => $texto_empresarial
        ]);
    }

    public function semCarteirinha()
    {
        $contratos = Contrato
        ::where("plano_id",1)
        ->whereHas('clientes',function($query){
            $query->whereRaw("cateirinha IS NULL");
        })
        ->with(['administradora','financeiro','cidade','comissao','plano','comissao.comissaoAtualFinanceiro','clientes','clientes.user'])
        ->get();
        return $contratos;
    }

    public function geralIndividualPendentes(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })

            // ->whereHas('comissao.ultimaComissaoPaga',function($query){
            //     $query->whereYear("data",2022);
            //     $query->whereMonth('data','08');
            // })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereYear("data_vigencia",date('Y'))
            ->whereMonth("data_vigencia",date('m'))
            ->orderBy("id","desc")
            ->get();
        // $contratos = [];

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
            ::with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereYear("data_vigencia",date('Y'))
            ->whereMonth("data_vigencia",date('m'))
            //->orderBy("id","desc")
            ->get();

        return $contratos;
    }




    public function mudarAnoIndividual(Request $request)
    {
        if(isset($request->mes) && !empty($request->mes) && $request->mes != null) {
            $contratos = Contrato
            ::where("plano_id",1)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereYear("data_vigencia",$request->ano)
            ->whereMonth("data_vigencia",$request->mes)
            ->orderBy("id","desc")
            ->get();
        } else {
            $contratos = Contrato
            ::where("plano_id",1)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereYear("data_vigencia",$request->ano)
            ->orderBy("id","desc")
            ->get();
        }
        return $contratos;
    }

    public function mudarAnoColetivo(Request $request)
    {



        //if(isset($request->mes) && !empty($request->mes) && $request->mes != null) {
            $contratos = Contrato
            ::where("plano_id",3)
            // ->whereHas('clientes',function($query){
            //     $query->whereRaw("cateirinha IS NOT NULL");
            // })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes']);
            //->whereYear("created_at",$request->ano)




            //->whereMonth("created_at",$request->mes);


            $contratos->when($request->ano != 'todos', function ($q) use($request) {
                return $q->whereYear("created_at",$request->ano);
            });

            $contratos->when($request->ano == "todos",function($q) use($request){
                return $q->whereYear("created_at",">","2000");
            });


            $contratos->when($request->mes != 'todos', function ($q) use($request) {
                return $q->whereMonth("created_at",$request->mes);
            });

            $contratos->when($request->mes == 'todos', function ($q) use($request) {
                return $q->whereMonth("created_at",">=","01");
            });



            $dados = $contratos->orderBy("id","desc")->get();

            return $dados;


        // } else {
        //     $contratos = Contrato
        //     ::where("plano_id",3)
        //     // ->whereHas('clientes',function($query){
        //     //     $query->whereRaw("cateirinha IS NOT NULL");
        //     // })
        //     ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        //     ->whereYear("created_at",$request->ano)
        //     ->orderBy("id","desc")
        //     ->get();
        // }
        //return $contratos;
    }


    public function mudarMesIndividual(Request $request)
    {
        // if(isset($request->ano) && !empty($request->ano) && $request->ano != null) {
        //     $contratos = Contrato
        //     ::where("plano_id",1)
        //     ->whereHas('clientes',function($query){
        //         $query->whereRaw("cateirinha IS NOT NULL");
        //     })
        //     ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPagaFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        //     ->whereMonth('data_vigencia',$request->mes)
        //     ->whereYear("data_vigencia",$request->ano)
        //     ->orderBy("id","desc")
        //     ->get();
        // } else {
        //     $contratos = Contrato
        //     ::where("plano_id",1)
        //     ->whereHas('clientes',function($query){
        //         $query->whereRaw("cateirinha IS NOT NULL");
        //     })
        //     ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPagaFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        //     ->whereMonth('data_vigencia',$request->mes)
        //     ->orderBy("id","desc")
        //     ->get();
        // }


        if($request->mes != "00") {
            $contratos = Contrato
            ::where("plano_id",1)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPagaFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereMonth('data_vigencia',$request->mes)
            ->orderBy("id","desc")
            ->get();


        } else {
            $contratos = Contrato
            ::where("plano_id",1)
            ->whereHas('clientes',function($query){
                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPagaFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            // ->whereMonth('data_vigencia',$request->mes)
            ->orderBy("id","desc")
            ->get();


        }


        return $contratos;
    }

    public function mudarMesColetivo(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            // ->whereHas('clientes',function($query){
            //     $query->whereRaw("cateirinha IS NOT NULL");
            // })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes']);
            //->whereYear("created_at",$request->ano)




            //->whereMonth("created_at",$request->mes);


            $contratos->when($request->ano != 'todos', function ($q) use($request) {
                return $q->whereYear("created_at",$request->ano);
            });

            $contratos->when($request->ano == "todos",function($q) use($request){
                return $q->whereYear("created_at",">","2000");
            });


            $contratos->when($request->mes != 'todos', function ($q) use($request) {
                return $q->whereMonth("created_at",$request->mes);
            });

            $contratos->when($request->mes == 'todos', function ($q) use($request) {
                return $q->whereMonth("created_at",">=","01");
            });



            $dados = $contratos->orderBy("id","desc")->get();

            return $dados;




        //if(isset($request->ano) && !empty($request->ano) && $request->ano != null) {

            // $contratos = Contrato
            // ::where("plano_id",3)
            // // ->whereHas('clientes',function($query){
            // //     $query->whereRaw("cateirinha IS NOT NULL");
            // // })
            // ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            // ->whereMonth('created_at',$request->mes)
            // ->whereYear("created_at",$request->ano)
            // ->orderBy("id","desc")
            // ->get();
            // return $contratos;
        //} else {
            // $contratos = Contrato
            // ::where("plano_id",3)
            // // ->whereHas('clientes',function($query){
            // //     $query->whereRaw("cateirinha IS NOT NULL");
            // // })
            // ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            // ->whereMonth('created_at',$request->mes)

            // ->orderBy("id","desc")
            // ->get();
        //}
        //return $contratos;
    }




    public function atualizarCarteirinha(Request $request)
    {

        //return $request->all();


        $carteirinha = $request->cateirinha;
        $id = $request->id_cliente;

        $url = "https://api-hapvida.sensedia.com/wssrvonline/v1/beneficiario/$carteirinha/financeiro/historico";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $resp = curl_exec($curl);
        curl_close($curl);
        $dados = json_decode($resp);







        if($dados != null) {
            $cliente = Cliente::where("id",$id)->first();
//
            $cliente->cateirinha = $carteirinha;
            $cliente->save();
//             return $cliente;
            $contrato = Contrato::where('cliente_id',$id)->first();
            $contrato->financeiro_id = 12;
            $contrato->save();

            $comissao = Comissoes::where("contrato_id",$contrato->id)->first()->id;

            foreach($dados as $d) {

                 $valor = number_format($d->vlObrigacao,2) ?? 0;

//                $valor = (float) $d->vlObrigacao;
//
//                $valor_banco = str_replace([".",","],["","."],$valor);
//
                 $data_vencimento = implode("-",array_reverse(explode("/",$d->dtVencimento)));
                 $comissoesLancadas = ComissoesCorretoresLancadas::where("comissoes_id",$comissao)->where("data",$data_vencimento)->first();
                 if($comissoesLancadas) {
                     $comissoesLancadas->valor_pago = $valor;
                     $comissoesLancadas->status_financeiro = 1;
                     $comissoesLancadas->status_gerente = 0;
                     $comissoesLancadas->data_baixa = $d->dtPagamento == null ? null : implode("-",array_reverse(explode("/",$d->dtPagamento)));
                     $comissoesLancadas->save();
                 }





            }

             $qtd_individual_parcela_01 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",5)

                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",1);
                     })
                     ->whereHas('clientes',function($query) use($request){
                        $query->whereRaw("cateirinha IS NOT NULL");
                     })
                  ->count();

                 $qtd_individual_parcela_02 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",6)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",2);
                     })
                     ->whereHas('clientes',function($query) use($request){

                         $query->whereRaw("cateirinha IS NOT NULL");
                     })
                     ->count();
//
                 $qtd_individual_parcela_03 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",7)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",3);
                     })
                     ->whereHas('clientes',function($query) use($request){
//
                         $query->whereRaw("cateirinha IS NOT NULL");
                     })
                     ->count();
//
                 $qtd_individual_parcela_04 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",8)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",4);
                     })
                     ->whereHas('clientes',function($query) use($request){
                         $query->whereRaw("cateirinha IS NOT NULL");
                     })
                     ->count();
                 $qtd_individual_parcela_05 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",9)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",5);
                     })
                     ->whereHas('clientes',function($query) use($request){
                         $query->whereRaw("cateirinha IS NOT NULL");
                     })
                     ->count();
//
                 $qtd_individual_parcela_06 = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id",10)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->where("parcela",6);
                     })
                     ->whereHas('clientes',function($query) use($request){
                         $query->whereRaw("cateirinha IS NOT NULL");
                     })
                     ->count();
//
                 $qtd_individual_finalizado = Contrato
                     ::where("financeiro_id",11)
                     ->where("plano_id",1)
//
                     ->whereHas('clientes',function($query) use($request){
//
                     })
                     ->count();
//
                 $qtd_individual_cancelado = Contrato
                     ::where("financeiro_id",12)
                     ->where("plano_id",1)
                     ->whereHas('clientes',function($query) use($request){
//
                     })
                     ->count();
//
                 $qtd_cliente = Cliente
                     ::where("user_id",$request->id)
                     ->whereRaw("cateirinha IS NOT NULL")
                     ->whereHas('contrato',function($query){
                         $query->whereRaw('plano_id = 1');
                     })->count();
//
                 $qtd_vidas = Cliente
                     ::where("user_id",$request->id)
                     ->whereRaw("cateirinha IS NOT NULL")
                     ->whereHas('contrato',function($query){
                         $query->whereRaw('plano_id = 1');
                 })
                 ->selectRaw("sum(quantidade_vidas) as quantidade_vidas")
                 ->first();
//
                 $qtd_individual_atrasado = Contrato
                     ::where("plano_id",1)
                     ->where("financeiro_id","!=",12)
                     ->whereHas('comissao.comissoesLancadas',function($query){
                         $query->whereRaw("DATA <= NOW()");
                         $query->whereRaw("valor > 0");
                         $query->whereRaw("data_baixa IS NULL");
                         $query->groupBy("comissoes_id");
                     })
                     ->whereHas('clientes',function($query) use($request){
                         $query->whereRaw('cateirinha IS NOT NULL');
                         $query->where("user_id",$request->id);
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





        // return $dados;

        // $comissoesLancadas = ComissoesCorretoresLancadas::




        // $contrato->financeiro_id = 12;
        // $contrato->save();

    }

    public function getAtrasados()
    {
        $atrasados = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id","!=",10)
                ->where("financeiro_id","!=",12)
                //->where("financeiro_id","!=",12)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    //$query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                    //$query->orderBy("data","desc");
                })
                // ->whereHas('comissao.comissoesLancadas.comissaoAtualFinanceiro',function($query){
                //     $query->orderBy("data","desc");
                // })
                ->whereHas('clientes',function($query){
                    $query->whereRaw('cateirinha IS NOT NULL');
                })

                ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])

                ->get();



        return $atrasados;
    }

    public function getAtrasadosCorretor()
    {
        $atrasados = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id","!=",12)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA <= NOW()");
                    $query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes',function($query){
                    $query->whereRaw('cateirinha IS NOT NULL');
                    $query->where("user_id",auth()->user()->id);
                })
                ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                ->get();
        return $atrasados;
    }


    public function quantidadeCorretor(Request $request)
    {
        if($request->id != 0) {
                $qtd_individual_parcela_01 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",5)

                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",1);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_02 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",6)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",2);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_03 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",7)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",3);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_parcela_04 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",8)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",4);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
            $qtd_individual_parcela_05 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",9)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",5);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();
            $qtd_individual_parcela_06 = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id",10)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->where("parcela",6);
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                    $query->whereRaw("cateirinha IS NOT NULL");
                })
                ->count();

            $qtd_individual_finalizado = Contrato
                ::where("financeiro_id",11)
                ->where("plano_id",1)

                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                })
                ->count();

            $qtd_individual_cancelado = Contrato
                ::where("financeiro_id",12)
                ->where("plano_id",1)

                ->whereHas('clientes',function($query) use($request){
                    $query->where("user_id",$request->id);
                })
                ->count();


            $qtd_cliente = Cliente
                ::where("user_id",$request->id)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato',function($query){
                    $query->whereRaw('plano_id = 1');
                })->count();

            $qtd_vidas = Cliente
                ::where("user_id",$request->id)
                ->whereRaw("cateirinha IS NOT NULL")
                ->whereHas('contrato',function($query){
                    $query->whereRaw('plano_id = 1');
            })->selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first();

            $qtd_individual_atrasado = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id","!=",12)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    //$query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                })
                ->whereHas('clientes',function($query) use($request){
                    $query->whereRaw('cateirinha IS NOT NULL');
                    $query->where("user_id",$request->id);
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
            $qtd_individual_parcela_01 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",5)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",1);
            })

            ->count();
        $qtd_individual_parcela_02 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",6)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",2);
            })

            ->count();

        $qtd_individual_parcela_03 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",7)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",3);
            })
            ->count();

        $qtd_individual_parcela_04 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",8)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",4);
            })
            ->count();

        $qtd_individual_parcela_05 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",9)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('clientes',function($query) use($request){
                $query->where("user_id",$request->id);
            })
            ->count();

        $qtd_individual_parcela_06 = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",10)
            ->whereHas('clientes',function($query) use($request){

                $query->whereRaw("cateirinha IS NOT NULL");
            })
            ->whereHas('clientes',function($query) use($request){
                $query->where("user_id",$request->id);
            })
            ->count();

        $qtd_individual_finalizado = Contrato
            ::where("financeiro_id",11)
            ->where("plano_id",1)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",6);
            })
            ->count();

        $qtd_individual_cancelado = Contrato
            ::where("financeiro_id",12)
            ->where("plano_id",1)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("parcela",6);
            })
            ->count();

            $qtd_cliente = Contrato::where("plano_id",1)->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })->count();

        $qtd_vidas = Cliente
            ::whereRaw("cateirinha IS NOT NULL")
            ->selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first();


        $qtd_individual_atrasado = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id","!=",12)
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->whereRaw("DATA < CURDATE()");
                $query->whereRaw("valor > 0");
                $query->whereRaw("data_baixa IS NULL");
                $query->groupBy("comissoes_id");
            })
            ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
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
    }


    }



    public function coletivoEmAnaliseCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",1)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','comissao.comissaoAtualFinanceiro','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoEmBranco(Request $request)
    {
        return [];
    }

    public function coletivoEmGeral(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            // ->where("financeiro_id",1)
            ->with(['administradora','financeiro','cidade','comissao','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','acomodacao','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereYear("created_at",date('Y'))
            ->whereMonth("created_at",date('m'))
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoEmGeralCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            // ->where("financeiro_id",1)
            ->with(['administradora','financeiro','cidade','comissao','comissao.comissaoAtualFinanceiro','acomodacao','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function storeEmpresarialFinanceiro(Request $request)
    {
        //dd($request->all());



        $dados = $request->all();
        $dados['taxa_adesao'] = str_replace([".",","],["","."],$request->taxa_adesao);
        $dados['desconto_corretor'] = str_replace([".",","],["","."],$request->desconto_corretor);
        $dados['desconto_corretora'] = str_replace([".",","],["","."],$request->desconto_corretora);

        $dados['valor_plano'] = str_replace([".",","],["","."],$request->valor_plano);
        $dados['valor_plano_saude'] = str_replace([".",","],["","."],$request->valor_plano_saude);
        $dados['valor_plano_odonto'] = str_replace([".",","],["","."],$request->valor_plano_odonto);
        $dados['valor_plano'] = $dados['valor_plano_saude'] + $dados['valor_plano_odonto'];
        $dados['valor_total'] = $dados['valor_plano'] + $dados['taxa_adesao'];
        $dados['valor_boleto'] = str_replace([".",","],["","."],$request->valor_boleto);
        $dados['data_boleto'] = date('Y-m-d',strtotime($request->data_boleto));
        $dados['created_at'] = $request->created_at;
        $dados['financeiro_id'] = 1;
        $valor = $dados['valor_plano'];
        $contrato = ContratoEmpresarial::create($dados);
        $comissao = new Comissoes();
        $comissao->contrato_empresarial_id = $contrato->id;
        // $comissao->cliente_id = $contrato->cliente_id;
        $comissao->user_id = $request->user_id;
        // $comissao->status = 1;
        $comissao->plano_id = $request->plano_id;
        $comissao->administradora_id = 4;
        $comissao->tabela_origens_id = $request->tabela_origens_id;
        $comissao->data = date('Y-m-d');
        $comissao->empresarial = 1;
        $comissao->save();


        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
        ::where("plano_id",$request->plano_id)
        ->where("administradora_id",4)
        ->where("user_id",$request->user_id)
        ->where("tabela_origens_id",$request->tabela_origens_id)
        ->get();


        $date = new \DateTime(now());
        $date->add(new \DateInterval('PT1M'));
        $data = $date->format('Y-m-d H:i:s');


        $comissao_corretor_contagem = 0;
        $comissao_corretor_default = 0;
        if(count($comissoes_configuradas_corretor) >= 1) {
            foreach($comissoes_configuradas_corretor as $c) {
                $comissaoVendedor = new ComissoesCorretoresLancadas();
                $comissaoVendedor->comissoes_id = $comissao->id;
                //$comissaoVendedor->user_id = auth()->user()->id;
                $comissaoVendedor->parcela = $c->parcela;
                if($comissao_corretor_contagem == 0) {
                    $comissaoVendedor->data = date('Y-m-d H:i:s',strtotime($request->data_boleto));
                    //$comissaoVendedor->tempo = $data;
                } else {
                    $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_boleto."+{$comissao_corretor_contagem}month"));
                    $date = new \DateTime($data);
                    $date->add(new \DateInterval("PT{$comissao_corretor_contagem}M"));
                    $data_add = $date->format('Y-m-d H:i:s');
                    //$comissaoVendedor->tempo = $data_add;
                }
                $comissaoVendedor->valor = ($valor * $c->valor) / 100;
                $comissaoVendedor->save();
                $comissao_corretor_contagem++;
            }
        } else {
            $dados = ComissoesCorretoresDefault
            ::where("plano_id",$request->plano_id)
            ->where("administradora_id",4)
            ->where("tabela_origens_id",2)
            ->get();
            foreach($dados as $c) {
                $comissaoVendedor = new ComissoesCorretoresLancadas();
                $comissaoVendedor->comissoes_id = $comissao->id;
                $comissaoVendedor->parcela = $c->parcela;
                if($comissao_corretor_default == 0) {
                    $comissaoVendedor->data = date('Y-m-d H:i:s',strtotime($request->data_boleto));
                    //$comissaoVendedor->data = $data_vigencia;
                    //$comissaoVendedor->status_financeiro = 1;
                    // if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                    //     //$comissaoVendedor->status_gerente = 1;
                    // }

                } else {
                    $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($request->data_boleto."+{$comissao_corretor_default}month"));
                    $date = new \DateTime($data);
                    $date->add(new \DateInterval("PT{$comissao_corretor_default}M"));
                    //$data_add = $date->format('Y-m-d H:i:s');
                }
                $comissaoVendedor->valor = ($valor * $c->valor) / 100;
                $comissaoVendedor->save();
                $comissao_corretor_default++;
            }
        }

         /** Comissao Corretora */
         $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes
         ::where("administradora_id",4)
         ->where('plano_id',$request->plano_id)
         ->where("tabela_origens_id",2)
         ->get();
         $comissoes_corretora_contagem=0;
         if(count($comissoes_configurada_corretora)>=1) {
             foreach($comissoes_configurada_corretora as $cc) {
                 $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                 $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                 $comissaoCorretoraLancadas->parcela = $cc->parcela;
                 if($comissoes_corretora_contagem == 0) {
                     $comissaoCorretoraLancadas->data = date('Y-m-d',strtotime($request->data_boleto));
                 } else {
                     $comissaoCorretoraLancadas->data = date("Y-m-d",strtotime($request->data_boleto."+{$comissoes_corretora_contagem}month"));
                 }
                 $comissaoCorretoraLancadas->valor = ($valor * $cc->valor) / 100;
                 $comissaoCorretoraLancadas->save();
                 $comissoes_corretora_contagem++;
             }
         }
         return redirect('admin/financeiro?ac=empresarial');
    }



    public function empresarialEmGeral(Request $request)
    {

    }

    public function emAnaliseIndividual(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",1)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function emAnaliseIndividualCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",1)
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }


    public function coletivoEmAnalise(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",1)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }






    public function coletivoEmissaoBoleto(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",2)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoEmissaoBoletoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->where("financeiro_id",2)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }






    public function coletivoPagamentoAdesao(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",3)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoAdesaoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",3)
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function coletivoPagamentoVigencia(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ///->where("financeiro_id",4)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoVigenciaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)

            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",2);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->orderBy("id","desc")

            ->get();
        return $contratos;
    }

    public function individualPagamentoPrimeiraParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",5)
             ->where("financeiro_id","!=",12)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",1);
                //$query->whereRaw("data_baixa IS NULL");
            })
             ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoPrimeiraParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",5)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",1);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }


    public function coletivoPagamentoSegundaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",6)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");

            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with('comissao.comissaoAtualFinanceiro')
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoSegundaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)

            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",3);
                $query->whereRaw("data_baixa IS NULL");

            })
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with('comissao.comissaoAtualFinanceiro')
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoSegundaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",6)
             ->where("financeiro_id","!=",12)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",2);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoSegundaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",6)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",2);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }




    public function coletivoPagamentoTerceiraParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",7)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",4);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoTerceiraParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",7)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",4);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoTerceiraParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",7)
             ->where("financeiro_id","!=",12)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",3);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoTerceiraParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",7)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",3);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }




    public function coletivoPagamentoQuartaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",8)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",5);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoQuartaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)

            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",5);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }







    public function individualPagamentoQuartaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",8)
             ->where("financeiro_id","!=",12)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",4);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoQuartaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",8)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro","=",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",4);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoQuintaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",9)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",6);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoPagamentoQuintaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)

            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",6);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function individualPagamentoQuintaParcela(Request $request)
    {
        $contratos = Contrato
        ::where("plano_id",1)
        ->where("financeiro_id",9)
        ->where("financeiro_id","!=",12)
        ->whereHas('comissao.comissoesLancadas',function($query){
            //$query->where("status_financeiro","=",0);
            //$query->where("status_gerente",0);
            $query->where("parcela",5);
            //$query->whereRaw("data_baixa IS NULL");
        })
        ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        ->orderBy("id","desc")
        ->get();
        return $contratos;
    }

    public function individualPagamentoQuintaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
        ::where("plano_id",1)
        ->where("financeiro_id",9)
        ->where("financeiro_id","!=",12)
        ->whereHas('clientes',function($query){
            $query->where("user_id",auth()->user()->id);
        })
        ->whereHas('comissao.comissoesLancadas',function($query){
            //$query->where("status_financeiro","=",0);
            //$query->where("status_gerente",0);
            $query->where("parcela",5);
            //$query->whereRaw("data_baixa IS NULL");
        })
        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        ->orderBy("id","desc")
        ->get();
        return $contratos;
    }

    public function coletivoPagamentoSextaParcela(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            //->where("financeiro_id",10)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
                $query->where("status_gerente",0);
                $query->where("parcela",7);
                $query->where("atual",1);
                $query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }


    public function coletivoPagamentoSextaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            // ->where("financeiro_id",10)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })

            ->whereHas('comissao.comissoesLancadas',function($query){
                // $query->where("status_financeiro",0);
                // $query->where("status_gerente",0);
                $query->where("parcela",7);
                // $query->whereRaw("data_baixa IS NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            // ->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }


                    //$query->whereRaw("valor > 0");
                    //$query->whereRaw("data_baixa IS NULL");
                    //$query->groupBy("comissoes_id");



    public function individualPagamentoSextaParcela(Request $request)
    {

        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",10)
            ->where("financeiro_id","!=",12)
            ->whereDoesntHave('comissao.comissoesLancadas',function($query){
                $query->where("status_financeiro",0);
            })
            ->whereHas('clientes',function($query){
                $query->whereRaw('cateirinha IS NOT NULL');
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualPagamentoSextaParcelaCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",10)
            ->whereHas('comissao.comissoesLancadas',function($query){
                //$query->where("status_financeiro",0);
                //$query->where("status_gerente",0);
                $query->where("parcela",6);
                //$query->whereRaw("data_baixa IS NULL");
            })
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoFinalizado(Request $request)
    {
        $contratos = Contrato
            //->where('financeiro_id',11)
            ::where("plano_id",3)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->whereHas('comissao.comissoesLancadas',function($query){

                $query->where("parcela",7);
                $query->where("status_financeiro",1);
                $query->whereRaw("data_baixa IS NOT NULL");
            })
            ->with('comissao.comissaoAtualFinanceiro')
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();

        return $contratos;
    }

    public function coletivoFinalizadoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",11)
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function coletivoFinalizadoColetivo(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->whereHas("clientes",function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->where("financeiro_id",11)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }




    public function individualFinalizado(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",11)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualFinalizadoCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",11)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            // ->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function coletivoCancelados(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",12)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','comissao.comissaoAtualFinanceiro','comissao.comissaoAtualLast','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function coletivoCanceladosCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",3)
            ->where("financeiro_id",12)
            ->whereHas('clientes',function($query){
                $query->where("user_id",auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function individualCancelados(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",12)
            ->with(['administradora','financeiro','cidade','comissao','comissao.cancelado','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }

    public function individualCanceladosCorretor(Request $request)
    {
        $contratos = Contrato
            ::where("plano_id",1)
            ->where("financeiro_id",12)
            ->whereHas('clientes',function($query){
                $query->where('user_id',auth()->user()->id);
            })
            ->with(['administradora','financeiro','cidade','comissao','comissao.cancelado','acomodacao','plano','comissao.comissaoAtualFinanceiro','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            //->whereRaw("data_boleto >= date_add(data_boleto, interval 1 day)")
            //->whereRaw("NOW() > date_add(updated_at, INTERVAL 30 SECOND)")
            //->whereRaw("tempo >= now()")
            ->orderBy("id","desc")
            ->get();
        return $contratos;
    }





    public function mudarDataVivenciaColetivo(Request $request)
    {
        $data = implode("-",array_reverse(explode("/",$request->data)));
        $contrato = Contrato::where("cliente_id",$request->cliente_id)->first();
        $contrato->data_vigencia = $data;
        if($contrato->save()) {
            return "sucesso";
        } else {
            return "error";
        }
    }




    public function mudarEstadosColetivo(Request $request)
    {

        $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = Contrato::find($id_contrato);
        switch ($contrato->financeiro_id) {
            case 1:
                $contrato->financeiro_id = 2;
            break;
            case 2:
                $contrato->financeiro_id = 3;
            break;
            default:
                return "abrir_modal";
            break;
        }
        $contrato->save();
        return $this->recalcularColetivo();
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

    public function mudarEstadosEmpresarial(Request $request)
    {

        // $id_cliente = $request->id_cliente;
        $id_contrato = $request->id_contrato;
        $contrato = ContratoEmpresarial::where("id",$id_contrato)->first();

        switch ($contrato->financeiro_id) {
            case 1:
                //$contrato->financeiro_id = 5;
                if($contrato->valor_total != $contrato->valor_boleto && $contrato->desconto_corretor == 0 && $contrato->desconto_corretora == 0) {
                    return [
                        "modal" => "abrir_modal_desconto",
                        "diferenca" => abs($contrato->valor_total - $contrato->valor_boleto)
                    ];
                } else {
                    $contrato->financeiro_id = 5;
                }
            break;
            default:
                return "abrir_modal_empresarial";
            break;
        }
        $contrato->save();
        return $this->recalcularEmpresarial();
     }


     public function mudarEstadosEmpresarialDescontos(Request $request)
     {

        $id_contrato = $request->id_contrato;
        $contrato = ContratoEmpresarial::where("id",$id_contrato)->first();

        $desconto_corretora = str_replace([".",","],["","."],$request->desconto_corretora);
        $desconto_corretor = str_replace([".",",","R$"],["",".",""],$request->desconto_corretor);
        $desconto_corretor = preg_replace('/\xc2\xa0/','',$desconto_corretor);
        $contrato->desconto_corretora = $desconto_corretora;
        $contrato->desconto_corretor = $desconto_corretor;
        $contrato->financeiro_id = 5;
        $contrato->save();
        return "sucesso";
        //return $this->recalcularEmpresarial();
     }





    public function verContrato(Request $request)
    {

        //return $request->all();

        // if($request->financeiro_id == 1) {

        // } else {

        // }

        if($request->janela != "aba_empresarial") {
            $plano_id = Contrato::where("id",$request->contrato_id)->first()->plano_id;
            $id_comissao = Comissoes::where("contrato_id",$request->contrato_id)->first()->id;
            $comissoes = DB::table('comissoes_corretores_lancadas')
            ->selectRaw('parcela')
            ->selectRaw('DATA AS vencimento')
            ->selectRaw('data_baixa AS data_baixa')
            ->selectRaw('(SELECT valor_plano FROM contratos WHERE id = (SELECT contrato_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS valor')
            ->selectRaw('DATEDIFF(data, data_baixa) as dias_faltando')
            ->selectRaw('(SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE id = (SELECT contrato_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id))) AS cliente')
            ->whereRaw("comissoes_id = ?",$id_comissao)
            ->get();



        } else {
            $plano_id = "";
            $id_comissao = Comissoes::where("contrato_empresarial_id",$request->contrato_id)->first()->id;

            $comissoes = DB::table('comissoes_corretores_lancadas')
            ->selectRaw('parcela')
            ->selectRaw('DATA AS vencimento')
            ->selectRaw('data_baixa AS data_baixa')
            ->selectRaw('(SELECT valor_plano FROM contrato_empresarial WHERE id = (SELECT contrato_empresarial_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS valor')
            ->selectRaw('DATEDIFF(data, data_baixa) as dias_faltando')
            ->selectRaw('(SELECT responsavel FROM contrato_empresarial WHERE id = (SELECT contrato_empresarial_id FROM comissoes WHERE comissoes.id = comissoes_corretores_lancadas.comissoes_id)) AS cliente')
            ->whereRaw("comissoes_id = ?",$id_comissao)
            ->get();


        }
        //return $comissoes;




        return view('admin.pages.comissao.ver',[
            "comissoes" => $comissoes,
            "cliente" => $comissoes[0]->cliente,
            "plano_id" => $plano_id
        ]);
    }

    public function clienteCancelado($id)
    {
        $contrato = Contrato::where("id",$id)->first();
        $cliente = Cliente::where("id",$contrato->cliente_id)->first();
        $comissao = Comissoes::where("contrato_id",$contrato->id)->first();
        $comissoesLancadas = ComissoesCorretoresLancadas
            ::where("comissoes_id",$comissao->id)
            ->whereRaw("valor_pago IS NOT NULL")
            ->selectRaw("parcela,data,valor_pago,data_baixa")
            ->selectRaw("(if(data_baixa != '0000-00-00','LIQUIDADO','CANCELADO') ) status")
            ->get();

        $contratos = Contrato
        ::where("id",$id)
        ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        ->orderBy("id","desc")
        ->first();



        return view('admin.pages.financeiro.cancelados',[
            "comissao" => $comissoesLancadas,
            "cliente" => $cliente,
            "dados" => $contratos
        ]);

    }






    public function cancelarContrato(Request $request)
    {

        $contrato_id = Comissoes::where("id",$request->comissao_id_cancelado)->first()->contrato_id;
        $contrato = Contrato::where("id",$contrato_id)->first();

        // $deletado = Cliente::where("id",$cliente)->delete();

        // if($deletado) {
        //     return $this->recalcularColetivo();
        // } else {
        //     return "error";
        // }

        $comissaoLancadas = ComissoesCorretoresLancadas
            ::where('comissoes_id',$request->comissao_id_cancelado)
            ->where('data_baixa',null)
            ->update(["atual"=>0,"cancelados"=>1]);


            //->update(['cancelados' => 1]);
        if(!$comissaoLancadas) {
            return "error";
        }

        // $dados = [];
        // $dados['comissoes_id'] = $request->comissao_id_cancelado;
        // $dados['data_baixa'] = $request->date;
        // $dados['motivo'] = $request->motivo;
        // $dados['observacao'] = $request->obs;
        // $cancelados = Cancelado::create($dados);
        // if(!$cancelados) {
        //     return "error";
        // }
        // // $comissao = Comissoes::find($request->comissao_id_cancelado)->contrato_id;
        Contrato::where("id",$contrato->id)->update(['financeiro_id'=>12]);

        return "sucesso";

        // if(Contrato::where("id",$contrato_id)->first()->plano_id == 3) {
        //     return [
        //         "plano" => "coletivo",
        //         "dados" => $this->recalcularColetivo()
        //     ];

        // } else {
        //     return [
        //         "plano" => "individual",
        //         "dados" => $this->recalcularIndividual()
        //     ];
        // }

    }

    public function excluirCliente(Request $request)
    {
        if($request->ajax()) {
            $id_cliente = $request->id_cliente;
            if($id_cliente != null) {
                $d = Cliente::where("id",$id_cliente)->delete();
                if($d) {
                    return $this->recalcularColetivo();
                } else {
                    return "error";
                }
            }
        }

    }

    public function excluirClienteIndividual(Request $request)
    {
        if($request->ajax()) {
            $id_cliente = $request->id_cliente;
            if($id_cliente != null) {
                $d = Cliente::where("id",$id_cliente)->delete();
                if($d) {
                    return $this->recalcularIndividual();
                } else {
                    return "error";
                }
            }
        }

    }

    public function excluirClienteEmpresarial(Request $request)
    {
        if($request->ajax()) {
            $id_contrato = $request->id_contrato;
            if($id_contrato != null) {
                $d = ContratoEmpresarial::where("id",$id_contrato)->delete();
                if($d) {
                    return "sucesso";
                } else {
                    return "error";
                }
            }
        }
    }

    private function clienteJaExiste($cpf)
    {
        $cliente = Cliente::where("cpf",$cpf)->get();
        if(count($cliente) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    public function sincronizarDadosColetivo(Request $request)
    {

        $filename = uniqid().".xlsx";
        if(move_uploaded_file($request->file,$filename)) {
            $filePath = base_path("public/{$filename}");
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if($rowNumber >= 5) {
                       $cpf = mb_strlen($cells[6]->getValue()) == 11 ? $cells[6]->getValue() : str_pad($cells[6]->getValue(), 11, "000", STR_PAD_LEFT);
                       $user_id = User::where('codigo_vendedor',$cells[0]->getValue())->first()->id;






                       $nascimento = ($cells[9]->getValue())->format('Y-m-d');
                       //$nascimento = implode("-",array_reverse(explode("/",$cells[9]->getValue())));
                       $criacao =  implode("-",array_reverse(explode("/",$cells[19]->getValue())));
                        $alvo = trim($cells[22]->getValue());
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
                       $cliente->quantidade_vidas = $cells[26]->getValue();
                       $cliente->email = mb_convert_case($cells[10]->getValue(), MB_CASE_LOWER, "UTF-8");
                       $cliente->save();

                        if(!empty($cells[7]->getValue()) && $cells[7]->getValue() != null) {
                            $dependente = new Dependentes();
                            $cpf_responsavel = mb_strlen($cells[8]->getValue()) == 11 ? $cells[8]->getValue() : str_pad($cells[8]->getValue(), 11, "000", STR_PAD_LEFT);
                            $dependente->cliente_id = $cliente->id;
                            $dependente->nome = mb_convert_case($cells[7]->getValue(), MB_CASE_TITLE, "UTF-8");
                            $dependente->cpf = $cpf_responsavel;
                            $dependente->save();
                        }

                        $contrato = new Contrato();
                        $contrato->acomodacao_id = $id_acomodacao;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = 3;
                        $contrato->tabela_origens_id = 2;
                        $contrato->plano_id = 3;
                        $contrato->financeiro_id = 1;
                        $contrato->data_vigencia = implode("-",array_reverse(explode("/",$cells[20]->getValue())));
                        $contrato->codigo_externo = $cells[5]->getValue();
                        // $contrato->data_boleto = implode("-",array_reverse(explode("/",$cells[21]->getValue())));
                        $contrato->data_boleto = ($cells[21]->getValue())->format('Y-m-d');
                        $contrato->valor_adesao = empty($cells[28]->getValue()) && $cells[28]->getValue() == null ? $cells[27]->getValue() : $cells[28]->getValue();
                        $contrato->valor_plano = $cells[27]->getValue();
                        $contrato->coparticipacao = $cells[23]->getValue();
                        $contrato->odonto = 1;
                        $contrato->created_at = implode("-",array_reverse(explode("/",$cells[19]->getValue())));
                        $contrato->desconto_corretor = "0,00";
                        $contrato->desconto_corretora = "0,00";
                        $contrato->save();

                       $comissao = new Comissoes();
                       $comissao->contrato_id = $contrato->id;
                       // $comissao->cliente_id = $contrato->cliente_id;
                       $comissao->user_id = $user_id;
                       // $comissao->status = 1;
                       $comissao->plano_id = 3;
                       $comissao->administradora_id = 3;
                       $comissao->tabela_origens_id = 2;
                       $comissao->data = date('Y-m-d');
                       $comissao->save();

                        /* Comissao Corretor */
                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                        ::where("plano_id",3)
                        ->where("administradora_id",3)
                        ->where("user_id",$user_id)
                        ->where("tabela_origens_id",2)
                        ->get();
                       $data_vigencia = implode("-",array_reverse(explode("/",$cells[20]->getValue())));
                       $comissao_corretor_contagem = 0;
                       $comissao_corretor_default = 0;
                       if(count($comissoes_configuradas_corretor) >= 1) {
                           foreach($comissoes_configuradas_corretor as $c) {
                               $comissaoVendedor = new ComissoesCorretoresLancadas();
                               $comissaoVendedor->comissoes_id = $comissao->id;
                               //$comissaoVendedor->user_id = auth()->user()->id;
                               $comissaoVendedor->parcela = $c->parcela;
                               if($comissao_corretor_contagem == 0) {
                                $comissaoVendedor->data = ($cells[21]->getValue())->format('Y-m-d');
                                //$comissaoVendedor->status_financeiro = 1;
                                if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                    //$comissaoVendedor->status_gerente = 1;
                                }

                            } elseif($comissao_corretor_contagem == 1) {
                                $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($data_vigencia));
                            }  else {
                                $mes = $comissao_corretor_contagem - 1;
                                $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($data_vigencia."+{$mes}month"));

                            }
                               $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                               $comissaoVendedor->save();
                               $comissao_corretor_contagem++;
                           }
                       } else {

                           $dados = ComissoesCorretoresDefault
                           ::where("plano_id",3)
                           ->where("administradora_id",3)
                           ->where("tabela_origens_id",2)
                           ->get();
                           foreach($dados as $c) {
                               $comissaoVendedor = new ComissoesCorretoresLancadas();
                               $comissaoVendedor->comissoes_id = $comissao->id;
                               $comissaoVendedor->parcela = $c->parcela;


                               if($comissao_corretor_default == 0) {
                                   $comissaoVendedor->data = ($cells[21]->getValue())->format('Y-m-d');
                                   //$comissaoVendedor->status_financeiro = 1;
                                   if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                       //$comissaoVendedor->status_gerente = 1;
                                   }

                               } elseif($comissao_corretor_default == 1) {
                                   $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($data_vigencia));
                               }  else {
                                   $mes = $comissao_corretor_default - 1;
                                   $comissaoVendedor->data = date("Y-m-d H:i:s",strtotime($data_vigencia."+{$mes}month"));

                               }
                               $comissaoVendedor->valor = ($cells[27]->getValue() * $c->valor) / 100;
                               $comissaoVendedor->save();
                               $comissao_corretor_default++;
                           }
                       }


                        $comissoes_configurada_corretora = ComissoesCorretoraConfiguracoes::where("administradora_id",3)->where('plano_id',3)->where('tabela_origens_id',2)->get();
                        $comissoes_corretora_contagem=0;
                        if(count($comissoes_configurada_corretora)>=1) {
                            foreach($comissoes_configurada_corretora as $cc) {
                                $comissaoCorretoraLancadas = new ComissoesCorretoraLancadas();
                                $comissaoCorretoraLancadas->comissoes_id = $comissao->id;
                                $comissaoCorretoraLancadas->parcela = $cc->parcela;
                                if($comissoes_corretora_contagem == 0) {
                                    $comissaoCorretoraLancadas->data = $data_vigencia;

                                // } else if($comissoes_corretora_contagem == 1) {
                                //     $comissaoCorretoraLancadas->data = date("Y-m-d H:i:s",strtotime($data_vigencia));
                                // } else {
                                //     $mes = $comissoes_corretora_contagem - 1;
                                //     $comissaoCorretoraLancadas->data = date("Y-m-d",strtotime($data_vigencia."+{$mes}month"));
                                } else {
                                    $comissaoCorretoraLancadas->data = date("Y-m-d",strtotime($data_vigencia."+{$comissoes_corretora_contagem}month"));
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







    public function sincronizarDados(Request $request)
    {
        $filename = uniqid().".xlsx";
        if(move_uploaded_file($request->file,$filename)) {
            $filePath = base_path("public/{$filename}");
            $cpfs = [];
            $reader = ReaderEntityFactory::createReaderFromFile($filePath);
            $reader->open($filePath);

            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                    $cells = $row->getCells();
                    if($rowNumber >= 5 && !in_array($cells[0]->getValue(),$cpfs)) {
                        $cpf = mb_strlen($cells[4]->getValue()) == 11 ? $cells[4]->getValue() : str_pad($cells[4]->getValue(), 11, "000", STR_PAD_LEFT);
                        $dia = str_pad($cells[18]->getValue(), 2, "0", STR_PAD_LEFT);
                        array_push($cpfs,$cells[0]->getValue());
                        $user_count = User::where('codigo_vendedor',$cells[2]->getValue())->count();

                        if(!$user_count && $user_count == 0) {
                            $user = new User();
                            $user->name = Str::title(Str::lower($cells[3]->getValue()));
                            $user->codigo_vendedor = $cells[2]->getValue();
                            $user->password = bcrypt("12345678");
                            $user->cargo_id = 2;
                            $email = Str::title($cells[3]->getValue())."@america.connectlife.app.br";
                            $user->email = Str::snake($email,"_");
                            $user->save();
                            $user_id = $user->id;
                        } else {
                            $user_id = User::where('codigo_vendedor',$cells[2]->getValue())->first()->id;
                        }

                        $cliente = new Cliente();
                        $cliente->user_id = $user_id;
                        $cliente->nome = mb_convert_case($cells[5]->getValue(), MB_CASE_TITLE, "UTF-8");
                        $cliente->celular = $cells[7]->getValue();
                        $cliente->cpf = $cpf;
                        $cliente->data_nascimento = implode("-",array_reverse(explode("/",$cells[6]->getValue())));
                        $cliente->pessoa_fisica = 1;
                        $cliente->pessoa_juridica = 0;
                        $cliente->codigo_externo = $cells[0]->getValue();

                        if($cells[8]->getValue() == "RESPONSÁVEL FINANCEIRO") {
                            $cliente->quantidade_vidas = $cells[16]->getValue();
                        } else {
                            $cliente->quantidade_vidas = $cells[16]->getValue() + 1;
                        }

                        $cliente->save();
                        $data_vigencia = implode("-",array_reverse(explode("/",$cells[17]->getValue())));
                        $contrato = new Contrato();
                        //$contrato->acomodacao_id = $acomodacao_id;
                        $contrato->cliente_id = $cliente->id;
                        $contrato->administradora_id = 4;
                        $contrato->tabela_origens_id = 2;
                        $contrato->plano_id = 1;
                        $contrato->financeiro_id = 5;
                        $contrato->data_vigencia = implode("-",array_reverse(explode("/",$cells[17]->getValue())));
                        $contrato->codigo_externo = $cells[0]->getValue();
                        $contrato->data_boleto = implode("-",array_reverse(explode("/",$cells[17]->getValue())));
                        $contrato->valor_adesao = $cells[12]->getValue();
                        $contrato->valor_plano = $cells[12]->getValue();
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
                        $comissao->tabela_origens_id = 2;
                        $comissao->data = date('Y-m-d');
                        $comissao->save();

                        $comissoes_configuradas_corretor = ComissoesCorretoresConfiguracoes
                        ::where("plano_id",1)
                        ->where("administradora_id",4)
                        ->where("user_id",$user_id)
                        ->where("tabela_origens_id",2)
                        ->get();

                        $comissao_corretor_contagem = 0;
                        $comissao_corretor_default = 0;

                        if(count($comissoes_configuradas_corretor) >= 1) {
                            foreach($comissoes_configuradas_corretor as $c) {
                                $comissaoVendedor = new ComissoesCorretoresLancadas();
                                $comissaoVendedor->comissoes_id = $comissao->id;
                                //$comissaoVendedor->user_id = auth()->user()->id;
                                // $comissaoVendedor->documento_gerador = "12345678";
                                $comissaoVendedor->parcela = $c->parcela;
                                $comissaoVendedor->valor = ($cells[12]->getValue() * $c->valor) / 100;
                                if($comissao_corretor_contagem == 0) {
                                    $comissaoVendedor->data = $data_vigencia;
                                    $comissaoVendedor->status_financeiro = 1;
                                    if($comissaoVendedor->valor == "0.00" || $comissaoVendedor->valor == 0 || $comissaoVendedor->valor >= 0) {
                                        //$comissaoVendedor->status_gerente = 1;
                                    }
                                    $comissaoVendedor->data_baixa = implode("-",array_reverse(explode("/",$cells[17]->getValue())));
                                    $comissaoVendedor->valor_pago = $cells[12]->getValue();
                                } else {
                                    $data_vigencia_sem_dia = date("Y-m",strtotime($data_vigencia));
                                    $dates = date("Y-m",strtotime($data_vigencia_sem_dia."+{$comissao_corretor_contagem}month"));

                                    $mes = explode("-",$dates)[1];
                                    if($dia == 30 && $mes == 02) {
                                        $comissaoVendedor->data = date("Y-02-28");
                                        $ano = explode("-",$comissaoVendedor->data)[0];
                                        $bissexto= date('L', mktime(0, 0, 0, 1, 1, $ano));

                                        if($bissexto == 1) {
                                            $comissaoVendedor->data = date("Y-02-29");
                                        } else {
                                            $comissaoVendedor->data = date("Y-02-28");
                                        }

                                    }  else {
                                        $comissaoVendedor->data = date("Y-m-".$dia,strtotime($dates));
                                    }

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
                                $valor_comissao_default = (float) str_replace(',', '', $cells[12]->getValue()) - 25;
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
                            
                            
                            
                            
                            
                            
                            
                            
                        } /****FIm SE Comissoes Lancadas */

                       
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
                                $valor_cc = (float) str_replace(',', '', $cells[12]->getValue()) - 25;
                                $comissaoCorretoraLancadas->valor = ($valor_cc * $cc->valor) / 100;
                                $comissaoCorretoraLancadas->save();
                                $comissoes_corretora_contagem++;
                            }
                        }
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                       
                    }
                }
            }
            //unlink("public/".$filename);
        }

        // $primeiro = Cliente::where("first",1);
        // if($primeiro->count() == 0) {
        //     $cli = Cliente::first();
        //     $cli->first = 1;
        //     $cli->save();
        // } else {
        //     Cliente::where("first",1)->update(["first"=>0]);
        //     Cliente::where("last",1)->update(["first"=>1,"last"=>0]);
        // }




        // $last = Cliente::where("last",1);
        // if($last->count() == 0) {
        //     $cli = Cliente::orderBy("id","desc")->first();
        //     $cli->last = 1;
        //     $cli->save();
        // } else {
        //     $last->last = 0;
        //     $last->save();
        //     $cli = Cliente::orderBy("id","desc")->first();
        //     $cli->last = 1;
        //     $cli->save();
        // }







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
                $url = "https://api-hapvida.sensedia.com/wssrvonline/v1/beneficiario/$cc->cateirinha/financeiro/historico";
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
                                //->where("status_gerente","!=",1)
                                ->update([
                                        'status_financeiro'=>1,
                                        //'status_gerente'=>1,
                                        'valor_pago' => $d->vlObrigacao,
                                        'data_baixa'=>$data_baixa
                                ]);
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

    public function sincronizarBaixasJaExiste()
    {
        $clientes = Contrato
                ::where("plano_id",1)
                ->where("financeiro_id","!=",12)
                ->whereHas('comissao.comissoesLancadas',function($query){
                    $query->whereRaw("DATA < CURDATE()");
                    //$query->whereRaw("valor > 0");
                    $query->whereRaw("data_baixa IS NULL");
                    $query->groupBy("comissoes_id");
                    $query->orderBy("data");
                })
                ->whereHas('clientes',function($query){
                    $query->whereRaw('cateirinha IS NOT NULL');
                })
                ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
                ->get();

        

            foreach($clientes as $cc) {
                if($cc != null) {
                    $url = "https://api-hapvida.sensedia.com/wssrvonline/v1/beneficiario/{$cc->clientes->cateirinha}/financeiro/historico";
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_URL, $url);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $resp = curl_exec($curl);
                    curl_close($curl);

                    $dados = json_decode($resp);
                    $data = "";
                    $docu = "";
                    foreach($dados as $d) {
                        if($d) {
                            if($d->dtPagamento != null && $d->cdStatus != 16) {
                            $data = implode("-",array_reverse(explode('/',$d->dtVencimento)));
                            $data_baixa = implode("-",array_reverse(explode('/',$d->dtPagamento)));
                            $docu = $d->cdDocumentoGerador;
                            ComissoesCorretoresLancadas
                                ::where("data",$data)
                                ->where("documento_gerador",$docu)
                                ->where("status_financeiro","!=",1)
                                //->where("status_gerente","!=",1)
                                ->update([
                                        'status_financeiro'=>1,
                                        //'status_gerente'=>1,
                                        'valor_pago' => $d->vlObrigacao,
                                        'data_baixa'=>$data_baixa
                                ]);
                        }
                        }
                        
                    }
                }
            }

                



    }


    public function verificardependentesuser(Request $request)
    {
        $id = $request->id;
        $cpf = Cliente::find($id)->cpf;
        $cliente = Cliente::find($id);
        $url = "https://api-hapvida.sensedia.com/wssrvonline/v1/beneficiario?cpf=$cpf";
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

    public function detalhesContratoColetivo($id)
    {

        $contratos = Contrato
            ::where("id",$id)
            ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.comissoesLancadas','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
            ->orderBy("id","desc")
            ->first();

        $motivo_cancelados = MotivoCancelados::all();




        $status = "";
        $parcela = "";

        // dd($contratos);

        //dd($contratos->comissao->comissaoAtualFinanceiro->parcela);

        if(isset($contratos->comissao->comissaoAtualFinanceiro->parcela) && $contratos->comissao->comissaoAtualFinanceiro->parcela != null) {
            switch($contratos->comissao->comissaoAtualFinanceiro->parcela) {

                case 3:
                    $status = "Pagou Vigencia";
                break;
                case 4:
                    $status = "Pagou 2º Parcela";
                break;
                case 5:
                    $status = "Pagou 3º Parcela";
                break;
                case 6:
                    $status = "Pagou 4º Parcela";
                break;
                case 7:
                    $status = "Pagou 5º Parcela";
                break;

            }

            $parcela = $contratos->comissao->comissaoAtualFinanceiro->parcela;
        }




        $cancelados = $contratos->comissao->comissoesLancadas->where("cancelados",1)->count();

        $dependentes = "";
        if(Dependentes::where('cliente_id',$id)->first()) {
            $dependentes = Dependentes::where('cliente_id',$id)->first();
        }




        return view('admin.pages.financeiro.detalhe-coletivo',[
            "dados" => $contratos,
            "motivo_cancelados" => $motivo_cancelados,
            "status" => $status,
            "parcela" => $parcela,
            "cancelados" => $cancelados,
            "dependentes" => $dependentes
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
                $url = "https://api-hapvida.sensedia.com/wssrvonline/v1/beneficiario?cpf=$v->cpf";
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
                //$contrato->financeiro_id = 4;
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
                //$contrato->financeiro_id = 4;
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

                //$contrato->financeiro_id = 6;
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
                //$contrato->financeiro_id = 7;
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
                //$contrato->financeiro_id = 8;
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
                //$contrato->financeiro_id = 9;
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
                //$contrato->financeiro_id = 10;
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

    public function baixaDaDataEmpresarial(Request $request)
    {
        $id_contrato = $request->id_contrato;
        $contrato = ContratoEmpresarial::find($id_contrato);
        $comissao_id = Comissoes::where("contrato_empresarial_id",$contrato->id)->first()->id;


        $parcela = ComissoesCorretoresLancadas::where("comissoes_id",$comissao_id)->where("status_financeiro",0)->where("status_gerente",0)->first()->parcela;



        switch ($parcela) {
            case 1:

                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",1)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",2)
                    ->update(['atual'=>1]);

                // $comissaoCorretora = ComissoesCorretoraLancadas
                //     ::where('comissoes_id',$comissao_id)
                //     ->where('parcela',1)
                //     ->first();
                // if(isset($comissaoCorretora) && $comissaoCorretora) {
                //     $comissaoCorretora->status_financeiro = 1;
                //     $comissaoCorretora->data_baixa = $request->data_baixa;
                //     $comissaoCorretora->save();
                // }

            break;

            case 2:
                //$contrato->financeiro_id = 7;
                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",2)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",3)
                    ->update(['atual'=>1]);

                // $comissaoCorretora = ComissoesCorretoraLancadas
                //     ::where('comissoes_id',$comissao_id)
                //     ->where('parcela',2)
                //     ->first();
                // if(isset($comissaoCorretora) && $comissaoCorretora) {
                //     $comissaoCorretora->status_financeiro = 1;
                //     $comissaoCorretora->data_baixa = $request->data_baixa;

                //     $comissaoCorretora->save();
                // }


            break;

            case 3:

                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",3)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",4)
                    ->update(['atual'=>1]);



            //     $comissaoCorretora = ComissoesCorretoraLancadas
            //     ::where('comissoes_id',$comissao_id)
            //     ->where('parcela',3)
            //     ->first();
            // if(isset($comissaoCorretora) && $comissaoCorretora) {
            //     $comissaoCorretora->status_financeiro = 1;
            //     $comissaoCorretora->data_baixa = $request->data_baixa;
            //     $comissaoCorretora->save();
            // }



            break;

            case 4:

                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",4)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }
                ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",5)
                    ->update(['atual'=>1]);



            //     $comissaoCorretora = ComissoesCorretoraLancadas
            //     ::where('comissoes_id',$comissao_id)
            //     ->where('parcela',4)
            //     ->first();
            // if(isset($comissaoCorretora) && $comissaoCorretora) {
            //     $comissaoCorretora->status_financeiro = 1;
            //     $comissaoCorretora->data_baixa = $request->data_baixa;
            //     $comissaoCorretora->save();
            // }



            break;

            case 5:

                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",5)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                ComissoesCorretoresLancadas
                ::where("comissoes_id",$comissao_id)
                ->where("parcela",6)
                ->update(['atual'=>1]);

            //     $comissaoCorretora = ComissoesCorretoraLancadas
            //     ::where('comissoes_id',$comissao_id)
            //     ->where('parcela',5)
            //     ->first();
            // if(isset($comissaoCorretora) && $comissaoCorretora) {
            //     $comissaoCorretora->status_financeiro = 1;
            //     $comissaoCorretora->data_baixa = $request->data_baixa;
            //     $comissaoCorretora->save();
            // }



            break;

            case 6:

                $contrato->data_baixa = $request->data_baixa;
                $comissao = ComissoesCorretoresLancadas
                    ::where("comissoes_id",$comissao_id)
                    ->where("parcela",6)
                    ->first();
                if($comissao) {
                    $comissao->status_financeiro = 1;
                    $comissao->data_baixa = $request->data_baixa;
                    $comissao->atual = 0;
                    $comissao->save();
                }

                //ContratoEmpresarial::where($id_contrato)->where("parcela",6)->update(["atual" => 1]);

                $comissaoCorretora = ComissoesCorretoraLancadas
                ::where('comissoes_id',$comissao_id)
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
        return $this->recalcularEmpresarial();
    }






    public function editarCampoIndividualmente(Request $request)
    {
        $cliente = Cliente::where("id",$request->id_cliente)->first();
        $dependente = Dependentes::where('cliente_id',$request->id_cliente)->first();
        $contrato = Contrato::where("cliente_id",$request->id_cliente)->first();

        switch($request->alvo) {
             case "valor_adesao":
                $contrato->valor_adesao = str_replace([".",","],["","."],$request->valor);
                $contrato->save();
            break;

            case "valor_contrato":
                $contrato->valor_plano = str_replace([".",","],["","."],$request->valor);
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


    public function editarCampoEmpresarialIndividual(Request $request)
    {


        $contrato = ContratoEmpresarial::where("id",$request->id_cliente)->first();

        switch($request->alvo) {
            case "razao_social_view_empresarial":
                $contrato->razao_social = $request->valor;
                $contrato->save();
            break;

            case "cnpj_view":
                $contrato->cnpj = $request->valor;
                $contrato->save();
            break;

            case "telefone_corretor_view_empresarial":
                $contrato->telefone = $request->valor;
                $contrato->save();
            break;

            case "celular_corretor_view_empresarial":
                $contrato->celular = $request->valor;
                $contrato->save();
            break;

            case "email_odonto_view_empresarial":
                $contrato->email = $request->valor;
                $contrato->save();
            break;

            case "nome_corretor_view_empresarial":
                $contrato->responsavel = $request->valor;
                $contrato->save();
            break;

            case "cod_corretora_view_empresarial":
                $contrato->codigo_corretora = $request->valor;
                $contrato->save();
            break;

            case "cod_saude_view_empresarial":
                $contrato->codigo_saude = $request->valor;
                $contrato->save();
            break;

            case "cod_odonto_view_empresarial":
                $contrato->codigo_odonto = $request->valor;
                $contrato->save();
            break;

            case "senha_cliente_view_empresarial":
                $contrato->senha_cliente = $request->valor;
                $contrato->save();
            break;

            case "valor_plano_saude_view":
                $contrato->valor_plano_saude = $request->valor;
                $contrato->save();
            break;

            case "valor_plano_odonto_view":
                $contrato->valor_plano_odonto = $request->valor;
                $contrato->save();
            break;

            case "valor_plano_view_empresarial":
                $contrato->valor_plano = $request->valor;
                $contrato->save();
            break;

            case "taxa_adesao_view_empresarial":
                $contrato->taxa_adesao = $request->valor;
                $contrato->save();
            break;

            case "plano_adesao_view_empresarial":

            break;

            case "valor_boleto_view_empresarial":

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










}
