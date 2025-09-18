<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
    use HasFactory;

     public function categoria(){
        return $this->hasOne(Categoria::class);
     }

      public function marca(){
        return $this->hasOne(Marca::class);
     }

      public function presentacion(){
        return $this->hasOne(Presentacione::class);
     }
}
