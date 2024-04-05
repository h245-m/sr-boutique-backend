<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\HomeCard\StoreHomeCardRequest;
use App\Http\Requests\HomeCard\UpdateHomeCardRequest;
use App\Models\HomeCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHomeCardRequest $request)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) { 
            $file = $request->file('image');
            $name =  uniqid() . '.' . $file->extension();
            $file->storeAs('public/images/homeGroup/'. $data['home_group_id'] . "/" , $name);
            $data['image'] = 'storage/images/homeGroup/'. $data['home_group_id'] . "/" .$name;
        } 

        return $this->respondCreated(HomeCard::create($data), 'Home Card created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeCard $homeCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeCardRequest $request, HomeCard $homeCard)
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) { 
            $file = $request->file('image');
            $name =  uniqid() . '.' . $file->extension();
            $file->storeAs('public/images/homeGroup/'. $data['home_group_id'] . "/" , $name);
            $data['image'] = 'storage/images/homeGroup/'. $data['home_group_id'] . "/" .$name;
        } 

        return $this->respondCreated($homeCard->update($data), 'Home Card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeCard $homeCard)
    {
        if(File::exists($homeCard->image)) {
            File::delete($homeCard->image);
        }   

        $homeCard->delete();
        return $this->respondNoContent();
    }
}
