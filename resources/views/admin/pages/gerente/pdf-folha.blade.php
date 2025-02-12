<html>
    <head>
        <title></title>
        <style>
            * {margin:0;padding:0;font-size:1em;}
            td {font-size: 0.7em;}
        </style>
    </head>
    <body>
       <div style="width:95%;margin:0 auto;padding:5px 0;">
            <p style="font-size:0.75em;">AMERICA CORRETORA</p>
       </div>

       <div style="border-top:1px solid black;display:block;width:95%;left:20px;position:absolute;height:70px;padding:10px 0;">
            <div style="width:90%;position:relative;left:0;float:left;">
                <h2>ACOMPANHAMENTO DE VENDAS</h2>
                <p>Vendedor: {{$user}}</p>
                <p>Referência: {{$meses}} / {{$ano}}</p>
            </div>

            @if($logo && $logo != '')

                <div style="width:10%;position:relative;right:0;top:0;margin-bottom:5px;float:right;background-color:#A9A9A9;padding:2px;border-radius:5px;">
                    <img src="{{$logo}}" alt="Logo" id="Logo" style="width:100%;height:100%;" />
                </div>

            @endif

       </div>


       <div style="clear: both;"></div>

       @if($tipo == "corretora")
       <div style="display:block;height:80px;width:95%;left:20px;position:relative;border-top:1px solid black;padding:2px;">
            <h2>VENDAS</h2>
            <div>
                <span style="width:89%;left:0;float:left;">001 Faturamento</span>
                <span style="width:11%;right:0;top:0;float:right;text-align:right;">
                    <div style="width:75%;float:right;text-align:right;">{{number_format($comissao,2,",",".")}}</div>
                </span>
            </div>
            <div style="clear: both;"></div>


           <div>
               <span style="width:89%;left:0;float:left;">003 Adiantamento</span>
               <div style="width:11%;right:0;top:0;float:right;">

                   <div style="width:75%;float:right;text-align:right;">{{number_format($adiantamento,2,",",".")}}</div>
               </div>

           </div>
           <div style="clear: both;"></div>


           <div>
               <span style="width:89%;left:0;float:left;">004 Desconto</span>
               <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{number_format($desconto,2,",",".")}}</span>
           </div>
           <div style="clear: both;"></div>
           <div>
               <span style="width:89%;left:0;float:left;">005 Estorno</span>
               <span style="width:11%;right:0;top:0;float:right;text-align:right;">{{number_format($estorno,2,",",".")}}</span>
           </div>

           <div style="clear: both;"></div>
            <div>
                <span style="width:50%;left:0;float:left;">Total Geral</span>
                <span style="width:40%;right:0;top:0;float:right;text-align:right;">{{number_format($total,2,",",".")}}</span>
            </div>
        </div>
       @endif

        <div style="clear: both;"></div>

        @php
            $total_plano_individual = 0;
            $total_comissao_individual = 0;
            $total_desconto_individual = 0;
            $total_valor_individual = 0;
            $total_plano_coletivo = 0;
            $total_comissao_coletivo = 0;
            $total_plano_empresarial = 0;
            $total_comissao_empresarial = 0;
            $total_desconto_empresarial = 0;
            $total_estorno_calculado = 0;

            $total_quantidade = 0;


            $total_empresarial = 0;
            $i_individual = 0;
            $i_coletivo = 0;
            $i_empresarial = 0;
            $i_estorno = 0;
        @endphp

        @if(count($individual) >= 1 && $boolean_individual)





        <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Plano Individual</div>

        <table style="width:95%;margin:0 auto;">
            <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Contrato</td>
                    <td>Data</td>
                    <td>Cliente</td>
                    <td>Parcela</td>
                    <td style="text-align:center;">Vidas</td>
                    <td align="center">Valor</td>
                    @if($tipo == "corretora")

                    <td align="right">Faturamento</td>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($individual as $dd)
                    @php
                        ++$i_individual;

                        $total_plano_individual += $dd->valor_plano_contratado;
                        $total_comissao_individual += $dd->comissao != null ? $dd->comissao : $dd->comissao;
                        $total_valor_individual    += $dd->comissao;

                        $total_quantidade += $dd->quantidade_vidas;


                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_individual}}</td>
                        <td style="width:10%;">HAPVIDA</td>
                        <td style="width:8%;">{{$dd->codigo_externo}}</td>
                        <td style="width:8%;">{{date('d/m/Y',strtotime($dd->created_at))}}</td>
                        <td style="font-size:0.6em;width:35%;">{{mb_convert_case($dd->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:7%;">Parcela {{$dd->parcela}}</td>
                        <td style="width:7%;text-align:center;">
                            {{$dd->quantidade_vidas}}
                        </td>
                        <td style="width:15%;" align="center">
                            {{number_format($dd->valor_plano_contratado,2,",",".")}}
                        </td>
                        @if($tipo == "corretora")
                        <td style="width:5%;" align="right">{{$dd->comissao != null ? number_format($dd->comissao,2,",",".") : 0}}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>

            <tfoot style="border-top:1px solid black;">
                <tr>
                    <td colspan="6"></td>


                        <td style="width:7%;text-align:center;">
                            @php
                                echo $total_quantidade;
                            @endphp
                        </td>

                        <td style="width:7%;text-align:center;">
                            @php
                                echo number_format($total_plano_individual,2,",",".");
                            @endphp
                        </td>
                    @if($tipo == "corretora")
                        <td style="width:7%;text-align:right;">
                            @php
                               echo number_format($total_comissao_individual,2,",",".");
                            @endphp
                        </td>
                    @endif
                </tr>
            </tfoot>
        </table>
        @endif

        @if($estorno_table && $estorno != 0)
            <div style="width:95%;border-bottom:1px solid black;margin:0 auto;background-color:rgb(231,230,230);font-weight:bold;padding:5px 0;">Estorno</div>
            <table style="width:95%;margin:0 auto;">
                <thead style="border-bottom:1px solid black;">
                <tr>
                    <td>#</td>
                    <td>Admin</td>
                    <td>Plano</td>
                    <td>Contrato</td>
                    <td align="center">Data</td>
                    <td>Cliente</td>
                    <td style="text-align:center;">Parcela</td>
                    <td >Valor</td>
                    <td align="center">Estorno</td>
                </tr>
                </thead>
                <tbody>
                @foreach($estorno_table as $et)
                    @php
                        ++$i_estorno;

                        $total_estorno_calculado += $et->total_estorno;
                    @endphp
                    <tr>
                        <td style="width:3%;">{{$i_estorno}}</td>
                        <td style="width:8%;">{{$et->administradora}}</td>
                        <td style="width:8%;">{{$et->plano}}</td>
                        <td style="width:6%;">{{$et->contrato}}</td>
                        <td style="width:8%;" align="center">{{$et->data}}</td>
                        <td style="width:30%;">{{mb_convert_case($et->cliente,MB_CASE_UPPER,"UTF-8")}}</td>
                        <td style="width:8%;text-align: center;">Parcela {{$et->parcela}}</td>
                        <td style="width:8%;">{{number_format($et->valor,2,",",".")}}</td>
                        <td style="width:8%;" align="center">{{number_format($et->total_estorno,2,",",".")}}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot style="border-top:1px solid black;">
                    <tr>
                        <td colspan="8"></td>
                        <td align="center">
                            @php
                                echo number_format($total_estorno_calculado,2,",",".") ?? '';
                            @endphp
                        </td>
                    </tr>
                </tfoot>
            </table>
        @endif





    </body>
</html>
