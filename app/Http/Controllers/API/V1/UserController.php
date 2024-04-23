<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexUserRequest $request)
    {
        $data = $request->validated();
        $query = User::query()->role($data['role']);

        $query->when(isset($data['query']) , function($query) use($data){
            $query->where('name' , 'like' , '%' . $data['query'] . '%')->orWhere('email' , 'like' , '%' . $data['query'] . '%');
        });

        $users = $query->paginate($data['per_page'] ?? 15);

        return $this->respondOk(UserResource::collection($users), 'Users fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        $user->assignRole('admin');
        // return $user;
        return $this->respondCreated(new UserResource($user) , 'User created successfully');
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
    public function destroy(User $user)
    {
        //
    }
}
