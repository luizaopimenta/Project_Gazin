<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Desenvolvedor;
use App\Models\Nivel;

return new class extends Migration
{
    public function up()
    {
        Schema::create( Desenvolvedor::TABLE, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nivel_id')->not_null();
            $table->string('nome', 255)->not_null();
            $table->char('sexo', 1 )->not_null();
            $table->date('data_nascimento')->not_null();
            $table->string('hobby', 50)->not_null();
            $table->foreign('nivel_id')->references('id')->on(Nivel::TABLE);
        });
    }

    public function down()
    {
        Schema::dropIfExists(Desenvolvedor::TABLE);
    }
};
