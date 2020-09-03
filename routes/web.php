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

Route::feeds();

//SEARCH
Route::post('/simple_search', 'ControlPanelController@simple_search');
Route::get('/full_search','ControlPanelController@full_search');

//COOKIES
Route::post('/set_view_type','HomeController@set_view_type');
Route::post('/change_view_type','HomeController@change_view_type');
Route::get('/check_cookies_accepted', 'HomeController@check_cookies_accepted');
Route::post('/set_cookies_accepted', 'HomeController@set_cookies_accepted');


//routes available for everyone
Route::get('/', 'HomeController@index'); //index page
Route::get('/category/{category_name}', 'CategoryController@show_posts_by_category'); //view posts by category
Route::get('/post/tag/{tag}','PostsController@show_posts_by_tag'); //view post by tag
Route::get('/post/{id}', 'PostsController@show_post'); //view post
Route::post('/submit_comment/{id}', 'PostsController@submit_comment'); //submit comment
Route::get('/about', 'HomeController@view_about_page'); //view About page
Route::post('/send_feedback','FeedbackController@mail'); //submit Feedback e-mail 
Route::post('/control/increment_view_count', 'MediaController@increment_view_count'); //increment view count for video



//routes for logged in users
Route::group(['middleware' => ['auth']], function() {
    Route::get('/control', 'ControlPanelController@show_control_panel'); //view Control Panel
    Route::post('/control/update_profile', 'ControlPanelController@update_profile'); //update user profile
});

//routes for Admin
Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/control/edit_about','ControlPanelController@show_edit_about'); //edit About page
    Route::post('/control/save_about','ControlPanelController@save_about'); //save changes in About

    //SETTINGS
    Route::post('/control/update_settings', 'ControlPanelController@update_settings'); //update general site settings
    Route::post('/control/update_social', 'ControlPanelController@update_social'); //update social media
    Route::post('/control/change_user_type', 'ControlPanelController@change_user_type'); //change user type

    Route::post('/control/update_design', 'ControlPanelController@update_design'); //update design settings

    //POSTS
    Route::get('/control/create_post', 'PostsController@view_add_post_page'); //view Create Post page
    Route::post('/control/create_new_post', 'PostsController@create_post'); //save post
    Route::get('/control/posts', 'PostsController@view_posts_page'); //view all posts by date (desc)
    Route::get('/control/posts/date', 'PostsController@view_posts_page_asc'); //view all posts by date (asc)
    Route::post('/control/post_status/{id}/{status}', 'PostsController@change_post_visibility'); //change post visibility
    Route::delete('/control/delete_post', 'PostsController@delete_post'); //delete post
    Route::get('/post/{id}/edit', 'PostsController@view_edit_post_page'); //view Edit Post page
    Route::post('/post/{id}/edit', 'PostsController@edit_post'); //save changes in post
    Route::post('/post/upload_files','PostsController@upload_files_to_temp_folder'); //upload files attached to the post
    Route::get('/clear_temp', 'PostsController@clear_temp_folder'); //clear temp folder
    Route::post('/delete_media', 'PostsController@delete_file_from_post'); //delete attached file from post
    Route::post('/control/pin_post','PostsController@pin_post'); //pin/unpin post

    //MEDIA
    Route::get('/control/media/upload_file', 'MediaController@view_upload_file_page'); //upload a file
    Route::get('/control/media','MediaController@view_media_browser'); //view media browser
    Route::post('/control/find_post', 'MediaController@find_post'); //find post (for file upload)
    Route::get('/control/media/{id}','MediaController@view_media'); //view edit media page
    Route::post('/control/media/edit_media/{id}', 'MediaController@edit_media'); //save chnages in media
    Route::post('/control/media/remove_thumbnail/{id}', 'MediaController@remove_thumbnail_from_media'); //delete thumbnail
    Route::post('/control/media/change_subs_status','MediaController@change_subtitles_visibility'); //show/hide subtitles
    Route::post('/control/media/delete_subs','MediaController@delete_subtitles'); //delete subtitles
    Route::post('/control/media/change_subs_display_name','MediaController@change_subtitles_display_name'); //change subtitles display name
    Route::post('/control/media/delete_media','MediaController@delete_media'); //delete media
    Route::post('/control/save_uploaded_media', 'MediaController@save_uploaded_media_files'); //save manually uploaded media files


    //CATEGORIES
    Route::get('/control/categories', 'CategoryController@view_categories_page'); //view category list in control panel
    Route::get('/control/categories/add', 'CategoryController@view_create_category_page'); //view create category page
    Route::post('/control/categories/add', 'CategoryController@create_category'); //create category
    Route::get('/control/categories/edit/{id}','CategoryController@view_edit_category_page'); //view edit category page
    Route::post('/control/categories/edit/{id}','CategoryController@edit_category'); //save changes in category
    Route::delete('/control/categories/delete','CategoryController@delete_category'); //delete category
    Route::post('/control/categories/raise','CategoryController@raise_category'); //raise category in list
    Route::post('/control/categories/lower','CategoryController@lower_category'); //lower category in list

    //COMMENTS
    Route::get('/control/comments','ControlPanelController@view_comments'); //view comments
    Route::post('/post/change_comment_status','PostsController@change_comment_status'); //show/hide/delete comment
});



//enable/disable register route
Auth::routes([
    'register' => true,
]
);