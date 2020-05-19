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

//рауты доступные всем пользователям сайта
Route::get('/', 'PostsController@index'); //главная страница
Route::get('/category/{category_name}', 'CategoryController@show_posts_by_category'); //вывод постов по категории
Route::get('/post/tag/{tag}','PostsController@show_posts_by_tag'); //вывод постов по тегу
Route::get('/post/{id}', 'PostsController@show_post'); //показать пост
Route::post('/submit_comment/{id}', 'PostsController@submit_comment'); //отправить комментарий
Route::get('/about', 'HomeController@about'); //страница About

//рауты доступные только админу
Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/control', 'ControlPanelController@index')->name('control'); //показать панель управления

    //НАСТРОЙКИ
    Route::post('/control/update_settings', 'ControlPanelController@update_settings'); //обновить общие настройки сайта
    Route::post('/control/update_social', 'ControlPanelController@update_social'); //обновить соц. сети
    Route::post('/control/change_user_type', 'ControlPanelController@change_user_type'); //сменить тип пользователя
    Route::post('/control/update_profile', 'ControlPanelController@update_profile'); //обновить настройки профиля

    //ПОСТЫ
    Route::get('/control/create_post', 'PostsController@show_create_post'); //показать страницу создания поста
    Route::post('/control/create_new_post', 'PostsController@create_post'); //сохранить пост
    Route::get('/control/posts', 'PostsController@show_list_of_posts'); //вывести посты в меню постов
    Route::get('/control/posts/date', 'PostsController@show_list_of_posts_by_date'); //вывести посты в меню постов по дате
    //vv пофиксить это vv//
    Route::post('/control/post_status/{id}/{status}', 'PostsController@change_post_visibility');
    //^^ пофиксить это ^^//
    Route::delete('/control/delete_post', 'PostsController@delete_post'); //удалить пост
    Route::get('/post/{id}/edit', 'PostsController@show_edit_post'); //показать страницу редактирования поста
    Route::post('/post/{id}/edit', 'PostsController@edit_post'); //сохранить изменения в посте
    Route::post('/post/change_comment_status','PostsController@change_comment_status'); //спрятать\показать\удалить комментарий
    Route::post('/post/upload_files','PostsController@upload_files'); //загрузить файлы, прикрепленные к посту
    Route::get('/clear_temp', 'PostsController@clear_temp'); //очистить папку temp с временными файлами
    Route::post('/delete_media', 'PostsController@delete_media'); //удалить прикрепленный файл из поста
    Route::post('/upload_files', 'PostsController@upload_files');
    Route::post('/control/pin_post','PostsController@pin_post'); //закрепить\открепить пост

    //МЕДИА
    Route::get('/control/media','MediaController@index'); //главная страница с медиа файлами
    Route::get('/control/media/{id}','MediaController@view_media'); //показать информацию о медиа\редактор медиа
    Route::post('/control/media/edit_media/{id}', 'MediaController@edit_media'); //сохранить изменения в файле
    Route::post('/control/media/remove_thumbnail/{id}', 'MediaController@remove_thumbnail'); //удалить thumbnail
    Route::post('/control/media/change_subs_status','MediaController@change_subs_status'); //спрятать\показать субтитры
    Route::post('/control/media/delete_subs','MediaController@delete_subs'); //удалить субтитры
    Route::post('/control/media/change_subs_display_name','MediaController@change_subs_display_name'); //сменить имя файла субтитров
    Route::post('/control/media/delete_media','MediaController@delete_media'); //удалить медиа

    //КАТЕГОРИИ
    Route::get('/control/categories', 'CategoryController@index'); //вывод списка категорий в панели управления
    Route::get('/control/categories/add', 'CategoryController@show_create_category'); //показать страницу создания категорий
    Route::post('/control/categories/add', 'CategoryController@create_category'); //создать категорию
    Route::get('/control/categories/edit/{id}','CategoryController@show_edit_category'); //редактировать категорию
    Route::post('/control/categories/edit/{id}','CategoryController@edit_category'); //сохранить изменения в категории
    Route::delete('/control/categories/delete','CategoryController@delete_category'); //удалить категорию

});

Auth::routes();


