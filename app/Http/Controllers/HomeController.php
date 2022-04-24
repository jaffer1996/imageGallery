<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = Auth::id();
        $images = DB::table('images')->where('user_id', $user_id)->get();
        $available = DB::table('users')->where('id', $user_id)->pluck('available')[0];

        //$id = Auth::user()->id;

        return view('home', ['images' => $images, 'available' => $available]);
    }
}
