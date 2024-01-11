<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRBLAttivita extends Model
{
    use HasFactory;
    protected $table = 'PRBLAttivita';

    public function prolAttivita()
    {
        return $this->hasOne(PROLAttivita::class, 'Id_PrOLAttivita', 'Id_PrOLAttivita');
    }
}
