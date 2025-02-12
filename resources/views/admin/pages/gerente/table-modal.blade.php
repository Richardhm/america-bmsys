<table class="table table-sm">
    <thead>
    <tr>
        <th>Nome</th>
        <th>Comissão</th>
        <th>Premiação</th>
        <th>Salario</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_valor = 0;
    @endphp
    @foreach($dados as $d)
        @php
            $total_valor += $d->total;
        @endphp
        <tr>
            <td>{{$d->user}}</td>
            <td>{{number_format($d->valor_comissao,2,",",".")}}</td>
            <td>{{number_format($d->valor_salario,2,",",".")}}</td>
            <td>{{number_format($d->valor_premiacao,2,",",".")}}</td>
            <td>{{number_format($d->total,2,",",".")}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th colspan="4" class="text-right">Total:</th>
        <th>{{number_format($total_valor,2,",",".")}}</th>
    </tr>
    </tfoot>
</table>
