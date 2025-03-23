<?php

namespace Database\Factories;

use App\Models\Nivel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Desenvolvedor>
 */
class DesenvolvedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "nivel_id" => Nivel::factory(),
            "nome" => fake()->name,
            "sexo" => "M",
            "data_nascimento" => now(),
            "hobby" => "Desenvolvedor",
        ];
    }
}
