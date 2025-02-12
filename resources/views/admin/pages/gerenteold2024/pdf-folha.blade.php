<html>
    <head>
        <title></title>
        <style>
            * {
                margin:0;
                padding:0;
                font-size:1em;
            }

            td {
                font-size: 0.7em;
            }
           
            
        </style>
    </head>
    <body>

       <div style="width:95%;margin:0 auto;padding:5px 0;">
            <p style="font-size:0.75em;">ACCERT CORRETORA</p>
       </div> 
       
       <div style="border-top:1px solid black;display:block;width:95%;left:20px;position:absolute;height:70px;padding:10px 0;">
            <div style="width:90%;position:relative;left:0;float:left;">
                <p>COMPOSIÇÃO SALARIAL</p>
                <p>Vendedor: {{$user}}</p>
                <p>Referência: {{$meses}} / 2023</p>
            </div>
            
            <div style="width:10%;position:relative;right:0;top:0;float:right;">
                <img src="{{$logo}}" alt="Logo" id="Logo" style="width:100%;height:100%;" />
            </div>
            
       </div>


       <div style="clear: both;"></div>     
       
       
       <div style="display:block;height:80px;width:95%;left:20px;position:relative;border-top:1px solid black;padding:2px;">
            <div>
                <span style="width:89%;left:0;float:left;">1 Salário Mês</span>
                <span style="width:11%;right:0;top:0;float:right;text-align:right;">
                    <div style="width:75%;float:right;text-align:right;">{{$salario}}</div>
                </span>
            </div>
            <div style="clear: both;"></div>     
            <div>
                <span style="width:89%;left:0;float:left;">1 Comissão</span>
                <div style="width:11%;right:0;top:0;float:right;">
                    
                    <div style="width:75%;float:right;text-align:right;">{{$comissao}}</div>
                </div>
                
            </div>
            <div style="clear: both;"></div>     
            <div>
                <span style="width:89%;left:0;float:left;">1 Premiação</span>
                <div style="width:11%;right:0;top:0;float:right;">
                    <div style="width:75%;float:right;text-align:right;">{{$premiacao}}</div>
                </div> 
            </div>
            <div style="clear: both;"></div>
            <div>
                <span style="width:89%;left:0;float:left;">Total Geral</span>
                <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{$total}}</span> 
            </div>
        </div>        

        <div style="clear: both;"></div>

        <div style="border-top:1px solid black;width:95%;margin:0 auto;border-bottom:1px solid black;padding:5px 0;">
            <p style="font-size: 0.875em;">ACOMPANHAMENTO DE VENDAS</p>
            <p style="font-size: 0.875em;">Período de {{$primeiro_dia}} até {{$ultimo_dia}}</p>
            <p style="font-size: 0.875em;">Status: Somente Pago</p>
        </div>    

        <div style="clear: both;"></div>
            
        @php

            $total_plano_individual = 0;
            $total_comissao_individual = 0;

            $total_plano_coletivo = 0;
            $total_comissao_coletivo = 0;
            
            $total_plano_empresarial = 0;
            $total_comissao_empresarial = 0;

        @endphp


        
        @if(count($individual) >= 1)
            
        



        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Plano Individual</div>
            
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>Contrato</td>
                    <td>Data</td>
                    <td>Cliente</td>
                    <td>Parcela</td>
                    <td align="center">Valor</td>
                    <td align="right">Desconto</td>
                    <td align="right">Valor Plano</td>
                    <td align="right">Comissão</td>
                </tr>
            </thead>
            <tbody>

                @foreach($individual as $d)
                    @php
                        $total_plano_individual += $d->comissao->contrato->valor_plano;
                        $total_comissao_individual += $d->valor_pago != null ? $d->valor_pago : $d->valor;
                    @endphp
                    <tr>
                        <td>{{$d->comissao->contrato->codigo_externo}}</td>
                        <td>{{date('d/m/Y',strtotime($d->comissao->contrato->created_at))}}</td>
                        <td style="font-size:0.6em;">{{mb_convert_case($d->comissao->contrato->clientes->nome,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td>Parcela {{$d->parcela}}</td>
                        <td align="center">{{number_format($d->comissao->contrato->valor_plano,2,",",".")}}</td>
                        <td align="right">{{$d->comissao->contrato->desconto_corretor}}</td>
                        <td align="right">{{number_format($d->comissao->contrato->valor_plano,2,",",".")}}</td>
                        <td align="right">{{$d->valor_pago != null ? number_format($d->valor_pago,2,",",".") : number_format($d->valor,2,",",".")}}</td>
                    </tr>
                @endforeach

            </tbody>
             
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="6"></td>
                    <td align="right">
                        @php
                            echo number_format($total_plano_individual,2,",",".");
                        @endphp
                    </td>
                    <td align="right">
                        @php
                           echo number_format($total_comissao_individual,2,",",".");
                        @endphp
                    </td>
                </tr>
            </tfoot>



        </table>
        

        @endif

        @if(count($coletivo) >= 1)
        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Plano Coletivo</div>
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>Contrato</td>
                    <td>Data</td>
                    <td>Cliente</td>
                    <td>Parcela</td>
                    <td align="center">Valor</td>
                    <td align="right">Desconto</td>
                    <td align="right">Valor Plano</td>
                    <td align="right">Comissão</td>
                </tr>
            </thead>
            <tbody>
                @foreach($coletivo as $d)

                    @php
                        if(isset($d->comissao->contrato->valor_plano) && !empty($d->comissao->contrato->valor_plano) && $d->comissao->contrato->valor_plano) {

                            $total_plano_coletivo += $d->comissao->contrato->valor_plano;
                            $total_comissao_coletivo += $d->valor_pago != null ? $d->valor_pago : $d->valor;
                            
                        }
                    @endphp

                    <tr>
                        <td>{{$d->comissao->contrato->codigo_externo}}</td>
                        <td>{{date('d/m/Y',strtotime($d->comissao->contrato->created_at))}}</td>
                        <td>{{mb_convert_case($d->comissao->contrato->clientes->nome,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td>Parcela {{$d->parcela}}</td>
                        <td align="center">{{number_format($d->comissao->contrato->valor_plano,2,",",".")}}</td>
                        <td align="right">{{$d->comissao->contrato->desconto_corretor}}</td>
                        <td align="right">{{number_format($d->comissao->contrato->valor_plano,2,",",".")}}</td>
                        <td align="right">{{$d->valor_pago != null ? number_format($d->valor_pago,2,",",".") : number_format($d->valor,2,",",".")}}</td>
                    </tr>
                @endforeach
            </tbody>
            
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="6"></td>
                    <td>
                        @php
                            echo number_format($total_plano_coletivo,2,",",".") ?? '';
                        @endphp
                    </td>
                    <td>
                        @php
                           echo number_format($total_comissao_coletivo,2,",",".") ?? '';
                        @endphp
                    </td>
                </tr>
            </tfoot>




        </table>
        

        @endif

        @if(count($empresarial) >= 1)
        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Empresarial</div>
        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td align="center">Parcela</td>
                    <td align="center">Data</td>
                    <td>Cliente</td>
                    <td align="center">Valor</td>
                    <td align="center">Comissão</td>
                </tr>
            </thead>
            <tbody>
                @foreach($empresarial as $d)
                    @php
                        $total_plano_empresarial += $d->comissao->contrato_empresarial->valor_plano;
                        $total_comissao_empresarial += $d->valor_pago != null ? $d->valor_pago : $d->valor;
                    @endphp

                    <tr>
                        <td align="center">Parcela {{$d->parcela}}</td>
                        <td align="center">{{date('d/m/Y',strtotime($d->comissao->contrato_empresarial->created_at))}}</td>
                        <td>{{mb_convert_case($d->comissao->contrato_empresarial->responsavel,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td align="center">{{number_format($d->comissao->contrato_empresarial->valor_plano,2,",",".")}}</td>
                        <td align="center">{{$d->valor_pago != null ? number_format($d->valor_pago,2,",",".") : number_format($d->valor,2,",",".")}}</td>
                    </tr>

                @endforeach

            </tbody>
            
            <tfoot style="border-top:1px solid black;">
                <tr>
                    <th colspan="3"></th>
                    <th>
                        @php
                            echo number_format($total_plano_empresarial,2,",",".") ?? '';
                        @endphp
                    </th>
                    <th>
                        @php
                           echo number_format($total_comissao_empresarial,2,",",".") ?? '';
                        @endphp
                    </th>
                </tr>
            </tfoot>








        </table>
        

        @endif

        
        
    </body>
</html>