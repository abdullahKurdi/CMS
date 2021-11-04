<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'Frontend\IndexControllere@index')->name('frontend.index');

//Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/login', 'Frontend\Auth\LoginController@showLoginForm')->name('frontend.show.login');
//Route::post('/login', 'Frontend\Auth\LoginController@login')->name('frontend.login');
//Route::post('/logout', 'Frontend\Auth\LoginController@logout')->name('frontend.logout');
//Route::get('/register', 'Frontend\Auth\RegisterController@showRegistrationForm')->name('frontend.show.register');
//Route::post('/register', 'Frontend\Auth\RegisterController@register')->name('frontend.register');;
//Route::get('/password/reset', 'Frontend\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('/password/email', 'Frontend\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('/password/reset/{token}', 'Frontend\Auth\ResetPasswordController@showResetForm')->name('frontend.password.reset');
//Route::post('/password/reset', 'Frontend\Auth\ResetPasswordController@reset')->name('password.reset');
//Route::get('/password/confirm', 'Frontend\Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
//Route::post('/password/confirm', 'Frontend\Auth\ConfirmPasswordController@confirm');
//Route::get('/email/verify', 'Frontend\Auth\VerificationController@show')->name('verification.notice');
//Route::get('/email/verify/{id}/{hash}', 'Frontend\Auth\VerificationController@verify')->name('verification.verify');
//Route::post('/email/resend', 'Frontend\Auth\VerificationController@resend')->name('verification.resend');

Route::get('/login',                            ['as' => 'frontend.show.login',             'uses' => 'Frontend\Auth\LoginController@showLoginForm']);
Route::post('login',                            ['as' => 'frontend.login',                  'uses' => 'Frontend\Auth\LoginController@login']);
Route::post('logout',                           ['as' => 'frontend.logout',                 'uses' => 'Frontend\Auth\LoginController@logout']);
Route::get('register',                          ['as' => 'frontend.show.register',          'uses' => 'Frontend\Auth\RegisterController@showRegistrationForm']);
Route::post('register',                         ['as' => 'frontend.register',               'uses' => 'Frontend\Auth\RegisterController@register']);
Route::get('password/reset',                    ['as' => 'password.request',                'uses' => 'Frontend\Auth\ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email',                   ['as' => 'password.email',                  'uses' => 'Frontend\Auth\ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}',            ['as' => 'password.reset',                  'uses' => 'Frontend\Auth\ResetPasswordController@showResetForm']);
Route::post('password/reset',                   ['as' => 'password.update',                 'uses' => 'Frontend\Auth\ResetPasswordController@reset']);
Route::get('email/verify',                      ['as' => 'verification.notice',             'uses' => 'Frontend\Auth\VerificationController@show']);
Route::get('/email/verify/{id}/{hash}',         ['as' => 'verification.verify',             'uses' => 'Frontend\Auth\VerificationController@verify']);
Route::post('email/resend',                     ['as' => 'verification.resend',             'uses' => 'Frontend\Auth\VerificationController@resend']);

Route::group(['middleware'=>'verified'] , function (){

   Route::get('/dashboard',                         ['as'=>'frontend.dashboard',                'uses'=>'Frontend\UserController@index']);
    Route::get('/edit-info',                        ['as'=>'frontend.dashboard.edit-info',         'uses'=>'Frontend\UserController@edit_info']);
    Route::post('/update-info',                     ['as'=>'frontend.dashboard.update-info',          'uses'=>'Frontend\UserController@update_info']);
    Route::post('/update-password',                 ['as'=>'frontend.dashboard.update-password',          'uses'=>'Frontend\UserController@update_password']);

    Route::any('user/notification/get','Frontend\NotificationController@getNotification');
    Route::any('user/notification/read','Frontend\NotificationController@markAsRead');
    Route::any('user/notification/read/{id}','Frontend\NotificationController@markAsReadAndRedirect');

    Route::get('/post/create',                       ['as'=>'frontend.dashboard.create',         'uses'=>'Frontend\UserController@create']);
   Route::post('/post',                             ['as'=>'frontend.dashboard.store',          'uses'=>'Frontend\UserController@store']);
   Route::get('/post/{post}/edit-post',             ['as'=>'frontend.dashboard.edit',           'uses'=>'Frontend\UserController@edit']);
   Route::put('/post/{post}',                       ['as'=>'frontend.dashboard.update',         'uses'=>'Frontend\UserController@update']);
   Route::post('/post/delete-media/{post}',         ['as'=>'frontend.dashboard.media.destroy',  'uses'=>'Frontend\UserController@media_destroy']);
   Route::delete('/post/{post}',                    ['as'=>'frontend.dashboard.destroy',        'uses'=>'Frontend\UserController@destroy']);
   Route::get('/comments/',                         ['as'=>'frontend.dashboard.comment',        'uses'=>'Frontend\UserController@comment']);
   Route::get('/comments/{comment}/edit-comment',   ['as'=>'frontend.dashboard.edit.comment',   'uses'=>'Frontend\UserController@edit_comment']);
   Route::put('/comments/{comment}',                ['as'=>'frontend.dashboard.update.comment', 'uses'=>'Frontend\UserController@update_comment']);
   Route::delete('/comments/{comment}',             ['as'=>'frontend.dashboard.destroy.comment','uses'=>'Frontend\UserController@destroy_comment']);

});

Route::get('/contact-us', 'Frontend\IndexControllere@contact')->name('frontend.contact');
Route::post('/contact-us', 'Frontend\IndexControllere@do_contact')->name('frontend.do_contact');

Route::get('/category/{category}','Frontend\IndexControllere@category')->name('frontend.category.post');
Route::get('/archive/{date}','Frontend\IndexControllere@archive')->name('frontend.archive.post');
Route::get('/author/{username}','Frontend\IndexControllere@author')->name('frontend.author.post');


Route::get('/search', 'Frontend\IndexControllere@search')->name('frontend.search');
Route::get('/{post}', 'Frontend\IndexControllere@post_show')->name('post_show');
Route::post('/{post}', 'Frontend\IndexControllere@store_comment')->name('add_comment');

//admin routs
Route::group(['prefix'=>'admin'],function (){
    Route::get('/login', 'backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'backend\Auth\LoginController@login');
    Route::post('/logout', 'backend\Auth\LoginController@logout')->name('admin.logout');
    Route::get('/password/reset', 'backend\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/email', 'backend\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset/{token}', 'backend\Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('/password/reset', 'backend\Auth\ResetPasswordController@reset')->name('admin.password.update');
    Route::get('/password/confirm', 'backend\Auth\ConfirmPasswordController@showConfirmForm')->name('admin.password.confirm');
    Route::post('/password/confirm', 'backend\Auth\ConfirmPasswordController@confirm');
    Route::get('/email/verify', 'backend\Auth\VerificationController@show')->name('admin.verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'backend\Auth\VerificationController@verify')->name('admin.verification.verify');
    Route::post('/email/resend', 'backend\Auth\VerificationController@resend')->name('admin.verification.resend');
});
