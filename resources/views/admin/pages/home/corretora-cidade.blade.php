@php
    $plano_id = $dados[0]->plano_id;
    $administradora_id = $dados[0]->administradora_id;
    
    $ii=0;
@endphp
<h6 class="d-flex w-100 py-1 align-items-center" style="border-bottom:5px solid black;">
    <span class="d-flex w-50 justify-content-end">Comissão Corretora</span>
</h6>
@for($i=0;$i < count($dados); $i++)

    @if($plano_id == $dados[$i]->plano_id && $administradora_id == $dados[$i]->administradora_id)
        @php
            $ii++;
            $plano = $dados[$i]->plano_id == 3 ? 'coletivo' : $dados[$i]->plano;
        @endphp
        @if($ii == 1)
            <div class="col-2 d-flex justify-content-between container_tabela" style="border:3px solid #0dcaf0;border-radius:5px;background-color:#123449;color:#fff;margin-right:1%;box-shadow: 5px 0 8px rgb(65,105,225);margin-bottom:5px;">
            <table id="table_{{$dados[$i]->plano_id == 3 ? $dados[$i]->administradora : $dados[$i]->plano}}">
                <thead>
                    <tr class="border-bottom border-white">
                        <th style="font-size:0.8em" class="text-center">{{$dados[$i]->plano_id == 3 ? $dados[$i]->administradora : $dados[$i]->plano}}</th>
                        <th class="text-right btn_remove_comissao" data-tipo="{{$dados[$i]->plano_id == 3 ? 'coletivo' : 'hapvida'}}" data-plano="{{$dados[$i]->plano_id}}" data-administradora="{{$dados[$i]->administradora_id}}">
                            <i class="fas fa-times fa-xs"></i>
                        </th>
                    </tr>
                    <tr>
                        <td style="width:10%;font-size:0.8em;text-align:center;">Parcela</td>
                        <td style="width:10%;font-size:0.8em;text-align:center;">Valor</td>
                    </tr>    
                </thead>    
                <tbody>
        @endif
            <tr>
                <td style="width:5%;text-align:center;">
                    @if($plano == 'coletivo')
                        @if($ii == 1)
                            Adesão
                        @endif
                        @if($ii == 2)
                            Vigência
                        @endif
                        @if($ii == 3)
                            2º Parcela
                        @endif
                        @if($ii == 4)
                            3º Parcela
                        @endif
                        @if($ii == 5)
                            4º Parcela
                        @endif
                        @if($ii == 6)
                            5º Parcela
                        @endif
                        @if($ii == 7)
                            6º Parcela
                        @endif
                    @else
                        {{$ii}}º Parcela
                    @endif                  
                </td>
                <td style="width:20%;">
                    <input type="text" value="{{$dados[$i]->valor}}" class="mudar_valor_parcela" name="{{$dados[$i]->id}}" style="width:100%;">
                </td>
            </tr>    
    @else
    </tbody>
            </table>
</div>
        @php
            $ii=0;
            $plano_id = $dados[$i]->plano_id;
            $administradora_id = $dados[$i]->administradora_id;
            $i--;
        @endphp
    @endif

@endfor
</tbody>
</table>
</div>          