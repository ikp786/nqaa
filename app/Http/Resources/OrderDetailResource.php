<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'order_number'              => $this->order_number,
            'address_type'              => $this->address_type,
            'address'                   => $this->address,
            'status'                    => $this->status,
            'mobile'                    => $this->mobile,
            'price'                     => $this->price,
            'delivery_image'            => $this->image,
            'delivery_image2'           => $this->image2,
            'created_at'                => date('d M Y, h:i A',strtotime($this->created_at)),
            'products'                  => OrderProductResource::collection($this->orderProducts)

        ];
    }
}
