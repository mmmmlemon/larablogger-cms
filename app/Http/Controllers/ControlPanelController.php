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
        $id = $settings->id;

        return view('user/control_panel', compact('site_title', 'site_subtitle', 'id'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'site_title'=>'required',
            'site_subtitle'=>'required'
        ]);

        $settings = App\Settings::all()->first();

        $settings->site_title = $request->get('site_title');
        $settings->site_subtitle = $request->get('site_subtitle');
        $settings->save();

        return redirect()->back();

        //dd($request->site_subtitle);
    }
 
}
