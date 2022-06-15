<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseFormatter;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductCollection;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $products = Product::with('comments')
            ->search($search)
            ->latest()
            ->paginate();

        if ($products) {
            return ResponseFormatter::success($products);
        } else {
            return ResponseFormatter::error();
        }
    }

    public function event(Request $request)
    {
        $results = [];

        $search = $request->get('search', '');

        $products = Product::where('category', 'Event')
            ->search($search)
            ->latest()
            ->paginate($request->item);

        foreach ($products as $product) {
            $data = $product;
            foreach ($product->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($product->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->id ? true : false;
            }
            array_push($results, $data);
        }

        if ($products) {
            return ResponseFormatter::success($results);
        } else {
            return ResponseFormatter::error();
        }
    }

    public function destination(Request $request)
    {
        $results = [];

        $search = $request->get('search', '');

        $products = Product::where('category', 'Destinasi')
            ->search($search)
            ->latest()
            ->paginate($request->item);

        foreach ($products as $product) {
            $data = $product;
            foreach ($product->comments as $comment) {
                $data['comments'] = $comment->user;
            }
            foreach ($product->likes as $like) {
                $data['likes'] = $like->user;
                $data['favorite'] = $like->user->id == $request->id ? true : false;
            }
            array_push($results, $data);
        }

        if ($products) {
            return ResponseFormatter::success($results);
        } else {
            return ResponseFormatter::error();
        }
    }

    /**
     * @param \App\Http\Requests\ProductStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $validated = $request->validate(ProductStoreRequest::rules());
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('public');
            }

            $product = Product::create($validated);

            return ResponseFormatter::success($product);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product)
    {
        // $this->authorize('view', $product);
        // $data = [];
        $data = $product;

        foreach ($product->comments as $comment) {
            $data['comments'] = $comment->user;
        }
        foreach ($product->likes as $like) {
            $data['likes'] = $like->user;
            $data['favorite'] = $like->user->id == $request->id ? true : false;
        }

        return new ProductResource($data);
    }

    /**
     * @param \App\Http\Requests\ProductUpdateRequest $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $this->authorize('update', $product);
            $validated = $request->validate(ProductUpdateRequest::rules());

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::delete($product->image);
                }

                $validated['image'] = $request->file('image')->store('public');
            }

            $product->update($validated);

            return ResponseFormatter::success($product);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Product $product)
    {
        try {
            // $this->authorize('delete', $product);

            if ($product->image) {
                Storage::delete($product->image);
            }

            $product->delete();

            return ResponseFormatter::success($product);
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th);
        }
    }
}
