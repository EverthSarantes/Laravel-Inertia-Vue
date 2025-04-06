<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Api\SearchServices;

class SearchController extends Controller
{
    public function search(Request $request, string $model)
    {
        $model = config('model')[$model];

        if ($model === null) {
            return response()->json([
                'message' => 'Modelo no encontrado',
                'data' => null,
            ], 404);
        }
        
        if (!class_exists($model)) {
            return response()->json([
                'message' => 'Modelo no encontrado',
                'data' => null,
            ], 404);
        }

        if(!SearchServices::verifyUsersHasPermission($model)) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción',
                'data' => null,
            ], 403);
        }

        $data = SearchServices::search($model, $request->extraQueryParameter, $request->pagination, $request->params);

        if($data === false) {
            return response()->json([
                'message' => 'Tipo de búsqueda no permitido',
                'data' => null,
            ], 400);
        }

        if($data->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron resultados',
                'data' => null,
            ], 404);
        }

        $data = SearchServices::loadMorphableFields($model, $data);

        $data = SearchServices::loadOptionsFieldContent($data);

        $data = SearchServices::loadRelationFields($data);

        return response()->json([
            'message' => 'Búsqueda realizada con éxito',
            'data' => $data,
        ], 200);
    }

    public function searchSelect(string $model, string $search)
    {
        $model = config('model')[$model];

        if ($model === null) {
            return response()->json([
                'message' => 'Modelo no encontrado',
                'data' => null,
            ], 404);
        }
        
        if (!class_exists($model)) {
            return response()->json([
                'message' => 'Modelo no encontrado',
                'data' => null,
            ], 404);
        }

        if(!SearchServices::verifyUsersHasPermission($model)) {
            return response()->json([
                'message' => 'No tienes permisos para realizar esta acción',
                'data' => null,
            ], 403);
        }

        $data = SearchServices::searchSelect($model, $search);

        return response()->json([
            'message' => 'Búsqueda realizada con éxito',
            'data' => $data,
        ], 200);
    }
}
