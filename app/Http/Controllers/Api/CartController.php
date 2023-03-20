<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\Traits\ResponseWithHttpRequest;
use Illuminate\Http\Request;
use Validator;
use Session;
class CartController extends Controller
{
    use ResponseWithHttpRequest;
    /**
     * Display a listing of the resource.
     */
    public function getCartData(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'device_token'             => 'required',
                    'language_type'            => 'required|In:en,ar-qa',
                    'user_id'                  => 'nullable|exists:users,id',
                ],
                [
                    'device_token.required'    => 'Device Token should be required'
                ]
            );
            if ($validator->fails()) {
                return $this->sendFailed($validator->errors()->first(), 200);
            }
            $data = [];
            $totalCartPrice = 0;
            $device_token = $request->device_token;
            if($request->user_id != null)
            Cart::whereDeviceToken($device_token)->update(['user_id' => $request->user_id]);            
            $unique_ids   = Cart::whereDeviceToken($device_token)->groupBy('unique_id')->pluck('unique_id');
            foreach($unique_ids as $unique_id_key => $unique_id):
            $cartItems    = Cart::with('product')->whereUniqueId($unique_id)->whereDeviceToken($device_token)->get();            
            $totalPrice   = $cartItems->sum(function ($cartItem) {
                return $cartItem->getTotalPrice();
            });
            $cartItems->total_price = $totalPrice;
            $data[$unique_id_key]['unique_id'] = $unique_id;
            $data[$unique_id_key]['data'] = $cartItems;
            endforeach;
            if(!isset($cartItems) || empty($cartItems->toArray()))
            return $this->sendFailed('DATA NOT FOUND IN CART',200);
            $result = \DB::table('products')
            ->join('carts', 'products.id', '=', 'carts.product_id')
            ->select('products.id', \DB::raw('SUM(products.price * carts.product_quantity) AS total_amount'))
            ->groupBy('products.id')
            ->where('device_token',$request->device_token)
            ->get();
            $total_amount = 0;
            foreach($result as $cart):
                $total_amount = $total_amount + $cart->total_amount;            
            endforeach;
            Session::put('language_type', $request->language_type);
            Session::put('totalCartPrice', $total_amount);

            return $this->sendSuccess('CART DATA GET SUCCESS',CartResource::collection($data));
        }catch(\Exception $e){
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    function addToCart(AddToCartRequest $request)
    {
        try {
            Session::put('language_type', $request->language_type);
            $products  = Product::find($request->product_id);
            if (!isset($products) || empty($products)) {
                return $this->sendFailed('PRODUCT ID NOT FOUND', 200);
            }
            // CHEK THIS PRODUCT ALREADY ADDED OR NOT IN CART
            $checkExist = Cart::where(['product_id' => $request->product_id, 'device_token' => $request->device_token, "unique_id" => $request->unique_id])->first();
            if($request->product_quantity == 0){
            if (isset($checkExist->id)) {
                $checkExist->delete();
              return $this->sendSuccess('PRODUCT DELETE IN CART SUCCESSFULLY');
            }
        }        
            if (empty($checkExist)) {
                $checkExist =  new Cart();
                $checkExist->product_quantity = $request->product_quantity;
            }else{
                $checkExist->product_quantity = $request->product_quantity;
                // $checkExist->increment('product_quantity');
            }
            $checkExist->fill($request->only('product_id', 'user_id', 'device_token','unique_id','address','address_type'));
            $checkExist->product_amount      = $products->price;
            $checkExist->total_amount        = $products->price * $checkExist->product_quantity;
            $checkExist->save();
            // $product_count   = Cart::where('device_token', $request->device_token)->sum('product_quantity');
            return $this->sendSuccess('PRODCUT ADDED IN CARD SUCCESSFULLY');
        } catch (\Throwable $e) {
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
     // PRODUCT DELETE IN CART      
      public function deleteProdcutInCart(Request $request)
      {
          try {
            $validator = Validator::make(
                $request->all(),
                [
                    'device_token'             => 'required',                    
                    // 'cart_id'                  => 'nullable|exists:carts,id',
                ],
                [
                    'device_token.required'    => 'Device Token should be required'
                ]
            );
            if ($validator->fails()) {
                return $this->sendFailed($validator->errors()->first(), 200);
            }
            //   $checkExist = Cart::where(['id' => $request->cart_id, 'device_token' => $request->device_token])->first();
              $checkExist = Cart::where(['device_token' => $request->device_token])->delete();
              if (!$checkExist) {
                  return $this->sendFailed('PRODUCT NOT FOUND', 200);
              }
            //   $checkExist->delete();
              return $this->sendSuccess('PRODUCT DELETE IN CART SUCCESSFULLY');
          } catch (\Throwable $e) {
              return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
          }
      }
}
