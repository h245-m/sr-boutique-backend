<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\IndexAdminProductRequest;
use App\Http\Requests\Product\IndexProductRequest;
use App\Models\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\User;
use Illuminate\Http\Request;

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
        })->when(isset($data['discount']), function ($query) {
            $query->whereRaw('priceAfter = price');
        })->when(isset($data['sort_by']), function ($query) use ($data) {
            if ($data['asc']) {
                $query->orderBy($data['sort_by']);
            } else {
                $query->orderByDesc($data['sort_by']);
            }
        });

        $products = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(ProductResource::collection($products)->response()->getData(), 'Products fetched successfully');
    }

    public function index_admin(IndexAdminProductRequest $request)
    {
        $data = $request->validated();

        $query = Product::query();

        $query->when(isset($data['query']), function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['query'] . '%');
        })
        ->when(isset($data['sort_by']), function ($query) use ($data) {
            if ($data['asc']) {
                $query->orderBy($data['sort_by']);
            } else {
                $query->orderByDesc($data['sort_by']);
            }
        })->when(isset($data['discount']), function ($query) {
            $query->whereRaw('priceAfter = price');
        })->when(isset($data['live']), function ($query) use ($data) {
            $query->isLive($data['live']);
        })->when(isset($data['expired']), function ($query) use ($data) {
            $query->isExpired($data['expired']);
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
        $data['priceAfter'] = $data['price'] - $data['discount'];

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

    public function show(Product $product , Request $request)
    {
        if (!$product->live || ($product->expires_at && $product->expires_at < now()) && (!$request->hasRole('product_admin') && !$request->hasRole('super_admin')) ) {
            return $this->respondNotFound('Product not found.');
        }

        $ratingsIds = $product->ratings()->select('id', 'user_id')->whereHas('user.media')->get()->pluck('user_id');
        $usersHasImages = User::whereIn('id' , $ratingsIds)->select('id')->inRandomOrder()->limit(5)->get();
        $product->setRelation('ratings',  $product->ratings()->select('id', 'rating', 'comment', 'rateable_id' , 'user_id')->paginate());
        $product->setRelation('randomImages', $usersHasImages);

        return $this->respondOk(ProductResource::make($product), 'Product fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if (isset($data['discount'])){
            $data['priceAfter'] = $data['price'] - $data['discount'];
        }
        
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
