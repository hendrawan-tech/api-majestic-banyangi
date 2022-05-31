<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
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
            ->paginate(5)
            ->withQueryString();

        return view('app.likes.index', compact('likes', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Like::class);

        $users = User::pluck('name', 'id');
        $products = Product::pluck('title', 'id');

        return view('app.likes.create', compact('users', 'products'));
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

        return redirect()
            ->route('likes.edit', $like)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Like $like
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Like $like)
    {
        $this->authorize('view', $like);

        return view('app.likes.show', compact('like'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Like $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Like $like)
    {
        $this->authorize('update', $like);

        $users = User::pluck('name', 'id');
        $products = Product::pluck('title', 'id');

        return view('app.likes.edit', compact('like', 'users', 'products'));
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

        return redirect()
            ->route('likes.edit', $like)
            ->withSuccess(__('crud.common.saved'));
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

        return redirect()
            ->route('likes.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
