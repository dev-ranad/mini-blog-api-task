<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\Response;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    use Response;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth('sanctum')->user()->can('comment.index', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $model = new Comment;
        $query = $model::latest();

        if($request->has('post_id') && $request->post_id) {
            $query->where('post_id', $request->post_id);
        }


        $query->with([
            'author' => function ($query) {
                $query->select('id', 'name', 'email');
            },
            'post' => function ($query) {
                $query->select('id', 'title');
            }
        ]);

        if ($request->has('allData') && $request->allData) {

            $totalCount = $query->count();
            $results = $query->paginate($request->perPage ?? 10);

            $data = ['comment' => $results->items()];

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
            $data = ['comment' => $results];

            return ['results' => $data];
        }
    }
    public function status(Comment $comment)
    {
        if (!auth('sanctum')->user()->can('comment.status', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $comment->update([
            'status' => $comment->status == '1' ? '0' : '1'
        ]);

        return $this->successResponse(
            $this->responseMessage('Comment', 'update'),
            $comment
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth('sanctum')->user()->can('comment.store', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try {
            DB::beginTransaction();
            $request->validate([
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string|max:1000',
            ]);

            $comment = Comment::create([
                'post_id' => $request->post_id,
                'user_id' => auth('sanctum')->user()->id,
                'content' => $request->content,
            ]);

            DB::commit();

            return $this->successResponse('Comment created successfully.', $comment, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Comment creation failed. ' . $e->getMessage() . '.', [], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        if (!auth('sanctum')->user()->can('comment.show', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $comment->load(['author', 'post']);

        return $this->successResponse(
            $this->responseMessage('Comment', 'show'),
            $comment
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (!auth('sanctum')->user()->can('comment.destroy', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try {
            DB::beginTransaction();

            $comment->delete();

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Comment', 'destroy'),
                null
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Comment deletion failed. ' . $e->getMessage() . '.', [], 500);
        }
    }
}
