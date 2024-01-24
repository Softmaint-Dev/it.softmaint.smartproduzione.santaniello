<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRBLMateriale extends Model
{
    //Cd_ARLotto
    use HasFactory;

    protected $table = 'PRBLMateriale';

    protected $primaryKey = 'Id_PrBLMateriale';



    public function ar()
    {
        return $this->hasOne(AR::class, 'Cd_AR', 'Cd_AR');
    }

    public function arLotto()
    {
        return $this->hasOne(ARLotto::class, 'Cd_ARLotto', 'Cd_ARLotto');
    }

    
 
}
