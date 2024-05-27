<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

class xDmsFolder extends Model
{
    use HasFactory;

    protected $table = 'xDmsFolder';

    protected $primaryKey = 'Id_xDmsFolder';

    public $timestamps = false;


    public function up()
    {
        Schema::create('nome_tabella', function (Blueprint $table) {
            $table->id();
            $table->string('Descrizione');
            $table->string('FileName');
            $table->binary('Content');
            $table->string('EntityTable');
            $table->float('EntityId');
            $table->integer('Id_DmsClass1');
            $table->integer('Id_DmsClass2');
            $table->string('DmsClass3');
            $table->string('Cd_DmsType');
            $table->dateTime('DocumentDate');
            $table->boolean('LinkedToFS');
            $table->integer('FileSize');
            $table->string('EntityDescription');
            $table->string('ComputerName');
            $table->string('FilePath');
            $table->timestamps();
        });
    }

    protected $fillable = [
        'Descrizione',
        'FileName',
        'Content',
        'EntityTable',
        'EntityId',
        'Id_DmsClass1',
        'Id_DmsClass2',
        'DmsClass3',
        'Cd_DmsType',
        'DocumentDate',
        'LinkedToFS',
        'FileSize',
        'EntityDescription',
        'ComputerName',
        'FilePath',
    ];

    public function dms($EntityId)
    {
        return DB::table('xDmsFolder')
            ->whereRaw("xDmsFolder.EntityId = ", [$EntityId])
            //->whereRaw("CONVERT(NVARCHAR(MAX), DmsDocument.EntityId) LIKE CONCAT('%', CONVERT(NVARCHAR(MAX), ?), '%')", [$id])
            ->select("Id_xDmsFolder", "Descrizione", "DocumentDate", "xType")
            ->get();
    }


}
