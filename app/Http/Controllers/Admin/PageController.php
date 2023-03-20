<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function __construct()
    {
       
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $pages = Page::get();        
        $title = 'Pages List';
        $data = compact('pages','title',);
        return view('admin.pages.index',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($page_id)
    {
        $pages = Page::find($page_id);
        $title = 'Page Edit';
        return view('admin.pages.edit', compact('pages', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PageRequest $request,Page $category, $id)
    {
        $input = $request->validated();
        $category = Page::find($id);
        // dd($category);
        $category->update($input);
        return redirect()->route('admin.pages.index')->with('Success', 'Pages Updated successfully');
    }
}
