<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    public const  TABLE = 'niveis';
    protected $table = self::TABLE;
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'nivel',
    ];

    public function desenvolvedores(){
        return $this->hasMany(Desenvolvedor::class);
    }
}
