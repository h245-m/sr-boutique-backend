<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexProductRequest;
use App\Models\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource; 

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexProductRequest $request)
    {
        $data = $request->validated();

        $query = Product::isLive(true);

        $query->when(isset($data['query']), function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['query'] . '%');
        })
            ->when(isset($data['sort_by']), function ($query) use ($data) {
                if ($data['asc']) {
                    $query->orderBy($data['sort_by']);
                } else {
                    $query->orderByDesc($data['sort_by']);
                }
            });

        $products = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(ProductResource::collection($products)->response()->getData(), 'Products fetched successfully');
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $product = Product::create($data);

        if ($request->hasFile('image')) {
            $product->addMediaFromRequest('image')->toMediaCollection("main");
        }

        if ($request->hasFile('additional_images')) {
            $product
                ->addMultipleMediaFromRequest(['additional_images'])
                ->each(function ($fileAdder) {
                    $fileAdder->toMediaCollection("additional_images");
                });
        }

        return $this->respondCreated(ProductResource::make($product), 'product created successfully');
    }

    /**
     * Display the specified resource.
     */

    public function show(Product $product)
    {
        if (!$product->live) {
            return $this->respondNotFound('Product not found.');
        }

        // $product->setRelation('ratings',  $product-ww    >ratings()->select('id', 'rating', 'comment', 'rateable_id')->paginate());
        $product->setRelation('colors', $product->attributes()->where('type', 0)->get());
        $product->setRelation('sizes', $product->attributes()->where('type', 1)->get());

        return $this->respondOk(ProductResource::make($product), 'Product fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {

            if ($request->hasFile('image')) {
                $product->clearMediaCollection("main");
                $product->addMediaFromRequest('image')->toMediaCollection("main");
            }

            if ($request->hasFile('additional_images')) {
                $product->clearMediaCollection("additional_images");

                $product
                    ->addMultipleMediaFromRequest(['additional_images'])
                    ->each(function ($fileAdder) {
                        $fileAdder->toMediaCollection("additional_images");
                    });
            }
        }

        $product->update($data);
        return $this->respondOk(ProductResource::make($product), 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->respondNoContent();
    }
}
