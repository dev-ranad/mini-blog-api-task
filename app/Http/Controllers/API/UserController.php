<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\Response;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use Response;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth('sanctum')->user()->can('user.index', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $model = new User();
        $query = $model::latest();

        $query->with([
            'roles' => function ($query) {
                $query->select('id', 'name');
            }
        ]);

        if ($request->has('allData') && $request->allData) {

            $totalCount = $query->count();
            $results = $query->paginate($request->perPage ?? 10);

            $data = ['user' => $results->items()];

            return [
                'results' => $data,
                'meta' => [
                    'total' => $totalCount ?? 0,
                    'per_page' => $request->perPage,
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage(),
                    'next_page_url' => $results->nextPageUrl(),
                    'prev_page_url' => $results->previousPageUrl(),
                    'from' => $results->firstItem(),
                    'to' => $results->lastItem(),
                ]
            ];
        } else {
            $results = $query->get();
            $data = ['user' => $results];

            return ['results' => $data];
        }
    }
}
