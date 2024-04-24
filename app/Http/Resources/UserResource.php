<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address,
            'nick' => $this->nick,
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'roles' => $this->getRoleNames(),
            'token' => $this->when($this->token, $this->token)
        ];
    }
}
