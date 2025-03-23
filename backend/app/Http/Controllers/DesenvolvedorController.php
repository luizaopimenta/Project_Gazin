<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesenvolvedorResource;
use App\Models\Desenvolvedor;
use App\Validate\DesenvolvedorValidate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesenvolvedorController extends Controller
{

        public function index(Request $request)
        {
            $perPage = $request->query('per_page', 5);
            $desenvolvedorQuery = Desenvolvedor::query();

            if ($request->has('search')) {
                $searchTerm = $request->query('search');
                $desenvolvedorQuery->where('nome', 'like', '%' . $searchTerm . '%');
            }

            $desenvolvedor = $desenvolvedorQuery->paginate($perPage);

            if ($desenvolvedor->isEmpty()) {
                return response()->json([
                    "message" => "Nenhum registro encontrado",
                ], 400);
            }

            return response()->json(["data" =>   DesenvolvedorResource::collection($desenvolvedor->items()),
                "meta" => [
                    "total" => $desenvolvedor->total(),
                    "current_page" => $desenvolvedor->currentPage(),
                    "per_page" => $desenvolvedor->perPage(),
                    "last_page" => $desenvolvedor->lastPage(),
                ]
            ], 200);

        }

    public function store(Request $request){
        try {
            $validatedData = $request->validate(DesenvolvedorValidate::validate(), DesenvolvedorValidate::message());

            $desenvolvedor = Desenvolvedor::create($validatedData);

            return response()->json([
                "data" => $desenvolvedor,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->errors(),
            ], 400);
        }
    }

    public function update(Request $request, Desenvolvedor $desenvolvedor){
        try {
            $validatedData = $request->validate(DesenvolvedorValidate::validate(), DesenvolvedorValidate::message());

            $desenvolvedor->update([
                "nivel_id" => $validatedData['nivel_id'],
                "nome" => $validatedData['nome'],
                "sexo" => $validatedData['sexo'],
                "data_nascimento" => $validatedData['data_nascimento'],
                "hobby" => $validatedData['hobby'],
            ]);

            return response()->json([
                "data" => DesenvolvedorResource::make($desenvolvedor),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->errors(),
            ], 400);
        }
    }

    public function destroy(Desenvolvedor $desenvolvedor){

        try {

            $desenvolvedor->delete();

            return response()->json([
                "message" => "Registro removido com sucesso.",
            ], 204);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->getMessage(),
            ], 400);
        }

    }

}
