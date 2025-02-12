<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;


    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'permission_cargos','cargo_id','permission_id');
    }
}
