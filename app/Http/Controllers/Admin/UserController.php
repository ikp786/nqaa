<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use DataTables;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::whereType('User')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($query) {
                    return '<a data-id="' . $query->id . '" data-name="' . $query->name . '" data-email="' . $query->email . '" data-mobile="' . $query->mobile . '" data-status="' . $query->status . '" class="mx-3 rowedit" data-bs-toggle="modal" data-bs-target="#modal-create" data-bs-toggle="tooltip" >
                        </i>
                    </a>';
                })->editColumn('status', function ($query) {
                return '<label class="status-switch">
                    <input type="checkbox" class="changestatus" data-id="' . $query->id . '" data-on="Active" data-off="InActive" ' . ($query->status == 'active' ? "checked" : "") . '>
                    <span class="status-slider round"></span>
                </label>';
            })->editColumn('created_at', function ($query) {
                return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d M, Y');
            })->editColumn('email', function ($query) {
                return $query->email;
            })->addColumn('name', function ($query) {
                return $query->name;
            
        })
                ->rawColumns(['status', 'action', 'name', 'created_at'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::find(2); // Find the user you want to associate with topics
        $topicIds = [1, 2, 3]; // Array of topic IDs you want to associate with the user

        $user->topics()->attach($topicIds); // Save the relationships between the user and the topics

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    public function changeStatus(Request $request)
    {
        // dd($request->all());
        $data = User::find($request->id);
        $data->status = $request->status;
        $data->save();
        return response()->json(['success' => true, 'statusCode' => 200, 'message' => 'status change successfully'], 200);

    }
}
