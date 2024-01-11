<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PROLDoRig extends Model
{
    use HasFactory;

    protected $table = 'PROLDoRig';

    public function dorig()
    {
        return $this->hasOne(Dorig::class, 'Id_DoRig', 'Id_DoRig');
    }

}
