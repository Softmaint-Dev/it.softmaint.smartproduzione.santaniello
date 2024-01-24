<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PRBLAttivita extends Model
{
    use HasFactory;
    protected $table = 'PRBLAttivita';

    protected $primaryKey = 'Id_PrBLAttivita';


    public function prolAttivita()
    {
        return $this->hasOne(PROLAttivita::class, 'Id_PrOLAttivita', 'Id_PrOLAttivita');
    }



    public function ar()
    {
        return $this->hasOne(PROLDoRig::class, 'Cd_AR', 'Cd_AR');
    }



 
    public function materiale()
    {
        return $this->hasMany(PRBLMateriale::class, 'Id_PrBLAttivita', 'Id_PrBLAttivita');
  
    }

}
