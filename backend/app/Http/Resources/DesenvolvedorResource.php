<?php

namespace App\Http\Resources;

use App\Models\Nivel;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DesenvolvedorResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "nome" => $this->nome,
            "sexo" => $this->sexo,
            "data_nascimento" => $this->data_nascimento,
            "idade" => Carbon::parse($this->data_nascimento)->diffInYears(Carbon::now()),
            "hobby" => $this->hobby,
            "nivel" => $this->nivel($this->niveis),
        ];
    }
    protected function nivel(Nivel $nivel):array
    {
        return [
            "id" => $nivel->id,
            "nivel" => $nivel->nivel
        ];
    }
}
