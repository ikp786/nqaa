<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Session;
class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $language_type = Session::get('language_type');
            if($language_type == 'en'){
                $name           = $this->product_name;
                $description    = $this->product_description;
            }else{
                $name           = $this->product_name_ar_qa;
                $description    = $this->product_description_ar_qa;
            }
        $product_image  = Product::find($this->product_id);
        return [
            'product_name'                       => $name,
            'product_image'                      => $product_image->image,
            'product_description'                => $description,
            'price'                              => $this->price,
            'quantity'                           => $this->quantity,


        ];
    }
}
