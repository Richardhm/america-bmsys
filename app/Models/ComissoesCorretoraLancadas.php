<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComissoesCorretoraLancadas extends Model
{
    use HasFactory;

    public function comissao() 
    {
        return $this->belongsTo(Comissoes::class,'comissoes_id','id');
    }



}
