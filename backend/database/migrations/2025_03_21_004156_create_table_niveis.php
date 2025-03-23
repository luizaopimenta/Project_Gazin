<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Nivel;

return new class extends Migration
{
    public function up()
    {
        Schema::create(Nivel::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('nivel',50)->not_null();
        });
    }

    public function down()
    {
        Schema::dropIfExists(Nivel::TABLE);
    }
};
