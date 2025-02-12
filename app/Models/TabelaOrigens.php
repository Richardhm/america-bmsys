<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelaOrigens extends Model
{
    use HasFactory;
    protected $table = "tabela_origens";
    protected $fillable = ["nome","uf"];
}
