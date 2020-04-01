<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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

Route::get('/', 'PostsController@index');
Route::get('/category/{category_name}', 'CategoryController@show_posts_by_category');

Route::get('/post/tag/{tag}','PostsController@show_posts_by_tag');
Route::get('/post/{id}', 'PostsController@show_post');


Route::get('/about', function () {
    return view('about');
});

Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/control', 'ControlPanelController@index')->name('control');
    Route::post('/control/update_settings', 'ControlPanelController@update_settings');
    Route::post('/control/update_social', 'ControlPanelController@update_social');
    Route::post('/control/change_user_type', 'ControlPanelController@change_user_type');
    Route::post('/control/update_profile', 'ControlPanelController@update_profile');
    Route::get('/control/create_post', 'PostsController@show_create_post');

    Route::post('/control/control_panel/create_new_post', 'PostsController@create_post');

    Route::get('/control/posts', function(){
        $posts = App\Post::orderBy('date','desc')->orderBy('id','desc')->paginate(10);
        $page='normal';
        return view('control_panel/posts/posts', compact('posts','page'));
    
    });
    Route::get('/control/posts/date', function(){
        $posts = App\Post::orderBy('date','asc')->paginate(10);
        $page = 'date_desc';
        return view('control_panel/posts/posts', compact('posts', 'page'));
    });
    Route::get('/control/post_status/{id}/{status}', 'PostsController@change_post_status');
    Route::post('/control/post_status/{id}/{status}', 'PostsController@change_post_status');
    Route::delete('/control/delete_post', 'PostsController@delete_post');
    Route::get('/post/{id}/edit', 'PostsController@show_edit_post');
    Route::post('/post/{id}/edit', 'PostsController@edit_post');
    Route::get('/control/categories', 'CategoryController@index');
    Route::get('/control/categories/add', function(){
        return view('control_panel/categories/add_category');
    });
    Route::post('/control/categories/add', 'CategoryController@create_category');

    Route::get('/control/categories/edit/{id}','CategoryController@edit_category');

    Route::post('/control/categories/edit/{id}','CategoryController@save_category');

    Route::delete('/control/categories/delete','CategoryController@delete_category');

    Route::post('/post/hide_comment','PostsController@hide_comment');
    
    Route::post('/post/show_comment','PostsController@show_comment');

});



Route::post('/submit_comment/{id}', 'PostsController@submit_comment');



Auth::routes();


