<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Session;
class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {        
        $totalCartPrice = Session::get('totalCartPrice');
        return [
            'unique_id'         => $this['unique_id'],
            'cart'              => CartCollectionResource::collection($this['data']),
            'totalCartPrice'    => $totalCartPrice
        ];
    }
}
