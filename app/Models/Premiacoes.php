<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PremiacoesCorretoresLancadas;


class premiacoes extends Model
{
    use HasFactory;

    public function premiacoesLancadas()
    {
        return $this->hasMany(PremiacoesCorretoresLancadas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);   
    }

}