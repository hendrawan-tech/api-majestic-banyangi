<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeCollection;

class UserLikesController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $search = $request->get('search', '');

        $likes = $user
            ->likes()
            ->search($search)
            ->latest()
            ->paginate();

        return new LikeCollection($likes);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $this->authorize('create', Like::class);

        $validated = $request->validate([
            'post_id' => ['required', 'exists:products,id'],
        ]);

        $like = $user->likes()->create($validated);

        return new LikeResource($like);
    }
}
