@if(count($resultado) >= 1)
    <table class="table table-sm">
        <thead>
            <tr style="background-color:#FFF;color:#000;">
                <td>Vencimento</td>
                <td>Valor</td>
                <td>Pagamento</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            @foreach($resultado as $r)
            <tr>
                <td>{{$r->dtVencimento}}</td>
                <td>{{number_format($r->vlObrigacao,2,",",".")}}</td>
                <td>{{$r->dtPagamento != '' ? $r->dtPagamento : '----'}}</td>
                <td>{{$r->dsStatus}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="alert alert-danger text-center">Sem resultado para essa consulta</p>
@endif