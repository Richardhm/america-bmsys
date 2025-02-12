<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cancelado extends Model
{
    use HasFactory;

    protected $fillable = ["comissoes_id","data_baixa","motivo","observacao"];


}
