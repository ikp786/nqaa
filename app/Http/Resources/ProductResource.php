<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Session;
class ProductResource extends JsonResource
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
                $name           = $this->name;
                $description    = $this->description;
            }else{
                $name           = $this->name_ar_qa;
                $description    = $this->description_ar_qa;
            }
        return [
            'id'                => $this->id,
            'name'              => $name,
            'description'       => $description,
            'price'             => $this->price,            
            'images'            => $this->image
        ];
    }
}
