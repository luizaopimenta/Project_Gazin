<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desenvolvedor extends Model
{
    public const TABLE = 'desenvolvedores';
    protected $table = self::TABLE;
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'nivel_id',
        'nome',
        'sexo',
        'data_nascimento',
        'hobby',
    ];

    protected function niveis(){
        return $this->hasOne(Nivel::class, 'id', 'nivel_id');
    }

}
