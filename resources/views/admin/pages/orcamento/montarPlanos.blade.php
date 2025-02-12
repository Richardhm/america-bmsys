@if(count($planos) >= 1) 
@php 
        $ii=0;         
        $total_apartamento_coparticipacao = 0;
        $total_enfermaria_coparticipacao = 0;
        $total_ambulatorial_coparticipacao =  0;
        $total_apartamento_sem_coparticipacao = 0;
        $total_enfermaria_sem_coparticipacao = 0;
        $total_ambulatorial_sem_coparticipacao = 0;
        $total_apartamento_odonto_final = 0;
        $total_enfermaria_odonto_final = 0;
        $total_ambulatorial_odonto_final =  0;
        $total_apartamento_sem_odonto_final = 0;
        $total_enfermaria_sem_odonto_final = 0;
        $total_ambulatorial_sem_odonto_final = 0;
    
    
      @endphp

<div class="d-block">
   
    <div class="d-flex" style="flex-wrap:wrap;">
        @for($i=0;$i < count($planos); $i++) 
          @if($planos[$i]->card == $card_inicial)
            @if($ii==0)
              <div class="card card_plano">
                <div class="card-body card_card">
                    <input type="hidden" name="administradora_id" id="administradora_id" value="{{$planos[$i]->admin_id}}"> 
                    <input type="hidden" name="plano_id" id="plano_id" value="{{$planos[$i]->plano_id}}"> 
                    <div class="d-flex mb-2">
                        <div style="flex-basis:30%;background-color:#fff;padding:10px;border-radius:10px;max-height:52px;display:flex;align-items: center;">
                          <img class="mx-auto" src="{{asset($planos[$i]->admin_logo)}}"  alt="{{$planos[$i]->admin_nome}}" width="100%;" height="100%">
                        </div>
                       <div class="d-flex justify-content-center align-items-center" style="flex-basis:70%;">
                          <div class="d-flex flex-column text-center">
                            <span style="font-size:1.1em;" style="background-color:rgba(0,0,0,0.9)">{{$planos[$i]->plano}}</span>
                            <span style="font-size:1.1em;" id="plano_com_sem_odonto">{{$planos[$i]->titulos}}</span>
                          </div>
                        </div>
                    </div><!-- FIM d-flex mb-2 -->
                   <table class="">
                        <thead>
                            <tr>
                                <td rowspan="2" style="vertical-align:middle;background-color:rgba(0,0,0,0.8);text-align:center;font-size:0.875em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;">Faixa Etária</td>
                                <td colspan="2" style="text-align:center;font-size:0.875em;border-bottom:1px solid #FFF;border-right:1px solid #FFF;" class="">Com Coparticipação</td>
                                <td colspan="2" style="text-align:center;background-color:rgba(0,0,0,0.8);font-size:0.875em;border-bottom:1px solid #FFF;" class="">Coparticipação Parcial</td>
                            </tr>
                            <tr>
                                <td style="text-align:center;font-size:0.875em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;" class="">APART</td>
                                <td style="text-align:center;font-size:0.875em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;" class="">ENFER</td>
                                <td style="text-align:center;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;border-bottom:1px solid #FFF;border-right:1px solid #FFF;" class="">APART</td>
                                <td style="text-align:center;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;border-bottom:1px solid #FFF;" class="">ENFER</td>
                            </tr>
                        </thead>
                    <tbody>
            @endif

            @if($planos[$i]->quantidade == 1)
                     <tr> 
                          <td style="background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;">
                              <span style="margin-left:20px;">{{$planos[$i]->nome == "59+" ? "Acima ".$planos[$i]->nome." Anos" : $planos[$i]->nome." Anos"}}</span>
                          </td>

                          <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($planos[$i]->apartamento_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_apartamento_coparticipacao += $planos[$i]->apartamento_com_coparticipacao_total;
                            @endphp
                          </td>

                          <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($planos[$i]->enfermaria_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_enfermaria_coparticipacao += $planos[$i]->enfermaria_com_coparticipacao_total;
                            @endphp
                          </td>
                         
                          <td style="text-align:right;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;border-right:1px solid white" class="">
                            <span style="margin-right:6px;">{{number_format($planos[$i]->apartamento_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_apartamento_sem_coparticipacao += $planos[$i]->apartamento_sem_coparticipacao_total
                            @endphp
                          </td>

                          <td style="text-align:right;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;" class="">
                            <span style="margin-right:6px;">{{number_format($planos[$i]->enfermaria_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_enfermaria_sem_coparticipacao += $planos[$i]->enfermaria_sem_coparticipacao_total
                            @endphp
                          </td>   

                      </tr>   
                  @else 

                    @for($x=0;$x<$planos[$i]->quantidade;$x++)

                        <tr> 
                          <td style="background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;">
                              <span style="margin-left:20px;">{{$planos[$i]->nome == "59+" ? "Acima ".$planos[$i]->nome." Anos" : $planos[$i]->nome." Anos"}}</span>
                          </td>
                          <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($planos[$i]->apartamento_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_apartamento_coparticipacao += $planos[$i]->apartamento_com_coparticipacao_total;
                            @endphp
                          </td>
                          <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;" class="">
                          <span style="margin-right:6px;">{{number_format($planos[$i]->enfermaria_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_enfermaria_coparticipacao += $planos[$i]->enfermaria_com_coparticipacao_total;
                            @endphp
                          
                          </td>
                          <td style="text-align:right;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;border-right:1px solid white" class="">
                          <span style="margin-right:6px;">{{number_format($planos[$i]->apartamento_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_apartamento_sem_coparticipacao += $planos[$i]->apartamento_sem_coparticipacao_total
                            @endphp
                          </td>
                          <td style="text-align:right;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;" class="">
                          <span style="margin-right:6px;">{{number_format($planos[$i]->enfermaria_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_enfermaria_sem_coparticipacao += $planos[$i]->enfermaria_sem_coparticipacao_total
                            @endphp
                          </td>
                      </tr>   
                    @endfor
                  @endif
                    @php $ii++; @endphp
                @else
                  @php $card_inicial = $planos[$i]->card; $ii=0; $i--;@endphp
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="background-color:rgba(0,0,0,0.8);border-right:1px solid #FFF;border-top:1px solid white;" class="">
                        <span style="margin-left:20px;">Total</span>
                      </td>
                      <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;border-top:1px solid white;" class="">
                      <span style="margin-right:6px;">{{isset($total_apartamento_coparticipacao) ? number_format($total_apartamento_coparticipacao,2,",",".") : 0}}</span>
                      </td>
                      <td style="text-align:right;font-size:0.875em;border-right:1px solid #FFF;border-top:1px solid white;" class="">
                      <span style="margin-right:6px;">{{isset($total_enfermaria_coparticipacao) ? number_format($total_enfermaria_coparticipacao,2,",",".") : 0}}</span>
                      </td>
                      
                      <td style="text-align:right;color:orange;background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">
                      <span style="margin-right:6px;">{{isset($total_apartamento_sem_coparticipacao) ? number_format($total_apartamento_sem_coparticipacao,2,",",".") : 0}}</span>
                      </td>
                      <td style="text-align:right;color:orange;background-color:rgba(0,0,0,0.8);font-size:0.875em;border-top:1px solid white;" class="">
                      <span style="margin-right:6px;">{{isset($total_enfermaria_sem_coparticipacao) ? number_format($total_enfermaria_sem_coparticipacao,2,",",".") : 0}}</span>
                      </td>
                      
                    </tr>
                  </tfoot>
                 
                  </table>
                  @php 
                    $total_apartamento_coparticipacao = 0;
                    $total_enfermaria_coparticipacao = 0;
                    $total_ambulatorial_coparticipacao =  0;
                    $total_apartamento_sem_coparticipacao = 0;
                    $total_enfermaria_sem_coparticipacao = 0;
                    $total_ambulatorial_sem_coparticipacao = 0;
                  @endphp
                </div>    
            </div>   
          @endif
                 
        @endfor
      </tbody>  
      <tfoot>
        <tr>
          <td style="font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;">
              <span style="margin-left:20px;">Total</span>
          </td>
          <td style="text-align:right;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">{{isset($total_apartamento_coparticipacao) ? number_format($total_apartamento_coparticipacao,2,",",".") : 0}}</td>
          <td style="text-align:right;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">{{isset($total_enfermaria_coparticipacao) ? number_format($total_enfermaria_coparticipacao,2,",",".") : 0}}</td>
          <!-- <td style="text-align:center;" class="">{{isset($total_ambulatorial_coparticipacao) ? number_format($total_ambulatorial_coparticipacao,2,",",".") : 0}}</td> -->
          <td style="text-align:right;font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;" class="">{{isset($total_apartamento_sem_coparticipacao) ? number_format($total_apartamento_sem_coparticipacao,2,",",".") : 0}}</td>
          <td style="text-align:right;font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;" class="">{{isset($total_enfermaria_sem_coparticipacao) ? number_format($total_enfermaria_sem_coparticipacao,2,",",".") : 0}}</td>
          <!-- <td style="text-align:center;" class="">{{isset($total_ambulatorial_sem_coparticipacao) ? number_format($total_ambulatorial_sem_coparticipacao,2,",",".") : 0}}</td> -->
          
          
        </tr>

       
      </tfoot>  
      </table> 
  </div>
</div>

   @if(count($ambulatorial) >= 1)
    @php 
        
        
        $xx=0;      
        
        
    
        $total_ambulatorial_com_coparticipacao = 0;
    $total_ambulatorial_sem_coparticipacao = 0;
      @endphp


   @for($a=0;$a < count($ambulatorial); $a++) 
          @if($ambulatorial[$a]->card == $card_incial_ambulatorial)
            @if($xx==0)
              <div class="card card_plano_ambulatorial">
                <div class="card-body card_card">
                <input type="hidden" name="administradora_id" id="administradora_id" value="{{$ambulatorial[$a]->admin_id}}"> 
                <input type="hidden" name="plano_id" id="plano_id" value="{{$ambulatorial[$a]->plano_id}}"> 
                  <div class="d-flex mb-2">
                    <div style="flex-basis:30%;background-color:#fff;padding:10px;border-radius:10px;max-height:52px;display:flex;align-items: center;margin-left: 17px;">
                      <img class="mx-auto" src="{{asset($ambulatorial[$a]->admin_logo)}}"  alt="{{$ambulatorial[$a]->admin_nome}}" width="100%;" height="100%">
                    </div>
                   <div class="d-flex justify-content-center align-items-center" style="flex-basis:70%;">
                      <div class="d-flex flex-column text-center">
                        <span style="font-size:1.1em;" style="background-color:rgba(0,0,0,0.9)">{{$ambulatorial[$a]->plano}}</span>
                        <span>Ambulatorial/<span id="plano_com_sem_odonto">{{$ambulatorial[$a]->titulos}}</span></span>
                      </div>
                    </div>
                 </div>
                 <table class="">                 
                      <tr>
                          <td style="background-color:rgba(0,0,0,0.8);text-align:center;font-size:0.875em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;">Faixa Etária</td>
                          <td style="text-align:center;font-size:0.875em;border-bottom:1px solid #FFF;border-right:1px solid #FFF;" class="">Coparticipação Total</td>
                          <td style="text-align:center;background-color:rgba(0,0,0,0.8);font-size:0.875em;border-bottom:1px solid #FFF;" class="">{{$ambulatorial[$a]->admin_id == 4 ? 'Coparticipação Parcial' : "Sem Copar"}}</td>
                      </tr>
                      
                  
                  <tbody>
            @endif

                  @if($ambulatorial[$a]->quantidade == 1)

                     <tr> 
                          <td style="background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;">
                              <span style="margin-left:20px;">{{$ambulatorial[$a]->nome == "59+" ? "Acima ".$ambulatorial[$a]->nome." Anos" : $ambulatorial[$a]->nome." Anos"}}</span>
                          </td>
                          <td style="text-align:center;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($ambulatorial[$a]->ambulatorial_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_com_coparticipacao += $ambulatorial[$a]->ambulatorial_com_coparticipacao_total;
                            @endphp
                          </td>
                          
                          <td style="text-align:center;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;" class="">
                          <span style="margin-right:6px;">{{number_format($ambulatorial[$a]->ambulatorial_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_sem_coparticipacao += $ambulatorial[$a]->ambulatorial_sem_coparticipacao_total
                            @endphp
                          </td>  
                      </tr>   
                  @else 
                    @for($aa=0;$aa<$ambulatorial[$a]->quantidade;$aa++)

                        <tr> 
                          <td style="background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;">
                              <span style="margin-left:20px;">{{$ambulatorial[$a]->nome == "59+" ? "Acima ".$ambulatorial[$a]->nome." Anos" : $ambulatorial[$a]->nome." Anos"}}</span>
                          </td>
                          <td style="text-align:center;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($ambulatorial[$a]->ambulatorial_com_coparticipacao,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_com_coparticipacao += $ambulatorial[$a]->ambulatorial_com_coparticipacao;
                            @endphp
                          </td>
                          <td style="text-align:center;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;border-right:1px solid white" class="">
                          <span style="margin-right:6px;">{{number_format($ambulatorial[$a]->ambulatorial_sem_coparticipacao,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_sem_coparticipacao += $ambulatorial[$a]->ambulatorial_sem_coparticipacao
                            @endphp
                          </td>
                         
                      </tr>   




                    @endfor  




                  @endif


                 
                    @php $xx++; @endphp
                @else
                  @php $card_incial_ambulatorial = $ambulatorial[$a]->card; $xx=0; $a--;@endphp
                  </tbody>
                  <tfoot>
                    <tr>
                      <td style="font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;">
                          <span style="margin-left:20px;">Total</span>
                      </td>
                      <td style="text-align:center;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">{{isset($total_ambulatorial_com_coparticipacao) ? number_format($total_ambulatorial_com_coparticipacao,2,",",".") : 0}}</td>
                      <td style="text-align:center;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;background-color:rgba(0,0,0,0.8);" class="">{{isset($total_ambulatorial_sem_coparticipacao) ? number_format($total_ambulatorial_sem_coparticipacao,2,",",".") : 0}}</td>
                      
                  
                    </tr>
                  </tfoot>
                 
                  </table>

                  @php 
                    $total_ambulatorial_com_coparticipacao = 0;
                    $total_ambulatorial_sem_coparticipacao = 0;
                    
                  @endphp
                  
                </div>    
              
          @endif
          </div>        
        @endfor
        
      <tfoot>
        <tr>
          <td style="font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;">
              <span style="margin-left:20px;">Total</span>
          </td>
          <td style="text-align:center;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">{{isset($total_ambulatorial_com_coparticipacao) ? number_format($total_ambulatorial_com_coparticipacao,2,",",".") : 0}}</td>
          <td style="text-align:center;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;background-color:rgba(0,0,0,0.8);" class="">{{isset($total_ambulatorial_sem_coparticipacao) ? number_format($total_ambulatorial_sem_coparticipacao,2,",",".") : 0}}</td>     
        </tr>
      </tfoot>   
<div>







  </div>

  
    
     

 







@endif
@else 
  <h3 class="text-center alert alert-info">Sem Resultados para essa pesquisa =/</h3>
@endif 


</div>