<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\ResponseWithHttpRequest;
use Illuminate\Http\Request;
use Session;

class ProductController extends Controller
{
    use ResponseWithHttpRequest;
    
    public function getProduct(Request $request)
    {
        try {
            if($request->language_type == '')
            $request->language_type = 'en';
            Session::put('language_type', $request->language_type);
            $products  = Product::where('status','active')->latest()->take(50)->get();
            if (!isset($products) || count($products) == 0) {
                return $this->sendFailed('PRODUCT NOT FOUND', 200);
            }
            return $this->sendSuccess('PRODUCT GET SUCCESSFULLY', ProductResource::collection($products));
        } catch (\Throwable $e) {
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
        
    }
    public function getSearchProduct(Request $request)
    {
        try {

            if($request->language_type == '')
            $request->language_type = 'en';
            Session::put('language_type', $request->language_type);
            if ($request->product_name == '') {
                return $this->sendFailed('PRODUCT NOT FOUND', 200);
            }
            $products  = Product::where('name', 'LIKE', "%" . $request->product_name . "%")->where('status','active')->latest()->take(100)->get();
            if (!isset($products) || count($products) == 0) {
                return $this->sendFailed('PRODUCT NOT FOUND', 200);
            }
            return $this->sendSuccess('PRODUCT GET SUCCESSFULLY', ProductResource::collection($products));
        } catch (\Throwable $e) {
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }
    public function getProductDetails(Request $request, $id)
    {
        try {
            if($request->language_type == '')
            $request->language_type = 'en';
            Session::put('language_type', $request->language_type);
            $products  = Product::find($id);
            if (!isset($products) || empty($products)) {
                return $this->sendFailed('PRODUCT NOT FOUND', 200);
            }
            return $this->sendSuccess('PRODUCT DETAIL GET SUCCESSFULLY', new ProductResource($products));
        } catch (\Throwable $e) {
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    
}
