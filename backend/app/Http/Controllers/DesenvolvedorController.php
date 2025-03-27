<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesenvolvedorResource;
use App\Models\Desenvolvedor;
use App\Validate\DesenvolvedorValidate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DesenvolvedorController extends Controller
{
/**
 * @OA\Get(
 *     path="/desenvolvedores",
 *     tags={"Desenvolvedores"},
 *     summary="Lista os desenvolvedores com paginação e busca.",
 *     description="Retorna uma lista de desenvolvedores com suporte a paginação e busca por termo no campo 'nome'.",
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
 *         description="Termo de busca para filtrar os desenvolvedores pelo campo 'nome'.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de desenvolvedores retornada com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="ID do desenvolvedor."),
 *                     @OA\Property(property="nome", type="string", description="Nome do desenvolvedor."),
 *                     @OA\Property(property="nivel_id", type="integer", description="ID do nível associado."),
 *                     @OA\Property(property="sexo", type="string", description="Sexo do desenvolvedor."),
 *                     @OA\Property(property="data_nascimento", type="string", format="date", description="Data de nascimento do desenvolvedor."),
 *                     @OA\Property(property="hobby", type="string", description="Hobby do desenvolvedor.")
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
        public function index(Request $request)
        {
            $perPage = $request->query('per_page', 5);
            $desenvolvedorQuery = Desenvolvedor::query();

            if ($request->has('search')) {
                $searchTerm = $request->query('search');
                $desenvolvedorQuery->where('nome', 'ilike', '%' . $searchTerm . '%');
            }

            if($request->has('order')){
                $orderTerm = $request->order;
                $orderDirecion = $request->direction;
                $desenvolvedorQuery->orderBy($orderTerm, $orderDirecion );
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


        /**
         * @OA\Post(
         *     path="/desenvolvedores",
         *     tags={"Desenvolvedores"},
         *     summary="Cria um novo desenvolvedor.",
         *     description="Cria um novo registro de desenvolvedor com base nos dados fornecidos.",
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(property="nivel_id", type="integer", description="ID do nível associado."),
         *             @OA\Property(property="nome", type="string", description="Nome do desenvolvedor."),
         *             @OA\Property(property="sexo", type="string", description="Sexo do desenvolvedor."),
         *             @OA\Property(property="data_nascimento", type="string", format="date", description="Data de nascimento do desenvolvedor."),
         *             @OA\Property(property="hobby", type="string", description="Hobby do desenvolvedor.")
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Desenvolvedor criado com sucesso.",
         *         @OA\JsonContent(
         *             type="object",
         *             @OA\Property(
         *                 property="data",
         *                 type="object",
         *                 @OA\Property(property="id", type="integer", description="ID do desenvolvedor criado."),
         *                 @OA\Property(property="nivel_id", type="integer", description="ID do nível associado."),
         *                 @OA\Property(property="nome", type="string", description="Nome do desenvolvedor."),
         *                 @OA\Property(property="sexo", type="string", description="Sexo do desenvolvedor."),
         *                 @OA\Property(property="data_nascimento", type="string", format="date", description="Data de nascimento do desenvolvedor."),
         *                 @OA\Property(property="hobby", type="string", description="Hobby do desenvolvedor.")
         *             )
         *         )
         *     ),
         *     @OA\Response(
 *         response=400,
 *         description="É necessário haver um nível cadastrado.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Não é possível cadastrar desenvolvedor. Não existem níveis cadastrados."
 *             )
 *         )
 *
 *     )
         * )
         */

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

/**
 * @OA\Put(
 *     path="/desenvolvedores/{id}",
 *     tags={"Desenvolvedores"},
 *     summary="Atualiza um desenvolvedor existente.",
 *     description="Atualiza os dados de um desenvolvedor com base no ID fornecido.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do desenvolvedor a ser atualizado.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="nivel_id", type="integer", description="ID do nível associado."),
 *             @OA\Property(property="nome", type="string", description="Nome do desenvolvedor."),
 *             @OA\Property(property="sexo", type="string", description="Sexo do desenvolvedor."),
 *             @OA\Property(property="data_nascimento", type="string", format="date", description="Data de nascimento do desenvolvedor."),
 *             @OA\Property(property="hobby", type="string", description="Hobby do desenvolvedor.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Desenvolvedor atualizado com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="ID do desenvolvedor atualizado."),
 *                 @OA\Property(property="nivel_id", type="integer", description="ID do nível associado."),
 *                 @OA\Property(property="nome", type="string", description="Nome do desenvolvedor."),
 *                 @OA\Property(property="sexo", type="string", description="Sexo do desenvolvedor."),
 *                 @OA\Property(property="data_nascimento", type="string", format="date", description="Data de nascimento do desenvolvedor."),
 *                 @OA\Property(property="hobby", type="string", description="Hobby do desenvolvedor.")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="É necessário haver um nível cadastrado.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Não é possível cadastrar desenvolvedor. Não existem níveis cadastrados."
 *             )
 *         )
 *
 *     )
 * )
 */

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

/**
 * @OA\Delete(
 *     path="/desenvolvedores/{id}",
 *     tags={"Desenvolvedores"},
 *     summary="Remove um desenvolvedor.",
 *     description="Remove um desenvolvedor com base no ID fornecido.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID do desenvolvedor a ser removido.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Desenvolvedor removido com sucesso.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Registro removido com sucesso."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Erro ao tentar remover o desenvolvedor.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Erro ao tentar remover o desenvolvedor."
 *             )
 *         )
 *     )
 * )
 */

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
