<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dorig extends Model
{
    use HasFactory;
    protected $table = 'Dorig';

    public function dotes()
    {
        return $this->hasOne(Dotes::class, 'Id_DOTes', 'Id_DOTes');
    }

 

}
