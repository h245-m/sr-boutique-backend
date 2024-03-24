<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rating\StoreRatingRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use willvincent\Rateable\Rating;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->respondOk(Rating::where('user_id' , $request->user->id)->select('id' , 'rating' , 'comment')->get(), 'Ratings fetched successfully');
    }

    /**
     * Store a newly created resource in storage. or Update Existing one
     */
    public function store(StoreRatingRequest $request)
    {
        $data = $request->validated();

        $prodcut = Product::find($data['product_id']);

        if(!$prodcut){
            return $this->respondNotFound("Product not found");
        }

        $prodcut->rateOnce($data['rate'] , $data['comment'] ?? null , $request->user->id);

        return $this->respondNoContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating , Request $request)
    {
        if($rating->user_id != $request->user->id){
            return $this->respondNotFound("Rating not found");
        }

        $rating->delete();
        return $this->respondNoContent();
    }
}
