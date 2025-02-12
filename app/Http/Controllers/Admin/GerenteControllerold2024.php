<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    Contrato,Cliente,TabelaOrigens,Administradoras,Planos,Acomodacao,CotacaoFaixaEtaria,User,PlanoEmpresarial,ContratoEmpresarial,  
    Comissoes,ComissoesCorretoresLancadas,ComissoesCorretoraConfiguracoes,ComissoesCorretoraLancadas,ComissoesCorretoresConfiguracoes,
    Dependentes,Cancelado, ComissoesCorretoresDefault, MotivoCancelados,
    Premiacoes,PremiacoesCorretoraLancadas,PremiacoesCorretoresLancadas,PremiacoesCorretoraConfiguracoes,PremiacoesCorretoresConfiguracoes,
    ValoresCorretoresLancados
};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Database\Eloquent\Builder;


class GerenteController extends Controller
{
    public function index()
    {
        



        // $contratos = Contrato
        //         ::where("plano_id",1)   
        //         ->whereHas('clientes',function($query){
        //             $query->whereRaw("cateirinha IS NOT NULL");
        //         }) 
                
        //         // ->whereHas('comissao.ultimaComissaoPaga',function($query){
        //         //     $query->whereYear("data",2022);
        //         //     $query->whereMonth('data','08');
        //         // })    
        //         ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        //         ->whereYear("data_vigencia",date('Y'))
        //         ->whereMonth("data_vigencia",date('m'))
        //         ->orderBy("id","desc")
        //         ->get();
    
        //     dd($contratos);        




    //    $dados = Comissoes::with(['contrato','contrato_empresarial','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();
    //    dd($dados);
        // $id = 2;
        // $ano = null;
        // $mes= 04;
        // $quantidade_individual_geral = Contrato::where("plano_id",1)
        // ->whereHas('clientes',function($query)use($id){
        //     if($id) {
        //         $query->where("user_id",$id);
        //     }
            
        // })
        // ->where(function($query) use($ano,$mes){
        //     if($ano) {
        //         $query->whereYear('created_at',$ano);
        //     }
        //     if($mes) {
        //         $query->whereMonth('created_at',$mes);
        //     }
        // })
        // ->count();

        // dd($quantidade_individual_geral);



        // $contratos = Contrato
        //     ::where("plano_id",1)   
        //     ->whereHas('clientes',function($query){
        //         $query->whereRaw("cateirinha IS NOT NULL");
        //     }) 
        //     ->whereHas('comissao.comissoesLancadas',function($query){
        //         $query->where("status_financeiro",1);
        //         $query->where("status_gerente",1);
        //         $query->where("valor","!=",0);
        //     })    
        //     ->with(['administradora','financeiro','cidade','comissao','acomodacao','plano','comissao.comissaoAtualFinanceiro','comissao.ultimaComissaoPaga','somarCotacaoFaixaEtaria','clientes','clientes.user','clientes.dependentes'])
        //     ->orderBy("id","desc")
        //     ->count();
        // dd();    






        //Geral

        $quantidade_geral     = Contrato::count();
        $total_valor_geral = Contrato::selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
        $quantidade_vidas_geral = Cliente::selectRaw("SUM(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;

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
        
        $qtd_atrasado = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->count();
        
        

        $qtd_atrasado_valor = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

       

        $qtd_atrasado_quantidade_vidas = Cliente
        ::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query){
            $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_finalizado = Contrato::where("financeiro_id",11)
        ->count();

        $quantidade_valor_finalizado = Contrato::where("financeiro_id",11)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",11);
            
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado = Contrato::where("financeiro_id",12)->count();     

        $quantidade_valor_cancelado = Contrato::where("financeiro_id",12)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",12);
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        //FIM Geral

        //Individual

        $quantidade_individual_geral     = Contrato::where("plano_id",1)->count();
        
        

        $total_valor_geral_individual = Contrato::where("plano_id",1)->selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
        $quantidade_vidas_geral_individual = Cliente::whereHas('contrato',function($query){
            $query->where("plano_id",1);
        })->selectRaw("if(SUM(quantidade_vidas)>=1,SUM(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;      

        



        $total_quantidade_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",1)
        ->count();

        $total_valor_recebidos_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
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
        ->whereHas('contrato',function($query){
            $query->where("plano_id",1);
        })
        ->selectRaw("if(sum(quantidade_vidas)>0,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;
        
        $total_quantidade_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",1)
        ->count();

        $total_valor_a_receber_individual = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",1)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;       

        $quantidade_vidas_a_receber_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('contrato',function($query){
            $query->where("plano_id",1);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;

        
        $qtd_atrasado_individual = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        
        ->where("plano_id",1)
        ->count();

        $qtd_atrasado_valor_individual = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('clientes',function($query){$query->whereRaw('cateirinha IS NOT NULL');})
        ->where("plano_id",1)
        ->selectRaw("sum(valor_plano) as total_valor_plano")->first()->total_valor_plano;

        

        $qtd_atrasado_quantidade_vidas_individual = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query){
            $query->where("plano_id",1);
            $query->whereIn("financeiro_id",[3,4,5,6,7,8,9,10]);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_finalizado_individual = Contrato::where("financeiro_id",11)->where('plano_id',1)->count();

        $quantidade_valor_finalizado_individual = Contrato::where("financeiro_id",11)->where('plano_id',1)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",11);
            $query->where("plano_id",1);
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_individual = Contrato::where("financeiro_id",12)
        ->where('plano_id',1)
        ->count();     

        $quantidade_valor_cancelado_individual = Contrato::where("financeiro_id",12)->where('plano_id',1)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_individual = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",12);
            $query->where("plano_id",1);
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;

        //Fim Individual

        //Coletivo

        $quantidade_coletivo_geral     = Contrato::where("plano_id",3)->count();

        $total_valor_geral_coletivo = Contrato::where("plano_id",3)->selectRaw("SUM(valor_plano) as total_geral")->first()->total_geral;
        $quantidade_vidas_geral_coletivo = Cliente::whereHas('contrato',function($query){
            $query->where("plano_id",3);
        })->selectRaw("if(SUM(quantidade_vidas)>=1,SUM(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;      

        $total_quantidade_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",3)
        ->count();

        $total_valor_recebidos_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",1);
            $query->where("valor","!=",0);
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
        ->whereHas('contrato',function($query){
            $query->where("plano_id",3);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;
        
        $total_quantidade_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",3)
        ->count();

        $total_valor_a_receber_coletivo = Contrato::whereHas('comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->where("plano_id",3)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as total_valor_plano")->first()->total_valor_plano;       

        $quantidade_vidas_a_receber_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->where("status_financeiro",1);
            $query->where("status_gerente",0);
            $query->where("valor","!=",0);
        })
        ->whereHas('contrato',function($query){
            $query->where("plano_id",3);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_recebidas")
        ->first()
        ->total_quantidade_vidas_recebidas;



        
        $qtd_atrasado_coletivo = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->where("plano_id",3)
        ->count();
        

        $qtd_atrasado_valor_coletivo = Contrato
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        
        ->where("plano_id",3)
        ->selectRaw("sum(valor_plano) as total_valor_plano")
        ->first()
        ->total_valor_plano;

        

        $qtd_atrasado_quantidade_vidas_coletivo = Cliente::whereHas('contrato.comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereHas('contrato',function($query){
            $query->where("plano_id",3);
            $query->whereIn('financeiro_id',[3,4,5,6,7,8,9,10]);
        })
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;

        $qtd_finalizado_coletivo = Contrato::where("financeiro_id",11)->where('plano_id',3)->count();

        $quantidade_valor_finalizado_coletivo = Contrato::where("financeiro_id",11)->where('plano_id',3)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",11);
            $query->where("plano_id",3);
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_coletivo = Contrato::where("financeiro_id",12)
        ->where('plano_id',3)
        ->count();     

        $quantidade_valor_cancelado_coletivo = Contrato::where("financeiro_id",12)->where('plano_id',3)
        ->selectRaw("if(sum(valor_plano)>=1,sum(valor_plano),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_coletivo = Cliente::whereHas('contrato',function($query){
            $query->where("financeiro_id",12);
            $query->where("plano_id",3);
        })->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;
       



        //Fimmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm Coletivo


        //Empresarial

        $quantidade_empresarial_geral  = ContratoEmpresarial::count();
        
        $total_valor_geral_empresarial = ContratoEmpresarial::selectRaw("if(SUM(valor_total)>=1,SUM(valor_total),0) as total_geral")->first()->total_geral;     
        
        $quantidade_vidas_geral_empresarial = ContratoEmpresarial::selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as quantidade_vidas")->first()->quantidade_vidas;

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



        
        $qtd_atrasado_empresarial = ContratoEmpresarial
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->count();

        $qtd_atrasado_valor_empresarial = ContratoEmpresarial
        ::whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->selectRaw("sum(valor_total) as total_valor_plano")->first()->total_valor_plano;

        

        $qtd_atrasado_quantidade_vidas_empresarial = ContratoEmpresarial::whereHas('comissao.comissoesLancadas',function($query){
            $query->whereRaw("DATA < CURDATE()");
            $query->whereRaw("data_baixa IS NULL");
            $query->groupBy("comissoes_id");
        })
        ->whereIn("financeiro_id",[3,4,5,6,7,8,9,10])
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_atrasadas")->first()->total_quantidade_vidas_atrasadas;



        $qtd_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)->count();

        $quantidade_valor_finalizado_empresarial = ContratoEmpresarial::where("financeiro_id",11)
        ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_finalizado")->first()->valor_total_finalizado;

        $qtd_finalizado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",11)
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_finalizadas")->first()->total_quantidade_vidas_finalizadas;

        $qtd_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)->count();     

        $quantidade_valor_cancelado_empresarial = ContratoEmpresarial::where("financeiro_id",12)
        ->selectRaw("if(sum(valor_total)>=1,sum(valor_total),0) as valor_total_cancelado")->first()->valor_total_cancelado;

        $qtd_cancelado_quantidade_vidas_empresarial = ContratoEmpresarial::where("financeiro_id",12)
        ->selectRaw("if(sum(quantidade_vidas)>=1,sum(quantidade_vidas),0) as total_quantidade_vidas_cancelado")->first()->total_quantidade_vidas_cancelado;






        //Fim Empresarial




        // $total_geral = Contrato::selectRaw("sum(valor_plano) as total_geral")->first()->total_geral;
        // $total_recebidos = Contrato::where("financeiro_id","!=",12)->whereHas('comissao.comissoesLancadas',function($query){
        //     $query->where("status_financeiro",1);
        //     $query->where("status_gerente",1);
            
        // })->whereHas('clientes',function($query){$query->whereRaw('cateirinha IS NOT NULL');})->selectRaw("sum(valor_plano) as total_plano")->first()->total_plano;
        //dd($total_recebidos);


        $users = User::where("id","!=",1)->get();
        
        $quat_comissao_a_receber = ComissoesCorretoraLancadas::where("status_financeiro",1)->where("status_gerente",0)->count();
        $quat_comissao_recebido = ComissoesCorretoraLancadas::where("status_financeiro",1)->where("status_gerente",1)->count();

        $valor_quat_comissao_a_receber = ComissoesCorretoraLancadas
            ::selectRaw("sum(valor) as total")
            ->where("status_financeiro",1)
            ->where("status_gerente",0)->first()->total;

        $valor_quat_comissao_recebido = ComissoesCorretoresLancadas
            ::selectRaw("sum(valor) as total")
            ->where("status_financeiro",1)
            ->where("status_gerente",1)->first()->total;
        
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

        $administradoras = Administradoras::orderBy('id','desc')->get();

        return view('admin.pages.gerente.index',[
            "quat_comissao_a_receber" => $quat_comissao_a_receber,
            "quat_comissao_recebido" => $quat_comissao_recebido,
            "valor_quat_comissao_a_receber" => $valor_quat_comissao_a_receber,
            "valor_quat_comissao_recebido" => $valor_quat_comissao_recebido,
            "datas_select" => $datas_select,
            "total_mes_comissao" => $total_mes_comissoes[0]->total,
            "administradoras_mes" => $administradoras_mes,
            "administradoras" => $administradoras,
            "users" => $users,

            "quantidade_geral"           => $quantidade_geral + $quantidade_empresarial_geral,
            "total_valor_geral" => $total_valor_geral + $total_valor_geral_empresarial,
            "quantidade_vidas_geral" => $quantidade_vidas_geral + $quantidade_vidas_geral_empresarial,

            "total_quantidade_recebidos" => $total_quantidade_recebidos + $total_quantidade_recebidos_empresarial,
            "total_valor_recebidos"      => $total_valor_recebidos + $total_valor_recebidos_empresarial,
            "quantidade_vidas_recebidas" => $quantidade_vidas_recebidas + $quantidade_vidas_recebidas_empresarial,
            
            
            "total_quantidade_a_receber" => $total_quantidade_a_receber + $total_quantidade_a_receber_empresarial,
            "total_valor_a_receber" => $total_valor_a_receber + $total_valor_a_receber_empresarial,
            "quantidade_vidas_a_receber" => $quantidade_vidas_a_receber + $quantidade_vidas_a_receber_empresarial,

                      
            "qtd_atrasado" => $qtd_atrasado + $qtd_atrasado_empresarial,
            "qtd_atrasado_valor" => $qtd_atrasado_valor + $qtd_atrasado_valor_empresarial,
            "qtd_atrasado_quantidade_vidas" => $qtd_atrasado_quantidade_vidas + $qtd_atrasado_quantidade_vidas_empresarial,


            "qtd_finalizado" => $qtd_finalizado + $qtd_finalizado_empresarial,
            "quantidade_valor_finalizado" => $quantidade_valor_finalizado + $quantidade_valor_finalizado_empresarial,
            "qtd_finalizado_quantidade_vidas" => $qtd_finalizado_quantidade_vidas + $qtd_finalizado_quantidade_vidas_empresarial,

            "qtd_cancelado" => $qtd_cancelado + $qtd_cancelado_empresarial,
            "quantidade_valor_cancelado" => $quantidade_valor_cancelado + $quantidade_valor_cancelado_empresarial,
            "qtd_cancelado_quantidade_vidas" => $qtd_cancelado_quantidade_vidas + $qtd_cancelado_quantidade_vidas_empresarial,

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

            /********************************************Coletivo */

            "quantidade_coletivo_geral" => $quantidade_coletivo_geral,
            "total_valor_geral_coletivo" => $total_valor_geral_coletivo,
            "total_quantidade_recebidos_coletivo" => $total_quantidade_recebidos_coletivo,
            "quantidade_vidas_geral_coletivo" => $quantidade_vidas_geral_coletivo,

            "total_valor_recebidos_coletivo" => $total_valor_recebidos_coletivo,
            "quantidade_vidas_recebidas_coletivo" => $quantidade_vidas_recebidas_coletivo,
            "total_quantidade_a_receber_coletivo" => $total_quantidade_a_receber_coletivo,


            "total_valor_a_receber_coletivo" => $total_valor_a_receber_coletivo,
            "quantidade_vidas_a_receber_coletivo" => $quantidade_vidas_a_receber_coletivo,
            "qtd_atrasado_coletivo" => $qtd_atrasado_coletivo,
            "qtd_atrasado_valor_coletivo" => $qtd_atrasado_valor_coletivo,
            "qtd_atrasado_quantidade_vidas_coletivo" => $qtd_atrasado_quantidade_vidas_coletivo,
            "qtd_finalizado_coletivo" => $qtd_finalizado_coletivo,
            "quantidade_valor_finalizado_coletivo" => $quantidade_valor_finalizado_coletivo,
            "qtd_finalizado_quantidade_vidas_coletivo" => $qtd_finalizado_quantidade_vidas_coletivo,
            "qtd_cancelado_coletivo" => $qtd_cancelado_coletivo,
            "quantidade_valor_cancelado_coletivo" => $quantidade_valor_cancelado_coletivo,
            "qtd_cancelado_quantidade_vidas_coletivo" => $qtd_cancelado_quantidade_vidas_coletivo,

            /***************** Empresarial ***********************/
            "quantidade_empresarial_geral" => $quantidade_empresarial_geral,
            "total_valor_geral_empresarial" => $total_valor_geral_empresarial,
            "quantidade_vidas_geral_empresarial" => $quantidade_vidas_geral_empresarial,

            "total_quantidade_recebidos_empresarial" => $total_quantidade_recebidos_empresarial,
            "total_valor_recebidos_empresarial" => $total_valor_recebidos_empresarial,
            "quantidade_vidas_recebidas_empresarial" => $quantidade_vidas_recebidas_empresarial,


            "total_quantidade_a_receber_empresarial" => $total_quantidade_a_receber_empresarial,
            "total_valor_a_receber_empresarial" => $total_valor_a_receber_empresarial,
            "quantidade_vidas_a_receber_empresarial" => $quantidade_vidas_a_receber_empresarial,


            'qtd_atrasado_empresarial' => $qtd_atrasado_empresarial,
            "qtd_atrasado_valor_empresarial" => $qtd_atrasado_valor_empresarial,
            "qtd_atrasado_quantidade_vidas_empresarial" => $qtd_atrasado_quantidade_vidas_empresarial,


            "qtd_finalizado_empresarial" => $qtd_finalizado_empresarial,
            "quantidade_valor_finalizado_empresarial" => $quantidade_valor_finalizado_empresarial,
            "qtd_finalizado_quantidade_vidas_empresarial" => $qtd_finalizado_quantidade_vidas_empresarial,


            "qtd_cancelado_empresarial" => $qtd_cancelado_empresarial,
            "quantidade_valor_cancelado_empresarial" => $quantidade_valor_cancelado_empresarial,
            "qtd_cancelado_quantidade_vidas_empresarial" => $qtd_cancelado_quantidade_vidas_empresarial



        ]);
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
                $total_valor_geral_empresarial = ContratoEmpresarial::selectRaw("if(SUM(valor_total)>=1,SUM(valor_total),0) as total_geral")->first()->total_geral;
                $quantidade_vidas_geral_empresarial = ContratoEmpresarial::selectRaw("sum(quantidade_vidas) as quantidade_vidas")->first()->quantidade_vidas;

                $quantidade_total = $quantidade + $quantidade_empresarial_geral;
                $valor_total = $valor + $total_valor_geral_empresarial;
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

                $qtd_atrasado = Contrato
                ::whereHas('comissao.comissoesLancadas',function($query){
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


    public function showTodosDetalheCard($estagio)
    {
        if($estagio == 1) {
            $dados = Comissoes::with(['contrato','contrato.financeiro','contrato_empresarial','contrato_empresarial.financeiro','user','contrato.clientes','comissaoAtualFinanceiro','ultimaComissaoPaga'])->get();    
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










    public function listagem()
    {
        // $dados = Cliente::with(['contrato','contrato.comissao','contrato.administradora','user','contrato.cidade','contrato.financeiro','contrato.comissao.comissaoAtual','contrato.plano'])
        // ->whereHas('contrato.comissao.comissoesLancadas',function($query){
        //     $query->where("status_financeiro",1);
        //     $query->where("status_gerente",0);
        // })->get();

        // $dados = DB::select(
        //     "
        //     SELECT
        //     (SELECT nome FROM administradoras WHERE id = (SELECT administradora_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS administradora,
        //     (SELECT NAME FROM users WHERE users.id = clientes.user_id) AS corretor,
        //     (SELECT nome FROM planos WHERE id = (SELECT plano_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS plano,
        //     (SELECT nome FROM tabela_origens WHERE id = (SELECT tabela_origens_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS tabela_origens,
        //     nome,
        //     (SELECT codigo_externo FROM contratos WHERE contratos.cliente_id = clientes.id) AS codigo_externo,
        //     (
        //       select valor from `comissoes_corretora_lancadas` where `comissoes_corretora_lancadas`.`comissoes_id` =  
        //       (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id))
        //       and `status_financeiro` = 1 and `status_gerente` = 0
        //     ) AS valor,
        //     (
        //       select data_baixa from `comissoes_corretora_lancadas` where `comissoes_corretora_lancadas`.`comissoes_id` =  
        //       (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id))
        //       and `status_financeiro` = 1 and `status_gerente` = 0
        //     ) AS data_baixa,
        //     (
        //       select parcela from `comissoes_corretores_lancadas` where `comissoes_corretores_lancadas`.`comissoes_id` =  
        //       (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id))
        //       and `status_financeiro` = 1 and `status_gerente` = 0
        //     ) AS parcela,
        //     (
        //         select data from `comissoes_corretores_lancadas` where `comissoes_corretores_lancadas`.`comissoes_id` =  
        //         (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id))
        //         and `status_financeiro` = 1 and `status_gerente` = 0
        //      ) AS vencimento,
        //     (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id) AS contrato_id
        //         from `clientes` 
        //         where exists (select * from `contratos` where `clientes`.`id` = `contratos`.`cliente_id` AND 
        //         exists (select * from `comissoes` where `contratos`.`id` = `comissoes`.`contrato_id` AND 
        //         exists (select * from `comissoes_corretores_lancadas` where `comissoes`.`id` = `comissoes_corretores_lancadas`.`comissoes_id` and `status_financeiro` = 1 and `status_gerente` = 0)))");
            
//         $dados = DB::select(
//             "
//             SELECT 
// 			comissoes_corretora_lancadas.id,
//    (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
//    (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
//    (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
//      case when empresarial then
//         (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//      else
//        (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
//      END AS cliente,
//        (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,		
//                 case when empresarial then
//                     (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//                         else
//                     (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
//                     END AS codigo_externo,	
//                     parcela,
//                     valor,
                    
//                     comissoes_corretora_lancadas.data as vencimento,                   
//                     comissoes.id AS comissao
                    
//                 FROM comissoes_corretora_lancadas 
//                 INNER JOIN comissoes ON comissoes.id = comissoes_corretora_lancadas.comissoes_id
//                 WHERE valor != 0
//                 "
//         );

    //     $dados = DB::select(
    //         "
    //         SELECT 
    //         comissoes_corretora_lancadas.id,
    //         comissoes_corretora_lancadas.status_financeiro,
    //         comissoes_corretora_lancadas.status_gerente,
    //         1 AS corretora,
    //         (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    //         (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
    //         (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
    //         case when empresarial then
    //         (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
    //      else
    //        (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
    //      END AS cliente,
    //         (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,		
    //             case when empresarial then
    //         (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
    //             else
    //                     (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
    //                     END AS codigo_externo,
    //                     comissoes_corretora_lancadas.parcela,
    //                     comissoes_corretora_lancadas.valor,
    //                     comissoes_corretora_lancadas.data as vencimento,                   
    //                     comissoes.id AS comissao 
    //         FROM comissoes_corretora_lancadas
    // INNER JOIN comissoes ON comissoes.id = comissoes_corretora_lancadas.comissoes_id WHERE comissoes_corretora_lancadas.valor != 0
    // AND comissoes_corretora_lancadas.status_financeiro = 1 AND comissoes_corretora_lancadas.status_gerente = 0

    // UNION 
    
    // SELECT 
    //     comissoes_corretores_lancadas.id,
    //     comissoes_corretores_lancadas.status_financeiro,
    //     comissoes_corretores_lancadas.status_gerente,
    //     0 AS corretora,
    //     (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora,
    //     (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS corretor,
    //     (SELECT nome FROM planos WHERE planos.id = comissoes.plano_id) AS plano,
    //     case when empresarial then
    //         (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
    //      else
    //        (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
    //      END AS cliente,
    //                      (SELECT nome FROM tabela_origens WHERE tabela_origens.id = comissoes.tabela_origens_id) AS tabela_origens,		
    //                 case when empresarial then
    //                     (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
    //                         else
    //                     (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
    //                     END AS codigo_externo,
    //                     comissoes_corretores_lancadas.parcela,
    //                     comissoes_corretores_lancadas.valor,
    //                     comissoes_corretores_lancadas.data as vencimento,                   
    //                     comissoes.id AS comissao  
    //     FROM comissoes_corretores_lancadas
    // INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id WHERE comissoes_corretores_lancadas.valor != 0
    // AND comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 0
    //         "
    //     );

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
        WHERE status_financeiro = 1 AND status_gerente = 0 AND valor != 0
        GROUP BY comissao
            ");
                
        return $dados;     
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
        // $mes = 5;
        // $ultimoDia = date('d/m/Y', strtotime('2023-' . $mes . '-t'));     
        // $primeiroDia = date('d/m/Y', strtotime('2023-' . $mes . '-01'));

        
        // $allcare = ComissoesCorretoresLancadas
        //     ::where("status_financeiro",1)
        //     ->where("status_apto_pagar",1)
        //     ->where('status_comissao',0)
        //     ->whereHas('comissao.administradoras',function($query){
        //         $query->where("id",1);
        //     })
        //     ->with(['comissao','comissao.contrato','comissao.plano','comissao.administradoras','comissao.contrato.clientes'])
        //     ->get();
            
      

       $user = User::find($id);
       $comissao_valor = DB::select(
            "
                SELECT 
                SUM(valor) as total
                FROM comissoes_corretores_lancadas 
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id        
                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND 
                comissoes_corretores_lancadas.status_gerente = 1 AND 
                MONTH(comissoes_corretores_lancadas.data) = MONTH(NOW()) AND
                comissoes.user_id = $id
            "
       );


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
            "total_a_pagar" => $total_a_pagar
        ]);
        
        
        
    }

    public function aptarPagamento(Request $request)
    {

        $id = $request->id;

        $co = ComissoesCorretoresLancadas::where("id",$request->id)->first();
        $co->status_apto_pagar = 1;
        $co->save();

        return true;




        // $co = ComissoesCorretoresLancadas::where("id",$request->id)->with('comissao.contrato')->first();
        // $co->status_antecipar = 1;
        // $co->data_antecipacao = date('Y-m-d');
        // $co->save();
        // return true;
        // return [
        //     "valor_plano_contratado" => $co->comissao->contrato->valor_plano,
        //     "data_baixa_gerente" => date("d/m/Y")
        // ];
    }


    public function comissaoListagemConfirmadas(Request $request)
    {
        $id = $request->id;

        $dados = DB::select("
        SELECT 
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora, 
        (comissoes.plano_id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,     
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
                       comissoes_corretores_lancadas.valor AS comissao_esperada,	
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas 
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE 
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND  
        comissoes.user_id = {$id} AND valor != 0 AND plano_id = 1
        ORDER BY comissoes.administradora_id
        ");

        return $dados;

    }

    public function comissaoListagemConfirmadasEmpresarial(Request $request)
    {
        $id = $request->id;

        $dados = DB::select("
        SELECT 
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora, 
        (comissoes.plano_id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,     
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
                       comissoes_corretores_lancadas.valor AS comissao_esperada,	
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas 
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE 
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND  
        comissoes.user_id = {$id} AND valor != 0 AND plano_id = 3
        ORDER BY comissoes.administradora_id
        ");

        return $dados;
    }





    public function comissaoListagemConfirmadasColetivo(Request $request)
    {
        $id = $request->id;

        $dados = DB::select("
        SELECT 
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora, 
        (comissoes.plano_id) AS plano,
        comissoes_corretores_lancadas.data_antecipacao as data_antecipacao,     
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
                       comissoes_corretores_lancadas.valor AS comissao_esperada,	
                       if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor) AS comissao_recebida,
                    comissoes_corretores_lancadas.id,
                    comissoes_corretores_lancadas.comissoes_id,
                    comissoes_corretores_lancadas.parcela
        FROM comissoes_corretores_lancadas 
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        WHERE 
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_apto_pagar = 1 AND  
        comissoes.user_id = {$id} AND valor != 0 AND plano_id = 3
        ORDER BY comissoes.administradora_id
        ");

        return $dados;

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
                    AS porcentagem_parcela_corretor    
   
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





    public function recebidasColetivo(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT 
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora, 
        comissoes_corretores_lancadas.created_at AS data_criacao,
        comissoes.plano_id as plano,
        contratos.codigo_externo AS orcamento,
        (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
        contratos.codigo_externo AS orcamento,
        (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
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
                    AS porcentagem_parcela_corretor    
   
               
               
   
        FROM comissoes_corretores_lancadas 
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contratos ON comissoes.contrato_id = contratos.id
        WHERE 
        comissoes_corretores_lancadas.status_financeiro = 1 AND comissoes_corretores_lancadas.status_gerente = 1 AND comissoes_corretores_lancadas.status_apto_pagar != 1 AND  
        comissoes.user_id = {$id}  AND valor != 0 AND status_comissao = 0 AND contratos.plano_id = 3 AND comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");

        return $dados;
    }

    public function recebidoEmpresarial(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT 
        (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora, 
        comissoes_corretores_lancadas.created_at AS data_criacao,
        comissoes.plano_id as plano,
        contrato_empresarial.codigo_externo AS orcamento,
        contrato_empresarial.quantidade_vidas AS quantidade_vidas,
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
                        )  AS porcentagem_parcela_corretor
								      
   
        FROM comissoes_corretores_lancadas 
        INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
        INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
        WHERE 
        comissoes_corretores_lancadas.status_financeiro = 1 AND 
								comissoes_corretores_lancadas.status_gerente = 1 AND 
								comissoes_corretores_lancadas.status_apto_pagar != 1 AND  
        comissoes.user_id = {$id}  AND comissoes_corretores_lancadas.valor != 0 AND 
								status_comissao = 0 AND 
								comissoes_corretores_lancadas.status_apto_pagar != 1
        ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }






    public function comissaoMesDiferente(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
                SELECT 
                
                comissoes_corretores_lancadas.id,
                comissoes_corretores_lancadas.parcela,
                comissoes_corretores_lancadas.created_at AS data_criacao,
                contratos.codigo_externo AS orcamento,
                DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data, 
                comissoes_corretores_lancadas.valor,
                (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
                comissoes_corretores_lancadas.data_baixa as data_baixa,
                (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,
                
                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS valor_plano_contratado,
                
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

                case when empresarial then
   				    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
   	            ELSE 			
				    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                END AS cliente,
                
                (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora
                
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

    public function coletivoAReceber(Request $request) 
    {
        $id = $request->id;
        $dados = DB::select("
                SELECT 
                comissoes_corretores_lancadas.id,
                comissoes_corretores_lancadas.parcela,
                comissoes_corretores_lancadas.created_at AS data_criacao,
                comissoes_corretores_lancadas.data_baixa as data_baixa,
                contratos.codigo_externo AS orcamento,

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







                case when empresarial then
                (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
                else
                (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
                END AS valor_plano_contratado,
                DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data, 
                comissoes_corretores_lancadas.valor,
                (SELECT quantidade_vidas FROM clientes WHERE clientes.id = contratos.cliente_id) AS quantidade_vidas,
                (SELECT plano_id FROM comissoes WHERE comissoes_corretores_lancadas.comissoes_id = comissoes.id) AS plano,
                case when empresarial then
   				    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
   	            ELSE 			
				    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
                END AS cliente,
                (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora
                FROM comissoes_corretores_lancadas 
                INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
                INNER JOIN contratos ON comissoes.contrato_id = contratos.id
                WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND 
                comissoes_corretores_lancadas.status_gerente = 0 AND 
                comissoes_corretores_lancadas.status_apto_pagar != 1 AND
                comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0 AND contratos.plano_id = 3
                ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }

    public function empresarialAReceber(Request $request)
    {
        $id = $request->id;
        $dados = DB::select("
        SELECT 
            comissoes_corretores_lancadas.id,
            comissoes_corretores_lancadas.parcela,
            comissoes_corretores_lancadas.created_at AS data_criacao,
            comissoes_corretores_lancadas.data_baixa as data_baixa,
            contrato_empresarial.codigo_externo AS orcamento,
            case when comissoes.empresarial = 1 then
            (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            else
            (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
            END AS valor_plano_contratado,
            DATE_FORMAT(comissoes_corretores_lancadas.data,'%d/%m/%Y') AS data,
            comissoes_corretores_lancadas.valor,
            contrato_empresarial.quantidade_vidas AS quantidade_vidas,
                    contrato_empresarial.plano_id AS plano,	

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




            case when comissoes.empresarial = 1 then
                    (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
            ELSE 			
                    (SELECT nome FROM clientes WHERE id = ((SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id)))
            END AS cliente,
            (SELECT nome FROM administradoras WHERE administradoras.id = comissoes.administradora_id) AS administradora

                    FROM comissoes_corretores_lancadas 

            INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
            INNER JOIN contrato_empresarial ON comissoes.contrato_empresarial_id = contrato_empresarial.id
            WHERE comissoes_corretores_lancadas.status_financeiro = 1 AND 
            comissoes_corretores_lancadas.status_gerente = 0 AND 
            comissoes_corretores_lancadas.status_apto_pagar != 1 AND
            comissoes.user_id = {$id} AND comissoes_corretores_lancadas.valor != 0
            ORDER BY comissoes.administradora_id
        ");
        return $dados;
    }

    public function criarPdfPagamento()
    {
        $dados = Administradoras
            ::with(['comissao','comissao.comissoesLancadasCorretoraQuantidade'])
        ->get();

        $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/logo-certa.jpg")));


        
       return view('admin.pages.gerente.pdf',[
            "dados" => $dados,
            "logo" => $logo
       ]);
    }

    public function finalizarPagamento(Request $request) 
    {
        $mes = $request->mes;
        
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

        $logo = 'data:image/png;base64,'.base64_encode(file_get_contents(public_path("storage/logo-certa.jpg")));

        // $ids = explode("|",$request->ids);

        // $comissao = str_replace(["R$"," "],["",""],$request->comissao);
        // $comissao_final = trim(html_entity_decode($comissao), " \t\n\r\0\x0B\xC2\xA0");
        // //return (float) $comissao_final;
        // // return str_replace([".",","],["","."],$request->salario);
        


        // DB::table("comissoes_corretores_lancadas")->whereIn('id', $ids)->update(['status_comissao' => 1]);
        // $va = new ValoresCorretoresLancados();
        // $va->user_id = $request->user_id;
        // $va->valor_comissao = str_replace([".",","],["","."], $comissao_final);
        // $va->valor_salario = str_replace([".",","],["","."],$request->salario);
        // $va->valor_premiacao = str_replace([".",","],["","."],$request->premiacao);
        // $va->data = date("Y-m-d");
        // if($va->save()) {
        //     return "sucesso";
        // } else {
        //     return "error";
        // }
        
        // $dados = DB::table("comissoes_corretores_lancadas")->where('status_financeiro', 1)->where('status_apto_pagar',1)->where('status_comissao',0)->get();
        // $dados = ComissoesCorretoresLancadas
        //     ::where("status_financeiro",1)
        //     ->where("status_apto_pagar",1)
        //     ->where('status_comissao',0)
        //     ->with(['comissao','comissao.contrato','comissao.plano','comissao.administradoras','comissao.contrato.clientes'])
        //     ->get();

        $individual = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        ->where("status_apto_pagar",1)
        ->where('status_comissao',0)
        ->whereHas('comissao.plano',function($query){
            $query->where("id",1);
        })
        ->with(['comissao','comissao.contrato','comissao.plano','comissao.administradoras','comissao.contrato.clientes'])
        ->get();
        
        

        $coletivo = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        ->where("status_apto_pagar",1)
        ->where('status_comissao',0)
        ->whereHas('comissao.plano',function($query){
            $query->where("id",3);
        })
        ->with(['comissao','comissao.contrato','comissao.plano','comissao.administradoras','comissao.contrato.clientes'])
        ->get();

        $empresarial = ComissoesCorretoresLancadas
        ::where("status_financeiro",1)
        ->where("status_apto_pagar",1)
        ->where('status_comissao',0)
        ->whereHas('comissao.plano',function($query){
            $query->where("id","!=",1);
            $query->where("id","!=",3);
        })
        ->with(['comissao','comissao.contrato_empresarial','comissao.plano','comissao.administradoras','comissao.contrato.clientes'])
        ->get();
      
             
        $primeiroDia = date('d/m/Y', strtotime('2023-' . $mes . '-01'));    
        $ultimoDia = date('t/m/Y', strtotime('2023-' . $mes . '-01'));    
           

        $pdf = PDF::loadView('admin.pages.gerente.pdf-folha',[
            "individual" => $individual,
            "coletivo" => $coletivo,
            "empresarial" => $empresarial,
            "meses" => $mes_folha,
            "salario" => $request->salario,
            "premiacao" => $request->premiacao,
            "comissao" => $request->comissao,
            "total" => $request->total,
            "logo" => $logo,
            "primeiro_dia" => $primeiroDia,
            "ultimo_dia" => $ultimoDia,
            "user" => $user
        ]);

        return $pdf->download("folha.pdf");

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



    // public function comissao()
    // {
    //     $dados = DB::select(
    //         "
    //         SELECT
	// 	    (SELECT nome FROM administradoras WHERE id = (SELECT administradora_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS administradora,
    //         (SELECT NAME FROM users WHERE users.id = clientes.user_id) AS corretor,
    //         (SELECT nome FROM planos WHERE id = (SELECT plano_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS plano,
    //         (SELECT nome FROM tabela_origens WHERE id = (SELECT tabela_origens_id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS tabela_origens,
    //         nome,
    //         (SELECT codigo_externo FROM contratos WHERE contratos.cliente_id = clientes.id) AS codigo_externo,
    //         (
    //         select COUNT(*) from `comissoes_corretores_lancadas` where `comissoes_corretores_lancadas`.`comissoes_id` =  
    //         (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id))
    //         and `status_financeiro` = 1 and `status_gerente` = 1
    //         ) AS quantidade,
    //         (SELECT id FROM comissoes WHERE contrato_id = (SELECT id FROM contratos WHERE contratos.cliente_id = clientes.id)) AS comissao
    //         from `clientes` 
    //         where exists (select * from `contratos` where `clientes`.`id` = `contratos`.`cliente_id` AND 
    //         exists (select * from `comissoes` where `contratos`.`id` = `comissoes`.`contrato_id` AND 
    //         exists (select * from `comissoes_corretores_lancadas` where `comissoes`.`id` = `comissoes_corretores_lancadas`.`comissoes_id` and `status_financeiro` = 1 and `status_gerente` = 1)))"
    //         );
    //     return $dados;     
    // }

    public function detalhe($id) 
    {
        

//         $dados = DB::select("
//         SELECT 
//         comissoes_corretores_lancadas.parcela,
//         comissoes_corretores_lancadas.id AS id_corretor,
// 		comissoes_corretora_lancadas.id AS id_corretora,
//         (SELECT NAME FROM users WHERE users.id = comissoes.user_id) AS nome_corretor,
//         (SELECT id FROM users WHERE users.id = comissoes.user_id) AS id_corretor,
//         if(comissoes_corretora_lancadas.valor_pago,comissoes_corretora_lancadas.valor_pago,0) AS valor_pago,
//         if(comissoes_corretora_lancadas.porcentagem_paga,comissoes_corretora_lancadas.porcentagem_paga,0) AS porcentagem_paga,
//         case when empresarial then
//             (SELECT codigo_externo FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//         else
//             (SELECT codigo_externo FROM contratos WHERE contratos.id = comissoes.contrato_id)
//             END AS codigo_externo,
//      comissoes_corretores_lancadas.data AS vencimento,
//      case when empresarial then
//  (SELECT valor_plano FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//    else
//    (SELECT valor_plano FROM contratos WHERE contratos.id = comissoes.contrato_id)
//  END AS valor_plano_contratado,
//   comissoes_corretora_lancadas.data_baixa AS data_baixa,  
//      (SELECT valor FROM comissoes_corretora_configuracoes  WHERE  plano_id = comissoes.plano_id AND  administradora_id = comissoes.administradora_id AND 
//       tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora,

//      (SELECT id FROM comissoes_corretora_configuracoes WHERE  plano_id = comissoes.plano_id AND administradora_id = comissoes.administradora_id AND 
//      tabela_origens_id = comissoes.tabela_origens_id AND parcela = comissoes_corretora_lancadas.parcela) AS porcentagem_parcela_corretora_id,

//      comissoes_corretora_lancadas.valor AS comissao_valor_corretora,

//      if(comissoes_corretores_lancadas.valor_pago,comissoes_corretores_lancadas.valor_pago,0) as comissao_valor_pago_corretor,
//      if(comissoes_corretores_lancadas.porcentagem_paga,comissoes_corretores_lancadas.porcentagem_paga,0) as comissao_porcentagem_pago_corretor,

//          comissoes_corretores_lancadas.valor AS comissao_valor_corretor,

//     (SELECT valor FROM comissoes_corretores_default
//     WHERE 
//     plano_id = comissoes.plano_id AND 
//     administradora_id = comissoes.administradora_id AND 
//     tabela_origens_id = comissoes.tabela_origens_id AND
//     parcela = comissoes_corretora_lancadas.parcela
//     ) AS porcentagem_parcela_corretores,

//     (SELECT id FROM comissoes_corretores_default
//     WHERE 
//     plano_id = comissoes.plano_id AND 
//     administradora_id = comissoes.administradora_id AND 
//     tabela_origens_id = comissoes.tabela_origens_id AND
//     parcela = comissoes_corretora_lancadas.parcela
//     ) AS porcentagem_parcela_corretor_id,

//     case when empresarial then
//       (SELECT responsavel FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//    else
//       (SELECT nome FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
//    END AS cliente,
//    case when empresarial then
//       (SELECT cnpj FROM contrato_empresarial WHERE contrato_empresarial.id = comissoes.contrato_empresarial_id)
//    else
//       (SELECT cpf FROM clientes WHERE id = (SELECT cliente_id FROM contratos WHERE contratos.id = comissoes.contrato_id))
//    END AS cliente_cpf
//    FROM comissoes_corretores_lancadas 
//    INNER JOIN comissoes_corretora_lancadas ON comissoes_corretora_lancadas.parcela = comissoes_corretores_lancadas.parcela
//    INNER JOIN comissoes ON comissoes.id = comissoes_corretores_lancadas.comissoes_id
//    WHERE comissoes_corretores_lancadas.comissoes_id = $id AND comissoes_corretora_lancadas.comissoes_id = $id AND comissoes_corretores_lancadas.status_financeiro = 1 AND
//    comissoes_corretores_lancadas.status_gerente = 0 
//    AND	
//      (comissoes_corretores_lancadas.valor != 0 OR comissoes_corretora_lancadas.valor != 0)
//    GROUP BY comissoes_corretores_lancadas.parcela	
//         "); 

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


        
        
        
        

        

        

        return view('admin.pages.gerente.detalhe',[
            "dados" => $dados,
            "cliente" => isset($dados[0]->cliente) && !empty($dados[0]->cliente) ? $dados[0]->cliente : "",
            "cpf" => isset($dados[0]->cliente_cpf) && !empty($dados[0]->cliente_cpf) ? $dados[0]->cliente_cpf : "",
            "valor_plano" => isset($dados[0]->valor_plano_contratado) && !empty($dados[0]->valor_plano_contratado) ? $dados[0]->valor_plano_contratado : "",
            "valor_corretora" => isset($dados[0]->comissao_valor_corretora) && !empty($dados[0]->comissao_valor_corretora) ? $dados[0]->comissao_valor_corretora : ""
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

            $valor_plano = $request->valor_plano;
            $porcentagem = $request->valor;
            $resultado = ($valor_plano * $porcentagem) / 100;
            $id = $request->id;
            $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
            $alt->valor_pago = $resultado;
           
            $id_default = $request->default_corretor;
            if($alt->save()) {
                $conf = ComissoesCorretoresDefault::where("id",$id_default)->first();
                $conf->valor = $porcentagem;
                if($conf->save()) {
                    return [
                        "valor" => number_format($resultado,2,",","."),
                        "porcentagem" => $porcentagem
                    ];
                }
            } else {
                return "error"; 
            }



        } else {
            $id = $request->id;
            $valor = str_replace([".",","],["","."],$request->valor);
            
            $valor_plano = $request->valor_plano;
            $porcentagem = floor(($valor / $valor_plano) * 100);
            $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
            $alt->valor_pago = $valor;
           
            $alt->porcentagem_paga = $porcentagem;
            if($alt->save()) {
                return $porcentagem;
            } else {
                return "error";
            }
            
        }



        


        
    }

    public function mudarComissaoCorretorGerente(Request $request)
    {
        $id = $request->id;
        $valor = str_replace([".",","],["","."],$request->valor);
        $valor_plano =  str_replace(["R$ ",".",","],["","","."],$request->valor_plano);
        $porcentagem = floor(($valor / $valor_plano) * 100);
        $alt = ComissoesCorretoresLancadas::where("id",$id)->first();
        $alt->valor_pago = $valor; 
        $alt->porcentagem_paga = $porcentagem;       
        $alt->save();
        return $porcentagem;
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








}
