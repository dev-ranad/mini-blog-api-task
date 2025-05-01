<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\Response;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use Response;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth('sanctum')->user()->can('category.index', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $model = new Category;
        $query = $model::latest();

        if ($request->has('allData') && $request->allData) {

            $totalCount = $query->count();
            $results = $query->paginate($request->perPage ?? 10);

            $data = ['category' => $results->items()];

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
            $data = ['category' => $results];

            return ['results' => $data];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth('sanctum')->user()->can('category.store', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
            ]);

            $category = new Category();
            $category->name = $validated['name'];
            $category->save();

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Category', 'store'),
                $category
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->errorResponse(
                $this->responseMessage('Category', 'validation'),
                $e->validator->errors()->messages()
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Category creation failed. ' . $e->getMessage() . '.', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (!auth('sanctum')->user()->can('category.destroy', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try {
            DB::beginTransaction();

            $category->delete();

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Category', 'destroy'),
                null
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Category deletion failed. ' . $e->getMessage() . '.', [], 500);
        }
    }
}
