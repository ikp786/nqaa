<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'unique_id'         => $this->unique_id,
            'product_quantity'  => $this->product_quantity,
            'product_amount'    => $this->product_amount,
            'total_amount'      => $this->total_amount,
            'address'           => $this->address,
            'address_type'      => $this->address_type,
            'product'           => new ProductResource($this->product),
        ];
    }
}
