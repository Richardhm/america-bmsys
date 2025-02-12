<div class="d-block">
    <div class="d-flex" style="flex-wrap:wrap;">
        <div class="card card_plano">
            <div class="card-body card_card">
                <input type="hidden" name="administradora_id" id="administradora_id" value="2">
                <input type="hidden" name="plano_id" id="plano_id" value="3">
                <div class="d-flex mb-2">
                    <div style="flex-basis:30%;background-color:#fff;padding:10px;border-radius:10px;max-height:52px;display:flex;align-items: center;">
                        <img class="mx-auto" src="{{asset('administradoras/6ZjO0muhLB9iYyqnZZkQ6QFHafcfmVa7nRflJ0Du.png')}}"  alt="Alter" width="100%;" height="100%">
                    </div>
                    <div class="d-flex justify-content-center align-items-center" style="flex-basis:70%;">
                        <div class="d-flex flex-column text-center">
                            <span style="font-size:1.1em;" style="background-color:rgba(0,0,0,0.9)">Tabela Adesão</span>
                            <span style="font-size:1.1em;" id="plano_com_sem_odonto">Com Odonto</span>
                        </div>
                    </div>
                </div><!-- FIM d-flex mb-2 -->
                <table>
                    <thead>
                    <tr>
                        <th class="text-center" rowspan="3" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;background-color:rgba(0,0,0,0.8);">Faixas</th>
                        <th style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;text-align:center;">Integrado</th>
                        <th style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;" colspan="2" class="text-center">Coletivo Total</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;">Com Coparticipação</th>
                        <th class="text-center" colspan="2" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;">Com Coparticipação</th>
                    </tr>
                    <tr>
                        <th class="text-center" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;">ENFER</th>
                        <th class="text-center" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;">APART</th>
                        <th class="text-center" style="border-right:1px solid #FFF;border-bottom:1px solid #FFF;">ENFER</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_enfermaria_com_coparticipacao_coletivo_integrado = 0;
                            $total_apartamento_com_coparticipacao_coletivo_total = 0;
                            $total_enfermaria_com_coparticipacao_coletivo_total =  0;
                        @endphp

                    @foreach($dados as $d)


                        <tr>
                            <td class="text-center" style="border-right:1px solid #FFF;background-color:rgba(0,0,0,0.8);">{{$d->nome}} anos</td>
                            <td class="text-right" style="border-right:1px solid #FFF;">
                                <span class="mr-2">{{number_format($d->resultado_enfermaria_com_coparticipacao_coletivo_integrado,2,",",".")}}</span>
                                @php
                                    $total_enfermaria_com_coparticipacao_coletivo_integrado += $d->resultado_enfermaria_com_coparticipacao_coletivo_integrado;
                                @endphp
                            </td>
                            <td class="text-right" style="border-right:1px solid #FFF;">
                                <span class="mr-2">{{number_format($d->resultado_apartamento_com_coparticipacao_coletivo_total,2,",",".")}}</span>
                                @php
                                    $total_apartamento_com_coparticipacao_coletivo_total += $d->resultado_apartamento_com_coparticipacao_coletivo_total;
                                @endphp
                            </td>
                            <td class="text-right" style="border-right:1px solid #FFF;">
                                <span class="mr-2">{{number_format($d->resultado_enfermaria_com_coparticipacao_coletivo_total,2,",",".")}}</span>
                                @php
                                    $total_enfermaria_com_coparticipacao_coletivo_total += $d->resultado_enfermaria_com_coparticipacao_coletivo_total;
                                @endphp
                            </td>
                        </tr>
                    @endforeach

                    </tbody>

                    <tfoot>
                        <tr>
                            <td style="font-size:0.875em;border-right:1px solid white;background-color:rgba(0,0,0,0.8);border-top:1px solid white;">
                                <span style="margin-left:30px;">Total</span>
                            </td>
                            <td style="text-align:right;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">
                                <span class="mr-2">{{number_format($total_enfermaria_com_coparticipacao_coletivo_integrado,2,",",".") ?? 0}}</span>
                            </td>
                            <td style="text-align:right;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">
                                <span class="mr-2">{{number_format($total_apartamento_com_coparticipacao_coletivo_total,2,",",".") ?? 0}}</span>
                            </td>
                            <td style="text-align:right;font-size:0.875em;border-right:1px solid white;border-top:1px solid white;" class="">
                                <span class="mr-2">{{number_format($total_enfermaria_com_coparticipacao_coletivo_total,2,",",".") ?? 0}}</span>
                            </td>

                        </tr>


                    </tfoot>















                </table>









            </div>
        </div>
    </div>
</div>





