<?php

namespace App\Http\Controllers\Api;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeCollection;
use App\Http\Requests\LikeStoreRequest;
use App\Http\Requests\LikeUpdateRequest;

class LikeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Like::class);

        $search = $request->get('search', '');

        $likes = Like::search($search)
            ->latest()
            ->paginate();

        return new LikeCollection($likes);
    }

    /**
     * @param \App\Http\Requests\LikeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LikeStoreRequest $request)
    {
        $this->authorize('create', Like::class);

        $validated = $request->validated();

        $like = Like::create($validated);

        return new LikeResource($like);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Like $like
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Like $like)
    {
        $this->authorize('view', $like);

        return new LikeResource($like);
    }

    /**
     * @param \App\Http\Requests\LikeUpdateRequest $request
     * @param \App\Models\Like $like
     * @return \Illuminate\Http\Response
     */
    public function update(LikeUpdateRequest $request, Like $like)
    {
        $this->authorize('update', $like);

        $validated = $request->validated();

        $like->update($validated);

        return new LikeResource($like);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Like $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Like $like)
    {
        $this->authorize('delete', $like);

        $like->delete();

        return response()->noContent();
    }
}
