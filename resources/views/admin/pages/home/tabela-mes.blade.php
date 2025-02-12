<thead>
<tr>
    <th colspan="4" class="bg-warning">
        <select name="ranking_mes" id="ranking_mes" class="font-weight-bold" style="border:none;background-color: #ffc107;padding:0;width:80%;">
            <option value="">Mês</option>
            @foreach($mesesSelect as $mm)
                <option value="{{$mm->month_date}}"
                        style="background-color:#ffc107;"
                        {{$data_atual == $mm->month_date ? 'selected' : ''}}
                >{{$mm->month_name_and_year}}</option>
            @endforeach
        </select>
    </th>

</tr>

</thead>
<tbody>
@php
    $i=0;
@endphp
@foreach($ranking as $r)
    @php
        $parts = explode(' ', $r->usuario);
        $nome_abreviado = $parts[0] . ' ' . ($parts[1] ?? '');
    @endphp
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$nome_abreviado}}</td>
        <td>{{$r->quantidade}}</td>
        <td>{{number_format($r->valor,2,",",".")}}</td>
    </tr>
@endforeach
</tbody>

<scrip>

</scrip>
