<table class="table table-sm">
    <thead>
    <tr>
        <th>Usuario</th>
        <th class="text-center">Individual</th>

        <th class="text-center">Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_table_individual = 0;

        $total_table = 0;
    @endphp
    @foreach($dados as $dt)
        <tr>
            <td>{{$dt->user_name}}</td>
            <td class="text-center">
                {{$dt->individual}}
                @php
                    $total_table_individual += $dt->individual;
                @endphp
            </td>

            <th class="text-center">
                {{$dt->total}}
                @php
                    $total_table += $dt->total;
                @endphp
            </th>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th class="text-center">{{$total_table_individual}}</th>

        <th class="text-center">{{$total_table}}</th>
    </tr>
    </tfoot>
</table>
