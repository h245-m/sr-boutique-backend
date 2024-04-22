<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WishList\IndexWishListRequest;
use App\Http\Requests\WishList\StoreWishListRequest;
use App\Http\Requests\WishList\UpdateWishListRequest;
use App\Http\Resources\WishListResource;
use Illuminate\Http\Request;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexWishListRequest $request)
    {

        $user = $request->user;
        $data = $request->validated();

        $query =$user->wish_list()->isLive(true);

        $query->when(isset($data['query']), function ($query) use ($data) {
            $query->where('name', 'like', '%' . $data['query'] . '%');
        })->when(isset($data['sort_by']), function ($query) use ($data) {
                if ($data['asc']) {
                    $query->orderBy($data['sort_by']);
                } else {
                    $query->orderByDesc($data['sort_by']);
                }
            });
            
        $wishList_products = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(WishListResource::collection($wishList_products)->response()->getData(), 'WishList fetched successfully');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWishListRequest $request)
    {

        $data = $request->validated();
        $user = $request->user;

        $user->wish_list()->syncWithoutDetaching([$data['product_id']]);

        return $this->respondNoContent();
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWishListRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id , Request $request)
    {
        $user = $request->user;

        if (!$user->wish_list()->where('product_id' , $id)->exists()) {
            return $this->respondNotFound('WishList not found');
        }
        
        $user->wish_list()->detach($id);
        return $this->respondNoContent();
    }
}
