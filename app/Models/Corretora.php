<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corretora extends Model
{
    use HasFactory;

    protected $table = "corretoras";

    protected $fillable = [
            "nome",
            "logo",
            "endereco",
            "telefone",
            "site",
            "email",
            "instagram",
            "consultas_eletivas",
            "consultas_urgencia",
            "exames_simples",
            "exames_complexos",
            "linha_01_coletivo",
            "linha_02_coletivo",
            "linha_03_coletivo",
            "linha_01_individual",
            "linha_02_individual",
            "linha_03_individual",
            "cor"
        ];


}
