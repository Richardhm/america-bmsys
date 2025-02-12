<h6 class="d-flex w-100 py-1 align-items-center" style="border-bottom:5px solid black;">
    <span class="d-flex w-50 justify-content-end">Comissões
        @if($tipo == 1)
            CLT
        @endif

        @if($tipo == 2)
            Parceiros
        @endif


        @if($user != "")
            Personalizadas de ({{$user}})
        @endif


    </span>
    
</h6>
@php $ii=0;@endphp
@for($i=0;$i < count($dados); $i++)
    @php 
        $ii++; 
        $plano = $dados[$i]->planos->id == 3 ? 'coletivo' : Illuminate\Support\Str::snake($dados[$i]->planos->nome,''); 
        $titulo = $dados[$i]->planos->id == 3 ? $dados[$i]->administradoras->nome : $dados[$i]->planos->nome;   
    @endphp

    @if($dados[$i]->plano_id == $plano_inicial && $dados[$i]->administradora_id == $administradora_id)
        @if($ii == 1)
        <div class="col-2 d-flex justify-content-between p-1 container_comissao_corretor align-items-stretch mt-1" style="border:3px solid #0dcaf0;border-radius:5px;background-color:#123449;color:#fff;margin-right:1%;box-shadow: 5px 0 8px rgb(65,105,225);">
        <table>
            <thead>

                <tr class="border-bottom border-white">
                    <th style="font-size:0.8em;width:80%;">{{$titulo}}</th>
                    <th style="width:20%;" class="text-right btn_remove_comissao_corretor" 
                        data-plano="{{$dados[$i]->plano_id}}" 
                        data-administradora="{{$dados[$i]->administradora_id}}" 
                        data-cidade="2" 
                        data-tipo="{{$tipo}}"
                        
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
            <tr>
                <td style="width:5%;" class="text-center">
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
                <input type="text" class="mudar_valor_corretor_comissao" data-tipo="{{$tipo}}" data-id="{{$dados[$i]->id}}" name="parcela_{{$ii}}_{{$plano}}_{{$dados[$i]->administradoras->id}}_{{$dados[$i]->planos->id}}" style="width:100%;" value="{{$dados[$i]->valor}}" />
         
                </td>
            </tr>
    @else
    </tbody>
        </table>   
        </div>
        @php
            $plano_inicial      = $dados[$i]->plano_id;
            $administradora_id  = $dados[$i]->administradora_id;
            
            $ii=0;
            $i--;
       @endphp


    @endif

@endfor
</tbody>
        </table>   
        </div>
