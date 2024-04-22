<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class UserController extends Controller
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
    public function store(Request $request)
    {
        //
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
    public function update(UpdateUserRequest $request)
    {
        $data = $request->validated();
        $user = $request->user;

        $user->update($data);

        if ($request->hasFile('image')) {
            $user->clearMediaCollection("main");
            $user->addMediaFromRequest('image')->toMediaCollection("main");
        }

        return $this->respondOk(UserResource::make($user));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
