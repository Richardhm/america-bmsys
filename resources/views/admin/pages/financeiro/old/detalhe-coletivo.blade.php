@extends('adminlte::page')
@section('title', 'Gerenciavel')
@section('plugins.Sweetalert2',true)
@section('content_top_nav_right')
    <!-- <li class="nav-item mostrar_comissao"><a href="" class="nav-link div_info text-white"><i class='fas fa-eye'></i></a></li> -->
    <li class="nav-item"><a class="nav-link text-white" href="{{route('orcamento.search.home')}}">Tabela de Preço</a></li>
    <li class="nav-item"><a class="nav-link text-white" href="{{route('home.administrador.consultar')}}">Consultar</a></li>
    
    <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt text-white"></i></a>
@stop

@section('content_header')
    <h1 class=" border-bottom border-dark">Histórico</h1>

    @if($cancelados >= 1)
        <p class="alert alert-danger text-center mt-3">Este plano foi cancelado</p>   
    @endif
@stop


@section('content')



<input type="hidden" id="cliente_id_alvo" value="{{$dados->clientes->id}}">



<div class="modal fade" id="dataBaixaModal" tabindex="-1" role="dialog" aria-labelledby="dataBaixaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataBaixaModalLabel">Data Da Baixa?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" name="data_da_baixa" id="data_da_baixa" method="POST">
                    <input type="date" name="data_baixa" id="data_baixa" class="form-control form-control-sm">
                    <input type="hidden" name="comissao_id" id="comissao_id_baixa" value="{{$dados->comissao->id}}">  
                                    
                    <div id="error_data_baixa">
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            </form>
            </div>
        </div>
    </div>





<div class="modal fade" id="cancelarModal" tabindex="-1" aria-labelledby="cancelarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelarModalLabel">Cancelados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" name="formulario_cancelados" id="formulario_cancelados">
                        <input type="hidden" name="comissao_id_cancelado" id="comissao_id_cancelado" value="{{$dados->comissao->id}}">
                        <div class="form-group">
                            <label for="">Data Baixa:</label>
                            <input type="date" name="date" id="date" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="motivo">Motivo Cancelamento:</label>
                            <select name="motivo" id="motivo" class="form-control">
                                <option value="">--Escolher o Motivo--</option>
                                
                                @foreach($motivo_cancelados as $mm) 
                                    <option value="{{$mm->id}}">{{$mm->nome}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="obs">Observação:</label> 
                            <textarea name="obs" id="obs" cols="30" rows="4" class="form-control"></textarea>
                        </div>

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>

                    </form>
                </div>            
            </div>
        </div>
    </div>



    <input type="hidden" id="excluir_cliente" value="{{$dados->clientes->id}}">

    <input type="hidden" id="data_cliente" value="{{$dados->clientes->id}}">
    <input type="hidden" id="data_contrato" value="{{$dados->id}}">

    <input type="hidden" id="data_financeiro" value="{{$dados->financeiro_id}}">

    



    <main class="container_full_cards">

        <section class="p-1 card_info">

        <div class="d-flex">
                                
            <div style="flex-basis:25%;">
                <span class="text-white" style="font-size:0.81em;">Administradora:</span>
                <input type="text" value="{{$dados->administradora->nome}}" name="administradora_individual" id="administradora_individual" class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:33%;margin:0 1%;">    
                <span class="text-white" style="font-size:0.81em;">Tipo Plano:</span>
                <input type="text" value="{{$dados->plano->nome}}"   id="tipo_plano_individual" class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:40%;margin-top:1%;" id="status">
                <span class="text-white" style="margin:0;padding:0;font-size:0.81em;display:flex;">
                    <span style="flex-basis:50%;">Status:</span>
                    <div class="container_edit" style="flex-basis:50%;font-size:0.9em;margin-top:1px;display:flex;justify-content: flex-end;">
                        <i class="fas fa-edit text-white editar_btn"></i>
                    </div>        
                </span>    
                <input type="text" id="status" value="{{$status != null ? $status : $dados->financeiro->nome}}" class="form-control form-control-sm" readonly>
            </div>    
    
    
        </div>
    
        <div class="d-flex">
            
            <div style="flex-basis:40%;">
                <span class="text-white" style="font-size:0.81em;">Cliente:</span>
                <input type="text" name="cliente" id="cliente" class="form-control form-control-sm" value="{{$dados->clientes->nome}}" readonly>
            </div>
            
            <div style="flex-basis:28%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Data Nascimento:</span>
                <input type="text" name="data_nascimento" id="data_nascimento" class="form-control form-control-sm" value="{{date('d/m/Y',strtotime($dados->clientes->data_nascimento))}}" readonly>
            </div>
            
            <div style="flex-basis:30%;">
                <span class="text-white" style="font-size:0.81em;">Codigo Externo:</span>
                <input type="text" name="codigo_externo" id="codigo_externo_individual" value="{{$dados->codigo_externo}}" class="form-control form-control-sm" readonly>
            </div>    

        </div>
    
        <div class="d-flex">
            <div style="flex-basis:33%;">
                <span class="text-white" style="font-size:0.81em;">CPF:</span>
                <input type="text" id="cpf" class="form-control form-control-sm" value="{{$dados->clientes->cpf}}" readonly>
            </div>

            <div style="flex-basis:33%;margin:0 1%;display:flex;align-items: flex-end;">
                <span style="flex-basis:90%;">

                <span class="text-white" style="font-size:0.81em;">Celular:</span>
                <input type="text" id="celular" value="{{$dados->clientes->celular}}" class="form-control form-control-sm" readonly>




                </span>
                

                <a class="" style="background: #25cb66;flex-basis:10%;
                    border-radius: 8px !important;
                    padding: 3px 7px;
                    text-align: center;
                    color:#FFF;
                    font-weight: bold;
                    margin-bottom: 0px;
                    text-decoration:none;" href="https://api.whatsapp.com/send?phone=55{{$dados->clientes->celular}}&amp;text=Oi tudo bem?" target="_blank" rel="nofollow">
                    <i class="fab fa-whatsapp"></i>
                </a>


            </div>

            <div style="flex-basis:33%;">
                <span class="text-white" style="font-size:0.81em;">Telefone:</span>
                <input type="text" id="telefone" class="form-control form-control-sm" value="{{$dados->clientes->telefone ?? ''}}" readonly>
            </div>
            
        </div>
    
    
        <div class="d-flex">
            
            

            <div style="flex-basis:40%;">
                <span class="text-white" style="font-size:0.81em;">Email:</span>
                <input type="text" id="email" class="form-control form-control-sm" value="{{$dados->clientes->email}}" readonly>
            </div>

            <div style="flex-basis:18%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">CEP:</span>
                <input type="text" id="cep" value="{{$dados->clientes->cep}}" class="form-control form-control-sm" readonly>
            </div>
            <div style="flex-basis:35%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">Cidade:</span> 
                <input type="text" id="cidade" class="form-control  form-control-sm" value="{{$dados->clientes->cidade}}" readonly>
            </div>
            
            <div style="flex-basis:10%;">
                <span class="text-white" style="font-size:0.81em;">UF:</span>
                <input type="text" id="uf" class="form-control form-control-sm" value="{{$dados->clientes->uf}}" readonly>
            </div>      



        </div>
    
    
        <div class="d-flex">
            
            <div style="flex-basis:30%;">
                <span class="text-white" style="font-size:0.81em;">Bairro:</span>
                <input type="text" id="bairro" class="form-control form-control-sm" value="{{$dados->clientes->bairro}}" readonly>
            </div>   
            
            <div style="flex-basis:40%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Rua:</span>
                <input type="text" id="rua" class="form-control form-control-sm" value="{{$dados->clientes->rua}}" readonly>
            </div>

            <div style="flex-basis:29%;">
                <span class="text-white" style="font-size:0.81em;">Complemento:</span>
                <input type="text" id="complemento" class="form-control form-control-sm" value="{{$dados->clientes->complemento}}" readonly>
            </div>    


        </div>
                               
        <div class="d-flex">
            <div style="flex-basis:18%;">
                <span class="text-white" style="font-size:0.81em;">Data Contrato:</span>
                <input type="text" name="data_contrato" id="data_contrato" class="form-control form-control-sm" value="{{date('d/m/Y',strtotime($dados->clientes->created_at))}}" readonly>
            </div>

            <div style="flex-basis:18%;margin:0 1%;">
                <span class="text-white" style="font-size:0.81em;">Valor Contrato:</span>
                <input type="text" name="valor_contrato" id="valor_contrato" value="R$ {{number_format($dados->valor_plano,2,',','.')}}" class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:18%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">Valor Adesão:</span>
                <input type="text" name="valor_adesao" id="valor_adesao" value="R$ {{number_format($dados->valor_adesao,2,',','.')}}"  class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:18%;margin-right:1%;">
                <span class="text-white" style="font-size:0.81em;">Data Boleto:</span>
                <input type="text" name="data_boleto" id="data_boleto" value="{{date('d/m/Y',strtotime($dados->data_boleto))}}" class="form-control  form-control-sm" readonly>
            </div>


            <div style="flex-basis:8%;margin-right:1%;">    
                <span class="text-white" style="font-size:0.81em;">Vidas</span>
                <input type="text" name="quantidade_vidas" id="quantidade_vidas_individual_cadastrar" value="{{$dados->somarCotacaoFaixaEtaria[0]->soma ?? $dados->clientes->quantidade_vidas}}" class="form-control  form-control-sm" readonly>
            </div>

            <div style="flex-basis:18%;">
                <span class="text-white" style="font-size:0.81em;">Data Vigência:</span>
                <input type="text" name="data_vigencia" id="data_vigencia" class="form-control  form-control-sm" value="{{date('d/m/Y',strtotime($dados->data_vigencia))}}" readonly>
            </div>
                
        </div>
    
        
        <div class="d-flex" style="justify-content: space-between;">
            <div style="flex-basis:48%;">
                <span class="text-white" style="font-size:0.81em;">Desconto Corretora:</span>
                <input type="text" id="desconto_corretora" class="form-control  form-control-sm" value="{{number_format($dados->desconto_corretora,2,',','.') ?? 0}}" readonly>
            </div>

            <div style="flex-basis:48%;">
                <span class="text-white" style="font-size:0.81em;">Desconto Corretor:</span>
                <input type="text" id="desconto_corretor" class="form-control  form-control-sm" value="{{number_format($dados->desconto_corretor,2,',','.') ?? 0}}" readonly>
            </div>
        </div>
        
        
            <div class="d-flex" style="justify-content: space-between;">
                <div style="flex-basis:48%;">
                    <span class="text-white" style="font-size:0.81em;">Nome Responsavel:</span>
                    <input type="text" id="nome_responsavel" class="form-control  form-control-sm" value="{{$dependentes->nome ?? ''}}" readonly>
                </div>

                <div style="flex-basis:48%;">
                    <span class="text-white" style="font-size:0.81em;">CPF Responsavel:</span>
                    <input type="text" id="cpf_responsavel" class="form-control  form-control-sm" value="{{$dependentes->cpf ?? ''}}" readonly>
                </div>
            </div>
        


            
        </section>   
        @php            
        @endphp
        <section class="historico_corretor">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="text-center mt-1 ml-1">Pagamentos</h5>
                <p class="align-self-center mt-3 mr-2">{{$dados->clientes->user->name}}</p>
            </div>
            

            <table class="table table-sm h-50" style="margin:0;padding:0;">
                <thead>
                    <tr>
                        <th style="font-size:0.875em;">Parcela</th>
                        <th style="font-size:0.875em;">Contrato</th>
                        <th style="font-size:0.875em;">Vencimento</th>
                        <th style="font-size:0.875em;">Valor</th>
                        <th style="font-size:0.875em;">Baixa</th>
                        <th style="font-size:0.875em;margin-left:10px;text-align:center;" align="center">Atrasado</th>
                        <th style="font-size:0.875em;text-align:right;">
                            <span style="margin-right:12px;">
                                Comissão
                            </span>
                        </th>
                        <th>Status Comissao</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_cliente = 0;
                        $total_comissao = 0;
                    @endphp    
                    @foreach($dados->comissao->comissoesLancadas as $kk => $cr)
                            @php
                                if(!empty($cr->data_baixa)):
                                    $total_comissao += $cr->valor;
                                else: 
                                    $total_cliente += $cr->valor;
                                endif;
                            @endphp
                        <tr>
                            <td class="" style="font-size:0.875em;">
                                @switch($cr->parcela)
                                    @case(1)
                                        Pag. Adesão
                                    @break;

                                    @case(2)
                                        Pag. Vigência 
                                    @break;

                                    @case(3)
                                        Pag. 2º Parcela
                                    @break;

                                    @case(4)
                                        Pag. 3º Parcela
                                    @break;

                                    @case(5)
                                        Pag. 4º Parcela
                                    @break;

                                    @case(6)
                                        Pag. 5º Parcela
                                    @break;

                                    @case(7)
                                        Pag. 6º Parcela
                                    @break;
                                @endswitch
                               
                            </td>
                            <td class="text-center" style="font-size:0.875em;">
                                {{$dados->codigo_externo}}
                                
                            </td>
                            <td style="font-size:0.875em;">{{date('d/m/Y',strtotime($cr->data))}}</td>
                            <td style="font-size:0.875em;">
                                
                                @if($cr->parcela == 1) 
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano ,2,",",".")}}</span>
                                @else
                                    <span style="margin-left:10px;">{{number_format($dados->valor_plano,2,",",".")}}</span>
                                @endif
                                


                            </td>
                            <td style="font-size:0.875em;">
                                @if(empty($cr->data_baixa) && $cr->cancelados == 1)
                                    <span style="margin-left:20px;color:red;">Cancelado</span>
                                @elseif(empty($cr->data_baixa)) 
                                    <span style="margin-left:20px;">---</span>
                                @else
                                    <span style="margin-left:20px;">{{date('d/m/Y',strtotime($cr->data_baixa))}}</span>
                                @endif
                                     
                            <td style="font-size:0.875em;text-align:center;">{{$cr->quantidade_dias}}</td>
                            <td style="font-size:0.875em;" align="right">
                                @if($cr->valor > 0)
                                    <span style="margin-right:15px;">{{number_format($cr->valor,2,',','.')}}</span>
                                @else 
                                    <span style="margin-right:28px;">---</span>
                                @endif
                            </td>
                            <td align="center">---</td>
                        </tr>
                    @endforeach
                </tbody>
                
            </table>

            <div class="d-flex align-items-end align-self-end" style="height:15%;">
                <div class="d-flex justify-content-between align-items-center" style="flex-basis:100%;">
                      
                    <p class="ml-3">Comissão Recebida: R$ @php  echo number_format($total_comissao,2,",",".")  @endphp</p>  
                    <p class="mr-3">Comissão a Receber: R$ @php  echo number_format($total_cliente,2,",",".")  @endphp</p>      
                </div>
                         
            </div>

            

        </section>    
        
        
        
        

    </main>  
    <div class="d-flex mt-3 " style="flex-basis:100%;justify-content: flex-end;">
        <div class="d-flex" style="flex-basis:50%;justify-content: flex-end;">

        @if($cancelados == 0)
                                
        @if($status == null) 
            @switch($dados->financeiro_id)

                @case(1)
                    <button class="btn btn-danger w-50 mr-1 excluir_coletivo">Excluir</button>
                    <button class="btn btn-success w-50 next">Conferido</button>                  
                @break

                @case(2)
                    <button class="btn btn-danger w-50 mr-2 excluir_coletivo">Excluir</button>
                    <button class="btn btn-success w-50 next">Emitiu Boleto</button>
                @break 

                @case(3)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                    <button class="btn btn-success w-50 pagamento_adesao next">Pagar Vigencia</button>
                @break

                
            @endswitch    
          
        @else    
                                
            @switch($parcela)

                @case(3)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
                    <button class="btn btn-success w-50 pagamento_segunda_parcela next">Pagar 2º Parcela</button>  
                @break

                @case(4)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
	                <button class="btn btn-success w-50 pagamento_terceira_parcela next">Pagar 3º Parcela</button>
                @break 

                @case(5)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
	                <button class="btn btn-success w-50 pagamento_quarta_parcela next">Pagar 4º Parcela</button>
                @break 

                @case(6)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
	                <button class="btn btn-success w-50 pagamento_quinta_parcela next">Pagar 5º Parcela</button>
                @break
                
                @case(7)
                    <button class="btn btn-danger w-50 mr-2 cancelar">Cancelar</button>
	                <button class="btn btn-success w-50 pagamento_quinta_parcela next">Pagar 6º Parcela</button>
                @break       

            @endswitch                         



        @endif
      
        @endif
        </div>                                                        
    </div>     
    
    <a class="btn btn-block btn-lg mt-3 text-white back" style="background-color:#123449;">Voltar</a>                              
    <!-- <a href="" class="btn btn-block btn-lg mt-3 text-white" style="background-color:#123449;">Voltar</a> -->
@stop

@section('js')
    <script src="{{asset('js/jquery.mask.min.js')}}"></script>   
    <script>
        $(function(){
            $("#cpf").mask('000.000.000-00');    
            $("#cpf_responsavel").mask('000.000.000-00');    
            //$("#cpf_responsavel").mask('(00) 0 0000-0000');  

            $(".back").on('click',function(){
                window.history.go(-1);
                return false;
            });  

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });    
            
            $("body").on('click','.cancelar',function(){
                $('#cancelarModal').modal('show')
            });

            $("body").on('click','.excluir_coletivo',function(){
                
                //if($(this).attr('excluir_coletivo')) {
                    Swal.fire({
                        title: 'Você tem certeza que deseja realizar essa operação?',
                        showDenyButton: true,
                        confirmButtonText: 'Sim',
                        denyButtonText: `Cancelar`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let id_cliente = $("#excluir_cliente").val();
                            $.ajax({
                                url:"{{route('financeiro.excluir.cliente')}}",
                                method:"POST",
                                data:"id_cliente="+id_cliente,
                                success:function(res) {
                                    if(res != "error") {
                                        window.location.href = "/admin/financeiro?ac=coletivo";
                                       
                                    } else {
                                        Swal.fire('Opss', 'Erro ao excluir o cliente', 'error')  
                                    }
                                }
                            });    
                        } else if (result.isDenied) {
                            //
                        }
                    })
                //}
           }); 
            


           $("body").on('click','.next',function(){
                //if($("#data_cliente").val() && $("#data_contrato").val()) {
                    let id_cliente = $("#data_cliente").val();
                    let id_contrato = $("#data_contrato").val();

                    let financeiro = $("#data_financeiro").val();

                    if(financeiro == 1 || financeiro == 2) {
                        Swal.fire({
                        title: 'Você tem certeza que deseja realizar essa operação?',
                        showDenyButton: true,
                        confirmButtonText: 'Sim',
                        denyButtonText: `Cancelar`,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url:"{{route('financeiro.mudarStatusColetivo')}}", 
                                    data:"id_cliente="+id_cliente+"&id_contrato="+id_contrato,
                                    method:"POST",
                                    success:function(res) {
                                        window.location.href = "/admin/financeiro?ac=coletivo";
                                    }
                                })
                            }
                        })
                    } else {
                        $('#dataBaixaModal').modal('show');   
                    }


                    // $.ajax({
                    //     url:"{{route('financeiro.mudarStatusColetivo')}}",
                    //     data:"id_cliente="+id_cliente+"&id_contrato="+id_contrato,
                    //     method:"POST",
                    //     success:function(res) {
                    //         if(res == "abrir_modal") {
                    //             $('#dataBaixaModal').modal('show');                      
                    //         } else {
                    //             // $(".coletivo_quantidade_em_analise").html(res.qtd_coletivo_em_analise);    
                    //             // $(".coletivo_quantidade_emissao_boleto").html(res.qtd_coletivo_emissao_boleto);
                    //             // $(".coletivo_quantidade_pagamento_adesao").html(res.qtd_coletivo_pg_adesao);
                    //             // $(".coletivo_quantidade_pagamento_vigencia").html(res.qtd_coletivo_pg_vigencia);
                    //             // $(".coletivo_quantidade_segunda_parcela").html(res.qtd_coletivo_02_parcela);
                    //             // $(".coletivo_quantidade_terceira_parcela").html(res.qtd_coletivo_03_parcela);
                    //             // $(".coletivo_quantidade_quarta_parcela").html(res.qtd_coletivo_04_parcela);
                    //             // $(".coletivo_quantidade_quinta_parcela").html(res.qtd_coletivo_05_parcela);
                    //             // $(".coletivo_quantidade_sexta_parcela").html(res.qtd_coletivo_06_parcela);
                    //             // $(".quantidade_coletivo_finalizado").html(res.qtd_coletivo_finalizado);
                    //             // table.ajax.reload();
                    //             // limparFormulario();
                    //         }
                    //     }
                    // });
                // } else {                     
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oops...',
                //         type: "error",
                //         width: '400px',
                //         html: "Tem que selecionar um item da tabela, para mudar de status"
                //     })
                // }
            });

            $('form[name="formulario_cancelados"]').on('submit',function(){
                $.ajax({
                    url:"{{route('financeiro.contrato.cancelados')}}",
                    method:"POST",
                    data:$(this).serialize(),
                    success:function(res) {
                        
                        if(res == "error") {

                        } else {
                            window.location.href = "/admin/financeiro?ac=coletivo";
                        //     if(res.plano == "coletivo") {
                        //         $(".coletivo_quantidade_em_analise").html(res.dados.qtd_coletivo_em_analise);    
                        //         $(".coletivo_quantidade_emissao_boleto").html(res.dados.qtd_coletivo_emissao_boleto);
                        //         $(".coletivo_quantidade_pagamento_adesao").html(res.dados.qtd_coletivo_pg_adesao);
                        //         $(".coletivo_quantidade_pagamento_vigencia").html(res.dados.qtd_coletivo_pg_vigencia);
                        //         $(".coletivo_quantidade_segunda_parcela").html(res.dados.qtd_coletivo_02_parcela);
                        //         $(".coletivo_quantidade_terceira_parcela").html(res.dados.qtd_coletivo_03_parcela);
                        //         $(".coletivo_quantidade_quarta_parcela").html(res.dados.qtd_coletivo_04_parcela);
                        //         $(".coletivo_quantidade_quinta_parcela").html(res.dados.qtd_coletivo_05_parcela);
                        //         $(".coletivo_quantidade_sexta_parcela").html(res.dados.qtd_coletivo_06_parcela);
                        //         $(".quantidade_coletivo_finalizado").html(res.dados.qtd_coletivo_finalizado);
                        //         $(".quantidade_coletivo_cancelados").html(res.dados.qtd_coletivo_cancelado);
                        //         $("#cancelarModal").modal('hide');
                        //         table.ajax.reload();
                        //         limparFormulario();
                        //     } else {
                        //         $(".individual_quantidade_em_analise").html(res.dados.qtd_individual_em_analise);    
                        //         $(".individual_quantidade_1_parcela").html(res.dados.qtd_individual_01_parcela);
                        //         $(".individual_quantidade_2_parcela").html(res.dados.qtd_individual_02_parcela);
                        //         $(".individual_quantidade_3_parcela").html(res.dados.qtd_individual_03_parcela);
                        //         $(".individual_quantidade_4_parcela").html(res.dados.qtd_individual_04_parcela);
                        //         $(".individual_quantidade_5_parcela").html(res.dados.qtd_individual_05_parcela);
                        //         $(".individual_quantidade_6_parcela").html(res.dados.qtd_individual_06_parcela);
                        //         $(".individual_quantidade_finalizado").html(res.dados.qtd_individual_finalizado);
                        //         $(".individual_quantidade_cancelado").html(res.dados.qtd_individual_cancelado);
                        //         $("#cancelarModal").modal('hide');
                        //         table_individual.ajax.reload();
                        //         limparFormularioIndividual();
                        //     }
                        }
                    }
                });
                return false;
           }); 







            $("form[name='data_da_baixa']").on('submit',function(){
                let id_cliente = $("#data_cliente").val();
                let id_contrato = $("#data_contrato").val();
                $.ajax({
                    url:"{{route('financeiro.baixa.data')}}",
                    method:"POST",
                    data: {
                        "id_cliente": id_cliente,
                        "id_contrato": id_contrato,
                        "data_baixa": $("#data_baixa").val(),
                        "comissao_id": $("#comissao_id_baixa").val()
                    },  
                    beforeSend:function() {
                        if($("#data_baixa").val() == "") {
                            $("#error_data_baixa").html('<p class="alert alert-danger">O campo data é campo obrigatório</p>');
                            return false;
                        } else {
                            $("#error_data_baixa").html('');
                        }
                    },
                    success:function(res) {
                        //console.log(res);
                        window.location.href = "/admin/financeiro?ac=coletivo";
                        // $(".coletivo_quantidade_em_analise").html(res.qtd_coletivo_em_analise);    
                        // $(".coletivo_quantidade_emissao_boleto").html(res.qtd_coletivo_emissao_boleto);
                        // $(".coletivo_quantidade_pagamento_adesao").html(res.qtd_coletivo_pg_adesao);
                        // $(".coletivo_quantidade_pagamento_vigencia").html(res.qtd_coletivo_pg_vigencia);
                        // $(".coletivo_quantidade_segunda_parcela").html(res.qtd_coletivo_02_parcela);
                        // $(".coletivo_quantidade_terceira_parcela").html(res.qtd_coletivo_03_parcela);
                        // $(".coletivo_quantidade_quarta_parcela").html(res.qtd_coletivo_04_parcela);
                        // $(".coletivo_quantidade_quinta_parcela").html(res.qtd_coletivo_05_parcela);
                        // $(".coletivo_quantidade_sexta_parcela").html(res.qtd_coletivo_06_parcela);
                        // $(".quantidade_coletivo_finalizado").html(res.qtd_coletivo_finalizado);
                        // table.ajax.reload();
                        // limparFormulario();
                        // $('#dataBaixaModal').modal('hide');
                        // $('#data_baixa').val('');
                    }
                })
                return false;
            });

            $('.editar_btn').on('click',function(){
                let params = $("#cliente").prop('readonly');
                if(!params) {
                    adicionarReadonly();
                } else {
                    removeReadonly();
                }             
            });

            function removeReadonly() {
                $("#cliente").removeAttr('readonly').addClass('editar_campo');
                $("#data_nascimento").removeAttr('readonly').addClass('editar_campo');
                $("#cpf").removeAttr('readonly').addClass('editar_campo')
                $("#nome_responsavel").removeAttr('readonly').addClass('editar_campo');
                $("#cpf_responsavel").removeAttr('readonly').addClass('editar_campo');
                $("#celular").removeAttr('readonly').addClass('editar_campo');
                $("#telefone").removeAttr('readonly').addClass('editar_campo');
                $("#email").removeAttr('readonly').addClass('editar_campo');
                $("#cep").removeAttr('readonly').addClass('editar_campo');
                $("#cidade").removeAttr('readonly').addClass('editar_campo');
                $("#uf").removeAttr('readonly').addClass('editar_campo');
                $("#bairro").removeAttr('readonly').addClass('editar_campo');
                $("#rua").removeAttr('readonly').addClass('editar_campo');
                $("#complemento").removeAttr('readonly').addClass('editar_campo');
            }

            
            function adicionarReadonly() {               
                $("#cliente").attr('readonly',true).removeClass('editar_campo');
                $("#data_nascimento").attr('readonly',true).removeClass('editar_campo');
                $("#cpf").attr('readonly',true).removeClass('editar_campo');
                $("#nome_responsavel").attr('readonly',true).removeClass('editar_campo');
                $("#cpf_responsavel").attr('readonly',true).removeClass('editar_campo');
                $("#celular").attr('readonly',true).removeClass('editar_campo');
                $("#telefone").attr('readonly',true).removeClass('editar_campo');
                $("#email").attr('readonly',true).removeClass('editar_campo')
                $("#cep").attr('readonly',true).removeClass('editar_campo')
                $("#cidade").attr('readonly',true).removeClass('editar_campo');
                $("#uf").attr('readonly',true).removeClass('editar_campo')
                $("#bairro").attr('readonly',true).removeClass('editar_campo')
                $("#rua").attr('readonly',true).removeClass('editar_campo')
                $("#complemento").attr('readonly',true).removeClass('editar_campo')
            }

            $("body").on('change','.editar_campo',function(){
                let alvo = $(this).attr('id');
                let valor = $("#"+alvo).val();
                let id_cliente = $("#cliente_id_alvo").val();
                
                $.ajax({
                    url:"{{route('financeiro.editar.campoIndividualmente')}}",
                    method:"POST",
                    data:"alvo="+alvo+"&valor="+valor+"&id_cliente="+id_cliente,
                    // success:function(res) {
                    //     table.ajax.reload();
                    // }
                });
            });


        });
    </script>
@stop

@section('css')
    <style>
        .container_full_cards {display: flex;}
        .card_info {background-color:#123449;flex-basis: 50%;border-radius: 5px;}
        .historico_pagamento {background-color:#123449;flex-basis: 19%;margin-left:1%;color:#FFF;border-radius: 5px;}
        .historico_pagamento h3 {color:#FFF;border-bottom: 1px solid #FFF;text-align: center;padding:5px 0;}
        .historico_corretor {background-color:#123449;flex-basis: 50%;margin-left:1%;color:#FFF;border-radius: 5px;}
    </style>
@stop