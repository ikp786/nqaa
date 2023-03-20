<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id'              => $this->id,
            'order_number'          => $this->order_number,
            'quantity'              => $this->quantity,
            'price'                 => $this->price,
            'status'                => $this->status,
            'created_at'            => date('d M Y, h:i A',strtotime($this->created_at)) 
        ];
    }
}
