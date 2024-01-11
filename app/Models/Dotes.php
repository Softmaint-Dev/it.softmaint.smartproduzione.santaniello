<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dotes extends Model
{
    use HasFactory;
    protected $table = 'Dotes';

    public function cf()
    {
        return $this->hasOne(CF::class, 'Cd_CF', 'Cd_CF');
    }

    public function dms()
    {
         return DB::table('DmsDocument')
        ->join('dotes', 'dotes.Id_Dotes', '=', 'DmsDocument.EntityId')
        ->whereNotNull('DmsDocument.EntityId')
        ->whereRaw("CONVERT(NVARCHAR(MAX), DmsDocument.EntityId) LIKE CONCAT('%', CONVERT(NVARCHAR(MAX), ?), '%')", [$this->Id_DoTes])
        ->select("Id_DmsDocument", "Descrizione", "DocumentDate")
        ->get();
    }
}
