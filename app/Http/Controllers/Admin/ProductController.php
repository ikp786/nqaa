<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        if($request->ajax()) { 
            $data = Product::get(); 
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($query){
                    return '                    
                    <a data-id="'.$query->id.'" data-name="'.$query->name.'"  data-description="'.$query->description.'" data-name_ar_qa="'.$query->name_ar_qa.'"  data-description_ar_qa="'.$query->description_ar_qa.'" data-price="'.$query->price.'" data-merchant_price="'.$query->merchant_price.'" class="mx-3 rowedit" data-bs-toggle="modal" data-bs-target="#modal-create" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                        <i class="fas fa-edit text-secondary"></i>
                    </a>
                    <a data-id="'.$query->id.'" class="delete" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                        <i class="fas fa-trash text-danger"></i>
                    </a>';

                })->editColumn('image', function ($query) {
                    return '<img src="' . $query->image . '" class="avatar lg rounded me-2" alt="profile-image">';

            })->editColumn('status', function ($query) {
                if($query->status == 'active'){
                    $status = 'badge-success';
                } else {
                    $status = 'badge-danger';
                }
                return '<label class="status-switch">
                <input type="checkbox" class="changestatus" data-id="' . $query->id . '" data-on="Active" data-off="InActive" ' . ($query->status == 'active' ? "checked" : "") . '>
                <span class="status-slider round"></span>
            </label>';
                })->editColumn('created_at', function ($query) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d M, Y');
                    })->editColumn('name', function ($query) {
                return $query->name;
                    })->editColumn('description', function ($query) {
                return $query->description;

            })->editColumn('name_ar_qa', function ($query) {
                return $query->name_ar_qa;
                    })->editColumn('description_ar_qa', function ($query) {
                return $query->description_ar_qa;
                
                    })->editColumn('price', function ($query) {
                return $query->price;                    
                })->editColumn('merchant_price', function ($query) {
                    return $query->merchant_price;
                        })
                ->rawColumns(['image','status','action','created_at'])
                ->make(true);
        }
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
         try {

            $input = $request->only('name','description','price','merchant_price','name_ar_qa','description_ar_qa');
            $id = [
                'id' => $request->id
            ];
            if($request->id != ''){
                
            }
            if($request->hasFile('image')){
                $filename = time() . '.' . $request->image->extension();            
                $request->image->move(public_path('images'), $filename);
                $input['image'] = $filename;
            }
            $insert = Product::updateOrCreate($id, $input);
            if($insert) {
                $message = $request->id ? 'Updated Successfully.' : 'Added Successfully.';
                return response()->json(['success' => true, 'statusCode' => 200, 'message' => $message], 200);
            }
            $message = $request->id ? 'Updating Failed.' : 'Adding Failed.';
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'statusCode' => 422, 'message' => $e->getMessage() . ' on line ' . $e->getLine()], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $Product): View
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $Product): View
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $Product): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Product $Product)
    {
        $data = Product::find($request->id);
        if($data){            
            $data->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Deletion Failed.']);
    }

    public function changeStatus(Request $request)
    {
        $data = Product::find($request->id);
        $data->status = $request->status;
        $data->save();
        return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'status change successfully'], 200);

    }

}
