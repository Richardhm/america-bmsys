<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComissoesCorretoresLancadas extends Model
{
    use HasFactory;

     protected $fillable = [
        "comissoes_id",
        "parcela",
        "data",
        "valor",
        "valor_pago",
        "desconto",
        "porcentagem_paga",
        "status_financeiro",
        "status_gerente",
        "status_apto_pagar",
        "status_comissao",
        "finalizado",
        "data_antecipacao",
        "data_baixa",
        "data_baixa_gerente",
        "data_baixa_finalizado",
        "documento_gerador",
        "estorno",
        "data_baixa_estorno",
        "cancelados",
        "atual"

    ];    
        
        
        
        
        
    public function comissao() 
    {
        return $this->belongsTo(Comissoes::class,'comissoes_id','id');
    }


}
