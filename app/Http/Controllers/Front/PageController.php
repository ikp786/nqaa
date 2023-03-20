<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    function termsAndConditions()
    {
        $title  = 'terms-and-conditions';
        $pages  = Page::where('page_slug', 'terms-and-conditions')->first();
        $data   = compact('title', 'pages');
        return view('front.pages.terms-and-conditions', $data);
    }

    function privacyPolicy()
    {
        $title  = 'Privacy Policy';
        $pages  = Page::where('page_slug', 'privacy-policy')->first();
        //$pages  = Page::all();
        $data   = compact('title', 'pages');
        return view('front.pages.privacy-policy', $data);
    }

}
