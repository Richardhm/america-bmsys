<!-----FIM CARD SEARCH------->
    @php
        $inicial = $card_inicial;
        $atual = "";
        $ii=0;
    @endphp
    <div id="resultado">

    @for($i=0;$i < count($tabelas); $i++)

                @if($ii==0)
                    <div class="card">


                    <div class="d-flex" style="flex-wrap:wrap;">
                        <div class="w-25 my-auto bg-white rounded p-2" style="max-height:52px;">
                            <img src="{{asset($tabelas[$i]->administradora)}}" class="d-flex align-self-center p-1" alt="" width="100%" height="100%">
                        </div>

                        <div class="w-75 d-flex flex-column text-center">
                            <span>{{$tabelas[$i]->plano}}</span>
                            <span>{{$tabelas[$i]->odontos}}</span>
                        </div>

                        <div class="w-100 text-center border-top border-left border-right mt-1 p-2">
                            <span>{{$tabelas[$i]->cidade}}</span>
                        </div>
                    </div>





                    <table class="border-left border-right border-bottom table-responsive">
                            <thead class="border-top border-bottom">
                                <tr>
                                    <td class="text-nowrap border-right" rowspan="2" style="width:5%;text-align:center;vertical-align: middle;background-color:rgba(0,0,0,0.8);">Faixas</td>
                                    <td colspan="2" class="text-center border-right">Com Copar</td>
                                    <td colspan="2" class="text-center" style="background-color:rgba(0,0,0,0.8);">Copar. Parcial</td>
                                </tr>
                                <tr>

                                    <td class="text-nowrap text-center" style="width:5%;">ENFER</td>
                                    <td class="text-nowrap text-center border-right" style="width:5%;">APART</td>
                                    <td class="text-nowrap text-center" style="width:5%;background-color:rgba(0,0,0,0.8);color:orange;">ENFER</td>
                                    <td class="text-nowrap text-center" style="width:5%;background-color:rgba(0,0,0,0.8);color:orange;">APART</td>

                                </tr>
                            </thead>
                            <tbody>
                @endif
                @if($tabelas[$i]->card == $inicial)

                    <tr>
                        <td class="text-nowrap border-right" style="width:5%;background-color:rgba(0,0,0,0.8);">
                            <span style="margin-left:8px;">{{$tabelas[$i]->faixas == "59+" ? "Acima 59 anos" : $tabelas[$i]->faixas." anos"}}</span>
                        </td>
                        <td class="text-nowrap" style="width:5%;">
                            <span style="margin-left:10px;">{{number_format($tabelas[$i]->enfermaria_com_coparticipacao_com_odonto,2,",",".")}}</span>
                        </td>
                        <td class="text-nowrap border-right" style="width:5%;">
                            <span style="margin-left:10px;">{{number_format($tabelas[$i]->apartamento_com_coparticipacao_com_odonto,2,",",".")}}</span>
                        </td>
                        <td class="text-nowrap" style="width:5%;background-color:rgba(0,0,0,0.8);color:orange;">
                            <span style="margin-left:10px;">{{number_format($tabelas[$i]->enfermaria_sem_coparticipacao_com_odonto,2,",",".")}}</span>
                        </td>
                        <td class="text-nowrap" style="width:5%;background-color:rgba(0,0,0,0.8);color:orange;">
                            <span style="margin-left:10px;">{{number_format($tabelas[$i]->apartamento_sem_coparticipacao_com_odonto,2,",",".")}}</span>
                        </td>

                    </tr>
                    @php $ii++ @endphp
            @else
                    </tbody>
                </table>
                </div>

                @php
                    $ii=0;
                    $inicial = $tabelas[$i]->card;
                    $i--;
                @endphp

            @endif

        @endfor

</tbody>
                </table>
                </div>

         @if(count($ambulatorial) >= 1)
    @php
        $x=0;
        $xx=0;
        $total_ambulatorial_com_coparticipacao = 0;
        $total_ambulatorial_sem_coparticipacao = 0;
      @endphp
   @for($x=0;$x < count($ambulatorial); $x++)
            @if($xx==0)
              <div class="card">
                    <div class="d-flex" style="flex-wrap:wrap;">
                        <div class="w-25 my-auto bg-white rounded p-2" style="max-height:52px;">
                            <img src="{{asset($ambulatorial[$x]->administradora)}}" class="d-flex align-self-center p-1" alt="" width="100%" height="100%">
                        </div>
                        <div class="w-75 d-flex flex-column text-center">
                            <span>
                                @if($ambulatorial[$x]->cidade && $ambulatorial[$x]->plano == "Super Simples")
                                    Ambulatorial Super Simples
                                @else
                                    Ambulatorial
                                @endif
                            </span>
                            <span>{{$ambulatorial[$x]->odontos}}</span>
                        </div>

                        <div class="w-100 text-center border-top border-left border-right mt-1 p-2">
                            <span>{{$ambulatorial[$x]->cidade}}</span>
                        </div>
                    </div>
                    <table class="border-left border-right border-bottom">
                            <thead class="border-top border-bottom">
                                 <tr>
                                    <td style="background-color:rgba(0,0,0,0.8);text-align:center;font-size:0.875em;border-right:1px solid #FFF;border-bottom:1px solid #FFF;">Faixa Etária</td>
                                    <td style="text-align:center;font-size:0.875em;border-bottom:1px solid #FFF;border-right:1px solid #FFF;" class="">Com Copar</td>
                                    <td style="text-align:center;background-color:rgba(0,0,0,0.8);font-size:0.875em;border-bottom:1px solid #FFF;" class="">Sem Copar</td>
                                </tr>
                            </thead>
                            <tbody>





            @endif

            @if($ambulatorial[$x]->card == $card_incial_ambulatorial)

                <tr>
                          <td style="background-color:rgba(0,0,0,0.8);font-size:0.875em;border-right:1px solid #FFF;border-right:1px solid #FFF;">
                              <span style="margin-left:20px;">{{$ambulatorial[$x]->faixas == "59+" ? "Acima ".$ambulatorial[$x]->faixas." Anos" : $ambulatorial[$x]->faixas." Anos"}}</span>
                          </td>
                          <td style="text-align:center;font-size:0.875em;border-right:1px solid #FFF;" class="">
                            <span style="margin-right:6px;">{{number_format($ambulatorial[$x]->ambulatorial_com_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_com_coparticipacao += $ambulatorial[$x]->ambulatorial_com_coparticipacao_total;
                            @endphp
                          </td>

                          <td style="text-align:center;background-color:rgba(0,0,0,0.8);color:orange;font-size:0.875em;" class="">
                          <span style="margin-right:6px;">{{number_format($ambulatorial[$x]->ambulatorial_sem_coparticipacao_total,2,",",".")}}</span>
                            @php
                              $total_ambulatorial_sem_coparticipacao += $ambulatorial[$x]->ambulatorial_sem_coparticipacao_total
                            @endphp
                          </td>
                      </tr>

                @php $xx++ @endphp
            @else
               </tbody>
                </table>
                </div>

                @php
                    $xx=0;
                    $card_incial_ambulatorial = $ambulatorial[$x]->card;
                    $x--;
                @endphp

            @endif

        @endfor




  </tbody>
                </table>
                </div>











@endif








    </div>

