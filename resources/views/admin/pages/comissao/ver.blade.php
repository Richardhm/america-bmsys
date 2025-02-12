@if($plano_id == 3) 
<table id="tabela_coletivo" class="table table-sm">
    <thead>
        <tr>
            <th colspan="4" align="center" class="text-white text-center">{{$cliente}}</th>
        </tr>
        <tr>
            <th>Parcela</th>
            <th>Vencimento</th>
            <th>Data Baixa</th>
            <th>Dias</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($comissoes as $c)
            <tr>                
                <td class="text-white">
                    @if($c->parcela == 1)
                        Pag. Adesão
                    @elseif($c->parcela == 2)
                        Pag.Vigência
                    @elseif($c->parcela == 3)
                        Pag. 2º Parcela
                    @elseif($c->parcela == 4)
                        Pag. 3º Parcela
                    @elseif($c->parcela == 5)
                        Pag. 4º Parcela
                    @elseif($c->parcela == 6)
                        Pag. 5º Parcela
                    @elseif($c->parcela == 7)
                        Pag. 6º Parcela
                    @else
                        ---
                    @endif
                </td>
                <td class="text-white">{{$c->vencimento}}</td>
                <td class="text-white">{{empty($c->data_baixa) ? 'Não Pago' : $c->data_baixa}}</td>
                <td class="text-white">{{$c->dias_faltando}}</td>
                <td class="text-white">{{number_format($c->valor,2,",",".")}}</td>
            </tr>      
        @endforeach
    </tbody>
</table> 

@else

    <table id="tabela_individual" class="table table-sm">
        <thead>
            <tr>
                <th colspan="4" align="center" class="text-white text-center">{{$cliente}}</th>
            </tr>
            <tr>
                <th>Parcela</th>
                <th>Vencimento</th>
                <th>Data Baixa</th>
                <th>Dias</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comissoes as $c)
                <tr>                
                    <td class="text-white">
                        @if($c->parcela == 1)
                            Pag. 1º Parcela
                        @elseif($c->parcela == 2)
                            Pag. 2º Parcela
                        @elseif($c->parcela == 3)
                            Pag. 3º Parcela
                        @elseif($c->parcela == 4)
                            Pag. 4º Parcela
                        @elseif($c->parcela == 5)
                            Pag. 5º Parcela
                        @elseif($c->parcela == 6)
                            Pag. 6º Parcela
                       
                        @endif
                    </td>
                    <td class="text-white">{{$c->vencimento}}</td>
                    <td class="text-white">{{empty($c->data_baixa) ? 'Não Pago' : $c->data_baixa}}</td>
                    <td class="text-white">{{$c->dias_faltando}}</td>
                    <td class="text-white">{{number_format($c->valor,2,",",".")}}</td>
                </tr>      
            @endforeach
        </tbody>
    </table> 










@endif