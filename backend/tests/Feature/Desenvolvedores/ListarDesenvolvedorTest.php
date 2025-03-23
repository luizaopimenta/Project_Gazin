<?php

use App\Models\Desenvolvedor;
use Database\Factories\DesenvolvedorFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('listar desenvolvedores', function () {
    Desenvolvedor::factory()->count(3)->create();
    $response = $this->get("/api/desenvolvedores");
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});
