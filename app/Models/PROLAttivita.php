<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PROLAttivita extends Model
{
    use HasFactory;

    protected $table = 'PROLAttivita';

    public function prolDoRig()
    {
        return $this->hasOne(PROLDoRig::class, 'Id_PrOL', 'Id_PrOL');
    }

}
