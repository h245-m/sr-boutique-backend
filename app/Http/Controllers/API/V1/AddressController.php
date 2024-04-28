<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user;
        return $this->respondOk($user->addresses, 'Addresses fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        if ($request->user->addresses()->exists()) {
            return $this->respondError("Cannot create more than one address");
        }

        $data = $request->validated();
        $user = $request->user;

        $address = $user->addresses()->create($data);
        return $this->respondOk($address,'Address created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
        $data = $request->validated();
        $user = $request->user;

        if ($user->id != $address->user_id) {
            return $this->respondError("Unauthorized");
        }

        $address->update($data);
        return $this->respondOk($address,'Address updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $user = $address->user;

        if ($user->id != $address->user_id) {
            return $this->respondError("Unauthorized");
        }

        $address->delete();
        return $this->respondNoContent();
    }
}
