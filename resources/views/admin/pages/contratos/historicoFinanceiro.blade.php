@if($contrato)
	<div style="background-color:#123449;border-radius: 5px;padding:5px 0;">
		<h5 class="text-center my-2 text-white">{{!empty($contrato->clientes->nome) ? $contrato->clientes->nome : $contrato->responsavel}}</h5>	
		<table class="table table-sm mx-auto" style="width:90%;color:#FFF;">
			<thead>
				<tr>
					<th colspan="5" class="text-center bg-white text-dark">Comissões</th>
				</tr>
				<tr>
					<th>Parcela</th>
					<th>Data</th>
					<th>Valor</th>
					<th>Parcela</th>
					<th>Comissão</th>
				</tr>
			</thead>	
			<tbody>
				@foreach($contrato->comissao->comissoesLancadas as $cc)
					<tr>
						<td>{{$cc->parcela}}</td>
						<td>{{date('d/m/Y',strtotime($cc->data))}}</td>
						<td>{{number_format($cc->valor,2,",",".")}}</td>
						<td>
							@if($cc->status_financeiro == 0)
								<span>Não Pago</span>
							@else
								<span>Pago</span>
							@endif
						</td>
						<td>
							@if($cc->status_gerente == 0)
								<span>Não Pago</span>
							@else
								<span>Pago</span>
							@endif
						</td>

					</tr>
				@endforeach		
			</tbody>
		</table>
	</div>
@else
	<p>Selecionar um Cliente para visualizar o seu historico</p>
@endif