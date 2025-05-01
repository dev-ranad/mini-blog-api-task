<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\Attachmentable;
use App\Http\Traits\Response;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    use Response, Attachmentable;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!auth('sanctum')->user()->can('post.index', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $model = new Post;
        $query = $model::latest();

        if (auth('sanctum')->check()) {
            $user = User::find(auth('sanctum')->user()->id);

            if ($user->hasRole('User')) {
                $query->where('user_id', $user->id);
            }
        }

        $query->with([
            'author' => function ($query) {
                $query->select('id', 'name', 'email');
            },
            'categories' => function ($query) {
                $query->select('categories.id', 'categories.name');
            },
            'attachments' => function ($query) {
                $query->select('attachments.id', 'attachments.url', 'attachments.attachmentable_id', 'attachments.attachmentable_type');
            }
        ]);

        if ($request->has('allData') && $request->allData) {

            $totalCount = $query->count();
            $results = $query->paginate($request->perPage ?? 10);

            $data = ['post' => $results->items()];

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
            $data = ['post' => $results];

            return ['results' => $data];
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth('sanctum')->user()->can('post.store', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try{
            DB::beginTransaction();

            $validated = $request->validate([
                'title' => 'required|string|max:255|unique:posts,title',
                'content' => 'required|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
            ]);

            $post = new Post();
            $post->title = $validated['title'];
            $post->content = $validated['content'];
            $post->user_id = auth('sanctum')->user()->id;
            $post->save();

            $hasFile = $this->checkAttachment($request);
            if (!empty($hasFile)) {
                $this->handleAttachment($request, $post, 'Post', 'post', $hasFile);
            }

            if (!empty($validated['categories'])) {
                $post->categories()->attach($validated['categories']);
            }

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Post', 'store'),
                $post->load(['author', 'categories', 'attachments'])
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->errorResponse(
                $this->responseMessage('Post', 'validation'),
                $e->validator->errors()->messages()
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Post creation failed. ' . $e->getMessage() . '.', [], 500);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (!auth('sanctum')->user()->can('post.show', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        $post->load(['author', 'categories', 'comments', 'attachments']);

        return $this->successResponse(
            $this->responseMessage('Post', 'show'),
            $post
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (!auth('sanctum')->user()->can('post.update', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }

        try {
            DB::beginTransaction();
            $post->load(['author', 'categories']);

            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id',
                'deleteAttachmentIds' => 'nullable|array',
                'deleteAttachmentIds.*' => 'exists:attachments,id',
            ]);

            $post->update($request->all());

            if (!empty($request->deleteAttachmentIds)) $this->deleteOldAttachment($request->deleteAttachmentIds);
            $hasFile = $this->checkAttachment($request);
            if (!empty($hasFile)) {
                $this->handleAttachment($request, $post, 'Post', 'post', $hasFile);
            }

            if (!empty($validated['categories'])) {
                $post->categories()->sync($validated['categories']);
            }

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Post', 'update'),
                $post->load(['author', 'categories', 'attachments'])
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->errorResponse(
                $this->responseMessage('Post', 'validation'),
                $e->validator->errors()->messages()
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Post update failed. ' . $e->getMessage() . '.', [], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (!auth('sanctum')->user()->can('post.destroy', 'api')) {
            return $this->errorResponse($this->responseMessage('Authentication', 'unauthenticated-permission'), [], 403);
        }
        try {
            DB::beginTransaction();

            $this->deleteAttachment($post);

            $post->categories()->detach();
            $post->delete();

            DB::commit();

            return $this->successResponse(
                $this->responseMessage('Post', 'destroy'),
                null
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Post deletion failed. ' . $e->getMessage() . '.', [], 500);
        }
    }
}
