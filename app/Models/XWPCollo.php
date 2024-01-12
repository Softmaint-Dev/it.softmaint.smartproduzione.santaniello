<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XWPCollo extends Model
{
    use HasFactory;

    public function prblattivita()
    {
        return $this->belongsTo(PRBLAttivita::class);
    }
}
