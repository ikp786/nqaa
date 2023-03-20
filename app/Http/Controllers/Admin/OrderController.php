<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Traits\ResponseWithHttpRequest;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ResponseWithHttpRequest;
    /**
     * Display a listing of the resource.
     */

     function transaction(Request $request){
        if($request->ajax()) {
            $data = Order::query();
            if($request->status) {
                $data = $data->where('status', $request->status);
            }            
            if($request->from) {
                $data = $data->whereDate('created_at', '>=', $request->from);
            }
            if($request->to) {
                $data = $data->whereDate('created_at', '<=', $request->to);
            }
            $data = $data->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($query){
                    return '<a data-id="'.$query->id.'" data-transaction_number="'.$query->transaction_number.'" class="mx-3 rowedit " data-bs-toggle="modal"  data-bs-toggle="tooltip" data-bs-original-title="Edit">
                        <i class="fas fa-eye text-secondary"></i>
                    </a>';
                })->editColumn('status', function ($query) {                    
                    return $query->status;
                })->editColumn('created_at', function ($query) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d M, Y');
                })->editColumn('address_type', function ($query) {
                    return $query->address_type;                
                })->editColumn('mobile', function ($query) {
                    return $query->mobile;                                
                })->addColumn('price', function ($query) {
                    if(auth()->user()->type == 'Admin'){
                        return $query->price ?? 0;
                    }else{
                        return $query->merchant_price ?? 0;
                    }
                })->addColumn('merchant_price', function ($query) {
                    return $query->merchant_price ?? 0;
                })
                ->rawColumns(['status','created_at'])
                ->make(true);
        }
        return view('admin.transaction.index');
     }
    public function index(Request $request)
    {        
        if($request->ajax()) {
            $data = Order::query();
            if($request->status) {
                $data = $data->where('status', $request->status);
            }            
            if($request->from) {
                $data = $data->whereDate('created_at', '>=', $request->from);
            }
            if($request->to) {
                $data = $data->whereDate('created_at', '<=', $request->to);
            }
            $data = $data->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($query){
                    return '<a data-id="'.$query->id.'" data-order_number="'.$query->order_number.'" class="mx-3 rowedit product_show" data-bs-toggle="modal" data-bs-target="#modal-product" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                        <i class="fas fa-eye text-secondary"></i>
                    </a>';
                })->editColumn('status', function ($query) {
                    $status = '<select class="status form-control" data-id="'.$query->id.'">
                        <option value="Pending"';
                    if($query->status == "Pending") {
                        $status .= 'selected ';
                    }
                    $status.='>Pending</option>
                    <option value="Accepted" ';
                    if($query->status == "Accepted"){
                        $status .= 'selected ';
                    }
                        $status .= '>Accepted</option>
                        <option value="Rejected" ';
                    if($query->status == "Rejected"){
                        $status .= 'selected ';
                    }
                        $status .= '>Rejected</option>
                        <option value="Delivered" ';
                    if($query->status == "Delivered"){
                        $status .= 'selected ';
                    }
                        $status .= '>Delivered</option>
                    </select>';
                    return $status;
                })->editColumn('created_at', function ($query) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d M, Y');
                })->editColumn('address_type', function ($query) {
                    return $query->address_type;
                })->editColumn('address', function ($query) {
                    if(auth()->user()->type == 'Admin'){
                        return $query->address;
                    }else{
                        if($query->address_type == 'Home'){
                        return $query->address;
                        }else{
                        return "";
                        }
                    }                   

                })->editColumn('mobile', function ($query) {
                    return $query->mobile;
                })->addColumn('product', function ($query) {
                    $product = Product::select('name','image')->find($query->id);
                    return '<div class="d-flex px-2 py-1">
                        <div>
                            <img src="'.@$product->image.'" alt="" class="avatar avatar-sm me-3">
                        </div>
                        <div class="d-flex align-items-start flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">'.@$product->name.'</h6>                            
                        </div>
                    </div>';
                })->addColumn('quantity', function ($query) {
                    return @$query->quantity;

                })->addColumn('price', function ($query) {
                    if(auth()->user()->type == 'Admin'){
                        return $query->price ?? 0;
                    }else{
                        return $query->merchant_price ?? 0;
                    }
                    

                })->addColumn('merchant_price', function ($query) {
                    return $query->merchant_price ?? 0;
                })
                ->rawColumns(['status','mobile','quantity','action','created_at','product'])
                ->make(true);
        }
        return view('admin.orders.index');
    }


    public function changeStatus(Request $request)
    {
        $data = Order::where('id', $request->id)->update(['status' => $request->status]);
        if($data){
            return response()->json(['success' => true, 'message' => 'Status Updated Successfully.'], 200);
        }
        return response()->json(['success' => false, 'message' => 'Status Updating Failed.'], 200);
    }

    function sentWhatsappMessage(Request $request){
        try {
        $order_id   = $request->order_id;
        $url        = route('admin.orders.get_order_whatsapp',$order_id);
        $body       = 'You have a New Order! Please click on the link don&#39;t forgot to uplaod delivery location Images.'.$url.'Thanks';
        $mobile     = '+91'.$request->mobile;
        $reponse    = $this->sendWhatsappMessage($mobile,$body);        
        $order      = Order::find($order_id);
        $order->status = 'Accepted';
        $order->save();
        $user       = User::find($order->user_id);
        $title      = 'Order  Accepted';
        $body       = 'Dear '.$user->name. ' Your order has been Accepted.';
        $user_id    = $order->user_id;
            if($user->device_token != null)
        $response = $this->SendNotification($user->device_token, $title, $body, $user_id);
        return redirect()->back()->with('success','Order link sent success in whatsapp');
    } catch (\Throwable $th) {
        redirect()->back()->with('Failed',$th->getMessage(). 'On Line' .$th->getLine());
    }
    }

    function getOrderWhatsapp($order_id){
        $order = Order::find($order_id);
        if($order->status == 'Delivered')
        return "this order already Delivered";
        return view('front.orders.order_delivered',['order' => $order]);
    }


    function uploadOrderImage(Request $request,$order_id){        
        try {
        $this->validate($request,
        [
            'order_id'           => 'required|exists:orders,id',
            'image'              => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'image2'             => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048'
        ]);

        $order          = Order::find($order_id);        
        if($order->status == 'Delivered')
        return "this order already Delivered";

        if($request->hasFile('image')){
            $filename = rand(1000,9999).time() . '.' . $request->image->extension();
            $request->image->move(public_path('orders'), $filename);
            $image = $filename;
        }
        if($request->hasFile('image2')){
            $filename = rand(10000,99999).time() . '.' . $request->image2->extension();
            $request->image2->move(public_path('orders'), $filename);
            $image2 = $filename;
        }
        
        $order->image   = $image;
        $order->image2  = $image2;
        $order->status  = 'Delivered';
        $order->save();
        $user           = User::find($order->user_id);
        $title          = 'Order  delivered';
        $body           = 'Dear '.$user->name. ' Your order has been delivered.';
        $user_id        = $order->user_id;
        $device_token = "dQPEkYiJSOaQk9Hqbk-Eo9:APA91bGoPZFW-QGWBQb318J5aDubnt7t24cfgHwZDR29Vpex9S9OzrtiP2yLIBBTK2YlvmgLSeztPqVzjUg-_ahm7Ll4v6cS6wWWGbMqKLG24L0498bk52bGg_0gWgf7XaTdGrB1JQUs";
            if($user->device_token != null){
        $response = $this->SendNotification($user->device_token, $title, $body, $user_id);       
            }

    return redirect()->route('admin.orders.order_delivered')->with('Success','Order deliverd success.');
    } catch (\Throwable $th) {
        return redirect()->back()->with('Failed',$th->getMessage(). ' On Line '. $th->getLine());
    }
    }


    function orderDelivered(){
        return "Order Delivered success.";
    }

    function getOrderProduct(Request $request){
        try {            
        $data = OrderProduct::whereOrderId($request->id)->get();
            $table = '';
            foreach($data as $d):
                // $p = Product::find($d->product_id);
        if(auth()->user()->type == 'Admin'){
            $price  = $d->price;
        }else{
            $price  = $d->merchant_price;
        }
            $table .='
                <table class="table table-flush" id="datatable">
                <thead class="thead-light text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                <tr>    
                    <td>Photo</td>
                    <td>Product Name</td>
                    <td>Quanitity</td>
                    <td>Amount</td>
                </tr>
                </thead>
                <tbody>
                <td>
                <img src="' . $d->product->image . '" class="avatar lg rounded me-2" alt="profile-image">
                 </td>
                <td> '.$d->product->name.' </td>
                <td> '.$d->quantity.' </td>
                <td> '.$price.' </td>
                </tbody>
                </table>
        ';
    endforeach;
        return response()->json(['data' => $table, 'success' => true, 'statusCode' => 200, 'message' => 'Order detail successfully'], 200);

    } catch (\Throwable $th) {
        return response()->json(['success' => false, 'statusCode' => 422, 'message' => $th->getMessage() . ' on line ' . $th->getLine()], 200);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
