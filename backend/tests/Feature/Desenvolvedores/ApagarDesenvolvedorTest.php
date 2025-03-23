<?php

use App\Models\Desenvolvedor;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('apagar desenvolvedor', function () {
    $desenvolvedor = Desenvolvedor::factory()->create();
    $response = $this->delete("api/desenvolvedores/{$desenvolvedor->id}");
    $response->assertStatus(204);
    $this->assertDatabaseMissing('desenvolvedores', [
        'id' => $desenvolvedor->id,
    ]);

});
