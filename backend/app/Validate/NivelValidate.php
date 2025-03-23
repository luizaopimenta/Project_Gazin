<?php

namespace App\Validate;

class NivelValidate
{
    public static function validar():array
    {
        return [
            "nivel" => "required|string|max:50",
        ];
    }

    public static function message():array
    {
        return [
            "required" => "O campo :attribute deve ser preenchido.",
            "nivel.string" => "O campo :attribute deve ser texto.",
            "nivel.max" => "O campo :attribute não deve ter mais que :max caracteres.",
        ];
    }
}
