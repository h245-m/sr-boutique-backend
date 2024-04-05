<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\HomeGroup\StoreHomeGroupRequest;
use App\Http\Requests\HomeGroup\UpdateHomeGroupRequest;
use App\Models\HomeGroup;
use Illuminate\Support\Facades\File;

class HomeGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $homeGroups = HomeGroup::with("cards")->get();
        return $this->respondOk($homeGroups , 'Home Groups fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHomeGroupRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user->id;

        return $this->respondCreated(HomeGroup::create($data) , 'Home Group created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeGroup $homeGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHomeGroupRequest $request, HomeGroup $homeGroup)
    {
        $data = $request->validated();
        return $this->respondOk($homeGroup->update($data), 'Home Group updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeGroup $homeGroup)
    {
        $path = "storage/images/homeGroup/". $homeGroup->id;
        if (File::exists($path)) {
            File::deleteDirectory($path);
        }

        $homeGroup->delete();
        return $this->respondNoContent();
    }
}
