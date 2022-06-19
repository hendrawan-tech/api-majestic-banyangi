<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\ProductResource;
use App\Models\Comment;

class ProductCommentsController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {

        $search = $request->get('search', '');

        $comments = $product
            ->comments()
            ->search($search)
            ->latest()
            ->paginate();

        return new CommentCollection($comments);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'comment' => ['required', 'max:255', 'string'],
                'user_id' => ['required'],
                'destination_id' => ['required'],
            ]);

            $comment = Comment::create($validated);

            $product = Product::where('id', $request->destination_id)->first();

            $data = $product;

            foreach ($product->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($product->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->user_id ? true : false;
            }

            return new ProductResource($data);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }
}
