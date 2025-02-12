@if(count($dados) >= 1)
@php 
    $ii=0;  
    $total = 0;       
    $contagem = 1;
@endphp
    <div class="d-flex justify-content-between" style="flex-wrap: wrap;">
        <h4 class="my-2 w-100 text-white">Nossos Planos: </h4>
        @for($i=0;$i < count($dados); $i++) 
            @php 
                $contagem = $i + 1;
                
            @endphp
            @if($dados[$i]->card == $card_inicial)
                @if($ii==0)
                    <input type="hidden" name="administradora" id="administradora" value="{{$dados[$i]->administradora}}">
                    <div class="d-flex flex-column rounded valores-acomodacao mb-3 py-2 border border-dark" style="width:30%;color:white;background-color:#C5D4EB;color:black;">
                        

                            <div class="d-flex">

                                <h4 class="text-center w-50 d-flex ml-2" style="background-color:#fff;border:2px solid black;border-radius:5px;">
                                    <img src="{{asset($dados[$i]->logo)}}" class="d-flex align-self-center p-1" alt="" width="100%" height="100%">
                                </h4>
                                <div class="d-flex w-50 flex-column align-self-center align-items-end align-content-center flex-wrap">    
                                    <div class="d-flex flex-column">
                                        <p class="text-center" style="margin:0;padding:0;font-size:1.2em;">{{$dados[$i]->planos}}</p> 
                                        <p class="text-center tipo" style="margin:0;padding:0;font-size:1.2em;">{{$dados[$i]->acomodacao}}</p>   
                                    </div>            
                                </div>


                            </div>
                            
                        
                            <div class="d-flex border-bottom border-top border-dark">
                                <div class="col-6 border-right border-dark">
                                    <p class="text-center h-100 my-auto py-2">{{$dados[$i]->coparticipacao}}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-center h-100 my-auto py-2">{{$dados[$i]->odonto}}</p>
                                </div>
                            </div>

                            <div class="d-flex my-2" style="padding:0px;">
                                <div class="ml-2">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;">Data Vigencia:</p>
                                        <input type="text" name="vigente" id="vigente_{{strtolower($dados[$i]->acomodacao)}}"  value="" class="form-control form-control-sm vigente">
                                        
                                    </div>
                                </div>    
                                <div class="mx-1">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;">Data Boleto:</p>
                                        <input type="date" name="boleto" id="boleto_{{strtolower($dados[$i]->acomodacao)}}" value="" placeholder="Data Boleto" class="form-control form-control-sm boleto">
                                    </div>
                                    
                                </div>
                                <div class="mr-2">
                                    <div class="form-group">
                                        <p style="margin:0;padding:0;">Valor Adesão:</p>
                                        <input type="text" name="adesao" id="adesao_{{strtolower($dados[$i]->acomodacao)}}" placeholder="R$" class="form-control form-control-sm valor_adesao">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mx-auto mb-2" style="width:80%;border-radius:10px;">
                                <table class="table table-sm table-borderless table-dark" style="border-radius:10px;">
                                    <thead>
                                        <tr>
                                            <th>Faixas</th>
                                            <th>Vidas</th>
                                            <th>Valor</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                @endif
                @php 
                    $ii++;
                    $total += $dados[$i]->valor * $dados[$i]->quantidade;
                                             
                @endphp                     
                                        <tr>
                                            <td>{{$dados[$i]->faixa}}</td>
                                            <td>{{$dados[$i]->quantidade}}</td>
                                            <td>{{number_format($dados[$i]->valor,2,",",".")}}</td>
                                            <td class="text-right">{{number_format($dados[$i]->valor * $dados[$i]->quantidade,2,",",".")}}</td>
                                        </tr>    
            
            @else
                                    
                                    </tbody>                                            
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-center border-top border-white valor_plano">R$ {{number_format($total,2,",",".")}}</td>
                                        </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>
                    
                @php $card_inicial = $dados[$i]->card; $i--; $ii=0;$total=0;@endphp
            @endif
            
            
        @endfor
            
       
       
    </div>

    @if($contagem == $quantidade) 
    </tbody>                                            
                                    <tfoot>
                                        <tr>
                                        <td colspan="4" class="text-center border-top border-white valor_plano">R$ {{number_format($total,2,",",".")}}</td>
                                        </tr>
                                    </tfoot>
                            </table>
                        </div>
                    </div>
        @endif
  
<input type="hidden" name="valor" id="valor" value="">
<input type="hidden" name="data_vigencia" id="data_vigencia" value="">
<input type="hidden" name="data_boleto" id="data_boleto" value="">
<input type="hidden" name="valor_adesao" id="valor_adesao" value="">
<input type="hidden" name="acomodacao" id="acomodacao" value="">

<div id="btn_submit" style="clear:both;width:100%;"></div> 
@else
    <p class="alert alert-danger">Sem Resultado para essa pesquisa =/</p>
@endif
<script>
    $(function(){
        // $(".valores-acomodacao").css("background-color","red");
        $('.valor_adesao').mask("#.##0,00", {reverse: true});




    });
</script> 