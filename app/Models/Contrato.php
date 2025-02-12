<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    public function administradora()
    {
        return $this->belongsTo(Administradoras::class);
    }

    public function financeiro()
    {
        return $this->belongsTo(EstagioFinanceiros::class,'financeiro_id','id');
    }

    public function cidade()
    {
        return $this->belongsTo(TabelaOrigens::class,'tabela_origens_id','id');
    }

    public function acomodacao()
    {
        return $this->belongsTo(Acomodacao::class);
    }

    public function plano()
    {
        return $this->belongsTo(Planos::class);
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function comissao()
    {
        return $this->hasOne(Comissoes::class);
    }

    public function premiacao()
    {
        return $this->hasOne(Premiacoes::class);
    }

    public function somarCotacaoFaixaEtaria()
    {
        return $this->hasMany(CotacaoFaixaEtaria::class)
            ->selectRaw("cotacao_faixa_etarias.contrato_id,sum(cotacao_faixa_etarias.quantidade) as soma")
            ->groupBy("cotacao_faixa_etarias.contrato_id");
    }

}
