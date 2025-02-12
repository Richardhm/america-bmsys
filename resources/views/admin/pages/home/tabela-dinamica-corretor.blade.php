<h6 class="d-flex w-100 py-1 align-items-center" style="border-bottom:5px solid black;">
    <span class="d-flex w-50 justify-content-end">Comissoes
        @if($user != "")
            ($user)
        @endif
    </span>
    
</h6>
@if(in_array('Coletivo por Adesão', $planos)) 
    @php
        $administradora = $administradoras[0]->nome;
        $ii=0;
    @endphp
    @for($i=0;$i < count($administradoras);$i++)       
        @php $ii++; @endphp
        @if($administradoras[$i]->nome == $administradora)

            @if($ii==1)
                <div class="col-2 d-flex justify-content-between p-1 container_tabela container_comissao_corretor mb-2" style="border:3px solid #0dcaf0;border-radius:5px;background-color:#123449;color:#fff;margin-right:1%;box-shadow: 5px 0 8px rgb(65,105,225);min-height:315px;max-height:315px;">
                <table id="table_{{$administradoras[$i]->nome}}">                    
                    <thead>
                        <tr class="border-bottom border-white">
                            <th style="font-size:0.8em" class="text-center">{{str_replace('_', ' ',ucwords($administradoras[$i]->nome))}}</th>
                            <th class="text-right btn_remove_comissao_corretor" 
                                data-tipo="{{$tipo}}" 
                                data-plano="3" 
                                data-administradora="{{$administradoras[$i]->id}}"
                                data-cidade="2"
                                >
                                <i class="fas fa-times fa-xs"></i>
                            </th>

                            
                        
                    </th>
                            
                            
                    </th>






                        </tr>
                        
                        <tr>
                            <td style="width:10%;font-size:0.8em;text-align:center;">Parcela</td>
                            <td style="width:10%;font-size:0.8em;text-align:center;">Valor</td>
                        </tr>    
                    </thead>
                    <tbody>  
            @endif
                @for($xx=1;$xx <= 7;$xx++)
                <tr>
                    <td style="width:5%;" class="text-center">
                        @if($xx == 1)
                            Adesão
                        @endif
                        @if($xx == 2)
                            Vigência
                        @endif
                        @if($xx == 3)
                            2º Parcela
                        @endif
                        @if($xx == 4)
                            3º Parcela
                        @endif
                        @if($xx == 5)
                            4º Parcela
                        @endif
                        @if($xx == 6)
                            5º Parcela
                        @endif
                        @if($xx == 7)
                            6º Parcela
                        @endif
                    </td>
                    <td style="width:20%;">
                        <input type="text" 
                        name="{{$configuracoes->where('administradora_id',$administradoras[$i]->id)->where('plano_id',3)->where('parcela', $xx)->first()->id}}" 
                        style="width:100%;" 
                        data-tipo="{{$tipo}}"
                        class="mudar_valor_corretor_comissao" 
                        data-id="{{$configuracoes->where('administradora_id',$administradoras[$i]->id)->where('plano_id',3)->where('parcela', $xx)->first()->id}}"
                        value="{{$configuracoes->where('administradora_id',$administradoras[$i]->id)->where('plano_id',3)->where('parcela', $xx)->first()->valor}}" /></td>
                </tr>    
                @endfor
            @if($ii==1)
                    <tbody>
                </table>
                </div>
            @endif
        @else
        @php
            $administradora = $administradoras[$i]->nome;
            $ii=0;
            $i--;
        @endphp
        @endif
    @endfor   
@endif

@php
    $elementToRemove = 'Coletivo por Adesão';
    $dados = array_diff($planos, [$elementToRemove]);
    $dados = array_values($dados);
@endphp
@if(count($dados) >= 1)
@php
        $yy=0;
        $plano_inicial = $dados[0];
    @endphp
    @for($y = 0;$y < count($dados); $y++)
        @php
            $planoNome = $dados[$y];
            $planoId = $planoIdMap[$planoNome] ?? null;
        @endphp
        @php $yy++;@endphp       
        @if($plano_inicial == $dados[$y])
            @if($yy == 1)
            <div class="col-2 d-flex justify-content-between p-1 container_tabela container_comissao_corretor mt-1" style="border:3px solid #0dcaf0;border-radius:5px;background-color:#123449;color:#fff;margin-right:1%;box-shadow: 5px 0 8px rgb(65,105,225);">            
                <table id="tabela_{{$dados[$y]}}">
                    <thead>

                        <tr class="border-bottom border-white">
                            <th style="font-size:0.8em" class="text-center">{{str_replace('_', ' ',ucwords($dados[$y]))}}</th>
                            <th class="text-right btn_remove_comissao_corretor" 
                                data-tipo="{{$tipo}}" 
                                data-plano="{{$planoId}}" 
                                data-administradora="4"
                                data-cidade="2"
                                >
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
            @for($xy=1;$xy <= 6;$xy++)
                
                <tr>
                    <td style="width:5%;" class="text-center">{{$xy}}º Parcela</td>    
                    <td style="width:20%;">
                    <input type="text" 
                    data-tipo="{{$tipo}}" 
                    name="{{$configuracoes->where('plano_id',$planoId)->where('parcela', $xy)->first()->id}}" 
                    style="width:100%;" 
                    class="mudar_valor_corretor_comissao"
                    data-id="{{$configuracoes->where('plano_id',$planoId)->where('parcela', $xy)->first()->id}}" 
                    value="{{ $configuracoes->where('plano_id',$planoId)->where('parcela', $xy)->first()->valor }}"
                    /></td>
                    
                </tr>    
            @endfor
            @if($yy == 1)              
            <tbody>
                </table>
                </div>
            @endif    
        @else
            @php
                $plano_inicial = $dados[$y];
                $yy=0;
                $y--;
            @endphp
        @endif
    @endfor       
@endif