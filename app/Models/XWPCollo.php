<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XWPCollo extends Model
{
    use HasFactory;

    //XLotto

    protected $table = 'xWPCollo';


    public function prblattivita()
    {
        return $this->hasMany(PROLAttivita::class, 'Id_PRBLAttivita', 'Id_PrBLAttivita');
    }

    
}
