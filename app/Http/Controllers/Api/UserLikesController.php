<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\LikeResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\LikeCollection;
use App\Http\Resources\ProductResource;
use App\Models\Like;
use App\Models\Product;

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
    public function store(Request $request)
    {
        // $this->authorize('create', Like::class);

        $cek = Like::where(['post_id' => $request->post_id, 'user_id' => $request->user_id])->first();
        if (isset($cek)) {
            $like = $cek->delete();
            $product = Product::where('id', $request->post_id)->first();

            $data = $product;

            foreach ($product->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($product->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->user_id ? true : false;
            }

            return new ProductResource($data);
        } else {
            $validated = $request->validate([
                'post_id' => ['required', 'exists:products,id'],
                'user_id' => ['required', 'exists:users,id'],
            ]);
            $like = Like::create($validated);

            $product = Product::where('id', $request->post_id)->first();

            $data = $product;

            foreach ($product->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($product->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->user_id ? true : false;
            }

            return new ProductResource($data);
        }
    }
}
