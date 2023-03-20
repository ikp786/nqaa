<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Traits\ResponseWithHttpRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseWithHttpRequest;
    function createOrder(Request $request){

        try {

            $validator = \Validator::make(
                $request->all(),
                [
                    'transaction_number'             => 'required',
                ]
            );
            if ($validator->fails()) {
                return $this->sendFailed($validator->errors()->first(), 200);
            }            
            $unique_ids = auth()->user()->cart()->groupBy('unique_id')->pluck('unique_id');
            foreach($unique_ids as $unique_id_key => $unique_id):
                $orderno = $this->generateRandomString(6);
            $cartItems    = auth()->user()->cart()->with('product')->whereUniqueId($unique_id)->get();            
            // GET TOTAL PRICE
            $totalPrice   = $cartItems->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->product_quantity;
            });
            // GET MERCHANT PRICE
            $totalMerchantPrice   = $cartItems->sum(function ($cartItem) {
                return $cartItem->product->merchant_price * $cartItem->product_quantity;
            });             
            \DB::beginTransaction();
            $quantity = auth()->user()->cart()->whereUniqueId($unique_id)->sum('product_quantity');            
            $order                              = new Order();
            $order->order_number                = $orderno;
            $order->transaction_number          = $request->transaction_number;
            $order->mobile                      = auth()->user()->mobile;
            $order->price                       = $totalPrice;
            $order->quantity                    = $quantity;
            $order->merchant_price              = $totalMerchantPrice;
            $order->address                     = @$cartItems[0]->address;
            $order->address_type                = @$cartItems[0]->address_type;
            $order->status                      = 'Pending';
            $order                              = auth()->user()->orders()->save($order);
            foreach($cartItems as $cartData):            
            $orderProducts                              = new OrderProduct();
            $orderProducts->order_id                    = $order->id;
            $orderProducts->order_number                = $orderno;
            $orderProducts->quantity                    = $cartData->product_quantity;
            $orderProducts->product_id                  = $cartData->product->id;
            $orderProducts->product_name                = $cartData->product->name;
            $orderProducts->product_name_ar_qa          = $cartData->product->name_ar_qa;
            $orderProducts->product_description         = $cartData->product->id;
            $orderProducts->product_description_ar_qa   = $cartData->product->description_ar_qa;
            $orderProducts->price                       = $cartData->product->price * $cartData->product_quantity;
            $orderProducts->merchant_price              = $cartData->product->merchant_price * $cartData->product_quantity;
            $orderProducts                              = auth()->user()->orderProducts()->save($orderProducts);            
            endforeach;
        \DB::commit();
        endforeach;
        return $this->sendSuccess('ORDER BOOKED SUCCESSFULLY');

        }catch(\Throwable $e){
            \DB::rollBack();
            return $this->sendFailed($e->getMessage() . ' on line ' . $e->getLine(), 400);
        }
    }

    function getOrderList(Request $request){

        \Session::put('language_type', $request->language_type);
        $orders = auth()->user()->orders()->latest()->get();        
        return $this->sendSuccess('ORDER LIST GET SUCCESSFULLY',OrderResource::collection($orders));
    }

    function getOrderDetail(Request $request,$id){

        $order = auth()->user()->orders()->with('orderProducts')->find($id);
        \Session::put('language_type', $request->language_type);
        return $this->sendSuccess('ORDER LIST GET SUCCESSFULLY',new OrderDetailResource($order));

    }
}
