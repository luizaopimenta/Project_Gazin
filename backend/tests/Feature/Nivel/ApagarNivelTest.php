<?php

use App\Models\Nivel;
use Database\Factories\NivelFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('apagar nivel', function () {
    $nivel = Nivel::factory()->create();
    $response = $this->delete("api/niveis/{$nivel->id}");
    $response->assertStatus(204);
    $this->assertDatabaseMissing('niveis', [
        'id' => $nivel->id,
    ]);

});
