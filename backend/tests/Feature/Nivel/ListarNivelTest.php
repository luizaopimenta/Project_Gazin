<?php

use App\Models\Nivel;
use Database\Factories\NivelFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('listar nivel', function () {
    Nivel::factory()->count(3)->create();
    $response = $this->get("/api/niveis");
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});
