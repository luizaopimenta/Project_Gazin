<?php

use App\Models\Desenvolvedor;
use App\Models\Nivel;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('cadastrar desenvolvedor', function () {
    $nivel = Nivel::factory()->create();
    $desenvolvedorData= [
        'nome' => 'Desenvolvedor',
        'sexo' => 'M',
        'data_nascimento' => '1990-01-01',
        'hobby' => 'Desenvolvedor',
        'nivel_id' => $nivel->id,
    ];
    $response = $this->post('api/desenvolvedores', $desenvolvedorData);
    $response->assertStatus(201); // 201 Created
    $this->assertDatabaseHas('desenvolvedores', $desenvolvedorData);
});
