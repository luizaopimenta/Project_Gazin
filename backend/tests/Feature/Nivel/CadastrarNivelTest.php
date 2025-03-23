<?php

use App\Models\Nivel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cadastrar nivel', function () {
    $nivelData= [
        'nivel' => 'Nivel 1'
    ];
    $response = $this->post('api/niveis', $nivelData);
    $response->assertStatus(201); // 201 Created
    $this->assertDatabaseHas('niveis', $nivelData);
});
