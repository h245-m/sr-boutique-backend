<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->when($this->getFirstMediaUrl("main") != "", MediaResource::make($this->getMedia("main")->first())),
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'rate' => $this->averageRating(),
            'type' => $this->type,
            'live' => $this->live,
            'category_id' => $this->category_id,
            'additional_images' => $this->when(($request->isMethod("POST") || $request->is('api/product/*')) && $this->getMedia("additional_images") != "", MediaResource::collection($this->getMedia("additional_images"))),
            'colors' => $this->when($request->is('api/product/*') && $request->isMethod("GET"), function () { 
                return $this->whenLoaded('colors' , AttributeResource::collection($this->colors));
            }),
            'sizes' => $this->when($request->is('api/product/*') && $request->isMethod("GET"), function () { 
                return $this->whenLoaded('sizes' , AttributeResource::collection($this->sizes));
            }),
            'ratings' => $this->when($request->is('api/product/*') && $request->isMethod("GET"), function () { 
                return $this->whenLoaded('ratings' , RatingResource::collection($this->ratings))->response()->getData();
            }),
        ];
    }
}
