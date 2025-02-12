<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContratoEmpresarial extends Model
{
    use HasFactory;
    protected $table = "contrato_empresarial";
    protected $fillable = [
            "plano_id",
            "desconto_corretora",
            "desconto_corretor",
            "tabela_origens_id",
            "user_id",
            "financeiro_id",
            "data",
            "codigo_corretora",
            "codigo_vendedor",
            "cnpj",
            "razao_social",
            "quantidade_vidas",
            "taxa_adesao",
            "valor_plano",
            "valor_total",
            "vencimento_boleto",
            "valor_boleto",
            "codigo_cliente",
            "senha_cliente",
            // "dia_vencimento",
            "valor_plano_odonto",
            "valor_plano_saude",
            "codigo_saude",
            "codigo_odonto",
            "responsavel",
            "telefone",
            "celular",
            "email",
            "codigo_externo",
            "data_boleto",
            "cidade",
            "uf",
            "plano_contrado",
            "created_at"
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }




        public function comissao()
        {
            return $this->hasOne(Comissoes::class);
        }   

        public function financeiro()
        {
            return $this->belongsTo(EstagioFinanceiros::class,'financeiro_id','id');
        }
        
            






}
