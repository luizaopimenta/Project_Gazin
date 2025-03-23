<?php

use App\Models\Desenvolvedor;
use App\Models\Nivel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('atualizar desenvolvedor', function () {
    $nivel = Nivel::factory()->create();
    $desenvolvedor = Desenvolvedor::factory()->create();
    $desenvolvedorData= [
        'nome' => 'Desenvolvedor 1',
        'sexo' => 'F',
        'data_nascimento' => '1990-01-01',
        'hobby' => 'Desenvolvedor 2',
        'nivel_id' => $nivel->id,
    ];
    $response = $this->put("api/desenvolvedores/{$desenvolvedor->id}", $desenvolvedorData);
    $response->assertStatus(200);
    $this->assertDatabaseHas('desenvolvedores', $desenvolvedorData);
});
