<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
//use File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/delete', function (Request $request) {
    
    $user_id = Auth::id();
    $available = DB::table('users')->where('id', $user_id)->pluck('available')[0];
    $id = $request->input('id');
    $name = $request->input('name');
    DB::table('images')->where('id', $id)->delete();
    $size = File::size(public_path() . '/images/' . $name);
    $size = round($size/1000000,1);
    File::delete(public_path() . '/images/' . $name);

    $newAvailable = $available + $size;

    if($newAvailable > 10){
        $newAvailable = 10;
    }

    DB::table('users')->where('id', $user_id)->update([
        'available' => $newAvailable
    ]);
    return redirect('/home');
});

Route::post('/uploadImage' , function(Request $request){

        if(!$request->file('image')){
            return redirect('/home');
        }
        $user_id = Auth::id();
        $available = DB::table('users')->where('id', $user_id)->pluck('available')[0];
        $file = $request->file('image');
        /*$destinationPath = 'checksize';
        $file->move($destinationPath, $file->getClientOriginalName());
        $size = File::size(public_path() . '/checksize/' . $file->getClientOriginalName());
        $size = round($size/1000000,1);
        File::delete(public_path() . '/checksize/' . $file->getClientOriginalName());*/
        $size = $file->getSize();
        $size = round($size/1000000,1);
        if($size > $available){
            return redirect('/home')->with('message', 'Limit has been reached');
        }

        $id = DB::table('images')->insertGetId([
            'user_id' => $user_id
        ]);

        DB::table('images')->where('id', $id)->update([
            'name' => $id . '.' . $file->getClientOriginalExtension()
        ]);

        $destinationPath = 'images';

        $file->move($destinationPath, $id . '.' . $file->getClientOriginalExtension());

        DB::table('users')->where('id', $user_id)->update([
            'available' => $available - $size
        ]);
        return redirect('/home');
});
