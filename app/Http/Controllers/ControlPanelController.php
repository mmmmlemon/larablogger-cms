<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class ControlPanelController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $settings = App\Settings::all()->first();
        $site_title = $settings->site_title;
        $site_subtitle = $settings->site_subtitle;

        return view('user/control_panel', compact('site_title', 'site_subtitle'));
    }


    public function update(Request $request, $id)
    {
        
    }
 
}
