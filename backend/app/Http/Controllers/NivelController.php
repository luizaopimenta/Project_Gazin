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

/**
 * @OA\Get(
 *     path="/niveis",
 *     tags={"Níveis"},
 *     summary="Lista os níveis com paginação e busca.",
 *     description="Retorna uma lista de níveis com suporte a paginação e busca por termo no campo 'nivel'.",
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Número de itens por página.",
 *         required=false,
 *         @OA\Schema(type="integer", default=5)
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Termo de busca para filtrar os níveis pelo campo 'nivel'.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de níveis retornada com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="ID do nível."),
 *                     @OA\Property(property="nivel", type="string", description="Nome do nível.")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="meta",
 *                 type="object",
 *                 @OA\Property(property="total", type="integer", description="Total de registros encontrados."),
 *                 @OA\Property(property="current_page", type="integer", description="Página atual."),
 *                 @OA\Property(property="per_page", type="integer", description="Número de itens por página."),
 *                 @OA\Property(property="last_page", type="integer", description="Última página disponível.")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Nenhum registro encontrado.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Nenhum registro encontrado.")
 *         )
 *     )
 * )
 */
    public function index(Request $request): jsonResponse
    {
        $perPage = $request->query('per_page', 5);
        $nivelQuery = Nivel::query()->withCount('desenvolvedores');

        if($request->has('search')) {
            $searchTerm = $request->query('search');
            $nivelQuery->where('nivel', 'ilike', '%' . $searchTerm . '%');
        }

        if($request->has('order')){
            $orderTerm = $request->order;
            $orderDirecion = $request->direction;
            $nivelQuery->orderBy($orderTerm, $orderDirecion );
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


    /**
     * @OA\Post(
     *     path="/niveis",
     *     tags={"Níveis"},
     *     summary="Cria um novo nível.",
     *     description="Cria um novo registro de nível com base nos dados fornecidos.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nivel", type="string", description="Nome do nível.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Nível criado com sucesso.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", description="ID do nível criado."),
     *                 @OA\Property(property="nivel", type="string", description="Nome do nível criado.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="object",
     *                 @OA\AdditionalProperties(type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     )
     * )
     */

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


/**
 * @OA\Put(
 *     path="/niveis/{id}",
 *     tags={"Níveis"},
 *     summary="Atualiza um nível existente.",
 *     description="Atualiza os dados de um nível com base no ID fornecido.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do nível a ser atualizado.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nivel", type="string", description="Nome atualizado do nível.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Nível atualizado com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID do nível atualizado."),
 *                 @OA\Property(property="nivel", type="string", description="Nome atualizado do nível.")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erro de validação.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="object",
 *                 @OA\AdditionalProperties(type="array", @OA\Items(type="string"))
 *             )
 *         )
 *     )
 * )
 */

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

/**
 * @OA\Delete(
 *     path="/niveis/{id}",
 *     tags={"Níveis"},
 *     summary="Remove um nível.",
 *     description="Remove um nível com base no ID fornecido, desde que não existam desenvolvedores associados.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do nível a ser removido.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Nível removido com sucesso."
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Não é possível excluir o nível devido a desenvolvedores associados.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Não é possível excluir o nível. Existem desenvolvedores associados a ele."
 *             )
 *         )
 *     )
 * )
 */

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
