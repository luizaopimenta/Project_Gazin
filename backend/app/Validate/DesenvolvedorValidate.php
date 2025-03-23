<?php

namespace App\Validate;

class DesenvolvedorValidate
{

    public static function validate():array
    {
        return [
            "nivel_id" => "required|integer",
            "nome" => "required|string|max:255",
            "sexo" => "required|string|max:1|in:M,F",
            "data_nascimento" => "required|date",
            "hobby" => "required|string|max:50",
        ];
    }
    public static function message():array
    {
        return [
            "required" => "O campo :attribute deve ser preenchido.",
            "nivel_id.integer" => "O campo :attribute deve ser inteiro.",
            "nome.string" => "O campo :attribute deve ser texto.",
            "nome.max" => "O campo :attribute não deve ter mais que :max caracteres.",
            "sexo.string" => "O campo :attribute deve ser texto.",
            "sexo.max" => "O campo :attribute não deve ter mais que :max caracteres.",
            "data_nascimento.date" => "O campo :attribute deve ser data.",
            "hobby.string" => "O campo :attribute deve ser texto.",
            "hobby.max" => "O campo :attribute não deve ter mais que :max caracteres.",
        ];
    }
}
