<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administradoras extends Model
{
    use HasFactory;

    public function comissao()
    {
        return $this->hasMany(comissoes::class,"administradora_id","id");
    }


}
