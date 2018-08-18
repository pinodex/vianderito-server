<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function termsOfService()
    {
        return view('docs.tos');
    }

    public function termsOfServiceHtml()
    {
        return view('docs.tos_html');
    }
}
