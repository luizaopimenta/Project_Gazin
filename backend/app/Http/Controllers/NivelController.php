<?php

namespace App\Http\Controllers;

use App\Models\Nivel;
use Illuminate\Http\Request;
use App\Models\Desenvolvedor;
use App\Validate\NivelValidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NivelController extends Controller
{
    public function index(Request $request): jsonResponse
    {
        $perPage = $request->query('per_page', 5);
        $nivelQuery = Nivel::query();

        if($request->has('search')) {
            $searchTerm = $request->query('search');
            $nivelQuery->where('nivel', 'like', '%' . $searchTerm . '%');
        }

        $nivel = $nivelQuery->paginate($perPage);

        if($nivel->isEmpty()){
            return response()->json([
                "message" => "Nenhum registro encontrado",
            ], 400);
        }

        return response()->json([ "data" => $nivel->items(),
            "meta" => [
                "total" => $nivel->total(),
                "current_page" => $nivel->currentPage(),
                "per_page" => $nivel->perPage(),
                "last_page" => $nivel->lastPage(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate(NivelValidate::validar(), NivelValidate::message());

            $nivel = Nivel::create($validatedData);

            return response()->json([
                "data" => $nivel,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->errors(),
            ], 400);
        }
    }

    public function update(Nivel $nivel, Request $request)
    {
        try {
            $validatedData = $request->validate(NivelValidate::validar($request), NivelValidate::message());

            $nivel->update([
                "nivel" => $validatedData["nivel"],
            ]);

            return response()->json([
                "data" => $nivel,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                "message" => $e->errors(),
            ], 400);
        }
    }

    public function destroy(Nivel $nivel){
        try {

            $desenvolvedores = Desenvolvedor::where('nivel_id', $nivel->id)->exists();

            if ($desenvolvedores) {
                return response()->json([
                    'message' => 'Não é possível excluir o nível. Existem desenvolvedores associados a ele.',
                ], 400);
            }
            $nivel->delete();

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
