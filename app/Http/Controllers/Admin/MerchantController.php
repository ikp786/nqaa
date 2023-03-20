<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreMerchantRequest;
use App\Http\Requests\UpdateMerchantRequest;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Validator;
use Hash;

class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        if($request->ajax()) { 
            $data = User::whereType('Merchant')->get(); 
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($query){
                    return '<a data-id="'.$query->id.'" data-name="'.$query->name.'"  data-email="'.$query->email.'" data-mobile="'.$query->mobile.'" class="mx-3 rowedit" data-bs-toggle="modal" data-bs-target="#modal-create" data-bs-toggle="tooltip" data-bs-original-title="Edit">
                        <i class="fas fa-edit text-secondary"></i>
                    </a>
                    <a data-id="'.$query->id.'" class="delete" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                        <i class="fas fa-trash text-danger"></i>
                    </a>
                    ';
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
                    })->editColumn('email', function ($query) {
                return $query->email;
                    })->editColumn('mobile', function ($query) {
                return $query->mobile;
                    })
                ->rawColumns(['status','action','created_at'])
                ->make(true);
        }
        return view('admin.merchants.index');
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
    public function store(StoreMerchantRequest $request)
    {
         try {
          
            $input = $request->only('name','email','mobile');
            $id = [
                'id' => $request->id
            ];
            if($request->password != ''){
                $input['password'] = Hash::make($request->password);
            }
            $input['type'] = 'Merchant';
            $insert = User::updateOrCreate($id, $input);
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
    public function show(User $user): View
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMerchantRequest $request, User $user): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $data = User::find($request->id);
        if($data){            
            $data->delete();
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Deletion Failed.']);
    }

    public function changeStatus(Request $request)
    {
        $data = User::find($request->id);
        $data->status = $request->status;
        $data->save();
        return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'status change successfully'], 200);

    }

}
