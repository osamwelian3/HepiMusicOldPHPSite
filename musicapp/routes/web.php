<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsVerifyEmail;

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

// Route::get('/', function () {
//     return redirect(route('login'));
// });
Route::get('/login', function () {
    return redirect(route('login'));
});
Route::get('logoutEmail', 'Auth\LoginController@logoutEmail')->name('logoutEmail');

Auth::routes(['verify' => true]);
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register', 'Auth\RegisterController@register');
Route::post('register', 'Auth\RegisterController@store')->name('register');

Route::get('forgot-password', 'Auth\ForgotPasswordController@showForgotPasswordForm')->name('forgot.password.get');
Route::post('forgot-password', 'Auth\ForgotPasswordController@submitForgotPasswordForm')->name('forgot.password.post'); 
Route::get('reset-password/{token}', 'Auth\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'Auth\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');

Route::get('/', 'HomeController@home')->name('home');
Route::get('home', 'HomeController@home')->name('home');
Route::get('/dashboard', 'HomeController@index')->name('dashboard');
// Route::get('/dashboard', 'HomeController@index')->name('dashboard')->middleware('auth', 'is_verify_email');

Route::get('/profile', 'HomeController@profile')->name('profile');
Route::post('/updateProfile', 'HomeController@updateProfile')->name('updateProfile');
Route::get('/profileDetail', 'HomeController@profileDetail')->name('profileDetail');

Route::get('user/verify/{token}', 'Auth\RegisterController@verifyAccount')->name('user.verify');

/* Admin Page */
// Route::middleware(['auth','is_admin'])->group(function () {
Route::group(['middleware' => ['auth']], function() {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UsersController@index')->name('users');
        Route::get('/index', 'UsersController@index')->name('users');
        Route::get('/get', 'UsersController@get')->name('users.list');
        Route::get('/detail', 'UsersController@detail')->name('users.detail');
        Route::post('/delete', 'UsersController@delete')->name('users.delete');
        Route::get('/referral', 'UsersController@referralUsers')->name('users.referral-users');
        Route::get('/referral-list', 'UsersController@referralList')->name('users.referral-list');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', 'CategoriesController@index')->name('categories');
        Route::get('/index', 'CategoriesController@index')->name('categories');
        Route::get('/get', 'CategoriesController@get')->name('categories.list');
        Route::get('/detail', 'CategoriesController@detail')->name('categories.detail');
        Route::post('/addupdate', 'CategoriesController@addupdate')->name('categories.addupdate');
        Route::post('/delete', 'CategoriesController@delete')->name('categories.delete');
        Route::post('/updatefield/{id}', 'CategoriesController@updatefield')->name('categories.updatefield');
        Route::post('/change_status', 'CategoriesController@changeStatus')->name('categories.change_status');
    });

    Route::group(['prefix' => 'songs'], function () {
        Route::get('/', 'SongsController@index')->name('songs');
        Route::get('/index', 'SongsController@index')->name('songs');
        Route::get('/get', 'SongsController@get')->name('songs.list');
        Route::get('/detail', 'SongsController@detail')->name('songs.detail');
        Route::post('/addupdate', 'SongsController@addupdate')->name('songs.addupdate');
        Route::post('/delete', 'SongsController@delete')->name('songs.delete');
        Route::post('/updatefield/{id}', 'SongsController@updatefield')->name('songs.updatefield');
        Route::post('/change_status', 'SongsController@changeStatus')->name('songs.change_status');
        Route::post('/edit_vote', 'SongsController@editVote')->name('songs.edit_vote');
    });
});

Route::post('/vote', 'SongsController@vote')->name('songs.vote');
Route::post('/filter', 'SongsController@filter')->name('songs.filter');
Route::post('/stream', 'SongsController@stream')->name('songs.stream');
Route::post('/like', 'SongsController@like')->name('songs.like');
Route::get('/liked', 'SongsController@liked')->name('songs.liked');
Route::post('/remove_like', 'SongsController@remove_like')->name('songs.remove_like');
Route::get('/listen/{file}', 'SongsController@listen')->name('songs.listen');

/* All Role Page */
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', 'PasswordController@index')->name('change-password');
    Route::post('/passwords/update', 'PasswordController@update')->name('passwords.update');
});