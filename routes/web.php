<?php

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
Route::group(['prefix'=>'admin','namespace'=>'Admin'
    //, 'middleware'	=>	'admin'
], function(){
    Route::get('/', 'DashboardController@index');
    Route::resource('/categories', 'CategoriesController');
    Route::resource('/tags', 'TagsController');
    Route::resource('/users', 'UsersController');
    Route::resource('/posts', 'PostsController');
//    Route::get('/comments', 'CommentsController@index');
//    Route::get('/comments/toggle/{id}', 'CommentsController@toggle');
//    Route::delete('/comments/{id}/destroy', 'CommentsController@destroy')->name('comments.destroy');
//    Route::resource('/subscribers', 'SubscribersController');
});



