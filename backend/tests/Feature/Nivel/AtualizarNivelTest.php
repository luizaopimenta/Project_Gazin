<?php

use App\Models\Nivel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('atualizar nivel', function () {
    $nivel = Nivel::factory()->create();
    $nivelData= [
        'nivel' => 'Nivel 2'
    ];
    $response = $this->put("api/niveis/{$nivel->id}", $nivelData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('niveis', $nivelData);
});
