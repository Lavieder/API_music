<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login','API\UserController@UserLogin');
Route::get('/login','API\UserController@UserLogin');
Route::post('/reg','API\UserController@UserRegister');
Route::get('/reg','API\UserController@UserRegister');
//, 'middleware' => 'checkUser'
Route::group(['prefix' => 'user'],function(){
    Route::post('/upface','API\UserController@UpdateFace');
    Route::get('/upface','API\UserController@UpdateFace');

    Route::post('/ugd','API\UserController@getUserGedan');
    Route::get('/ugd','API\UserController@getUserGedan');

    Route::post('/adfs','API\UserController@addReMyLikeSong');
    Route::get('/adfs','API\UserController@addReMyLikeSong');

    Route::post('/myfs','API\UserController@getMyLikeSong');
    Route::get('/myfs','API\UserController@getMyLikeSong');

    Route::post('/delfs','API\UserController@delMyLikeSong');
    Route::get('/delfs','API\UserController@delMyLikeSong');

    Route::post('/cmgd','API\UserController@createMyGedan');
    Route::get('/cmgd','API\UserController@createMyGedan');

    Route::post('/ctgd','API\UserController@collectGedan');
    Route::get('/ctgd','API\UserController@collectGedan');

    Route::post('/upui','API\UserController@updateUserInfo');
    Route::get('/upui','API\UserController@updateUserInfo');

    Route::post('/delgd','API\UserController@delGedan');
    Route::get('/delgd','API\UserController@delGedan');
});

Route::group(['prefix' => 'rec'],function(){
    Route::get('/banner','API\RmdController@recBanner');
    Route::get('/gedan','API\RmdController@recGedan');
    Route::get('/song','API\RmdController@recSong');
});

Route::group(['prefix' => 'singer'],function(){
    Route::get('/arealist','API\SingerController@areaClassAll');
    Route::get('/sexlist','API\SingerController@sexClassAll');
    Route::get('/sglist','API\SingerController@singerAll');
});

Route::group(['prefix' => 'sgdetail'],function(){
    Route::post('/detail','API\SingerDetailController@singerDetail');
    Route::get('/detail','API\SingerDetailController@singerDetail');
    Route::get('/lyrics','API\SingerDetailController@getLyrics');
    Route::post('/lyrics','API\SingerDetailController@getLyrics');
});

Route::group(['prefix' => 'song'],function(){
    Route::get('/today','API\SongController@todaySong');
    Route::post('increpn','API\SongController@increPlayNum');
    Route::get('/increpn','API\SongController@increPlayNum');
});

Route::group(['prefix' => 'album'],function(){
    Route::post('/aldetail','API\AlbumDetailController@albumDetail');
    Route::get('/aldetail','API\AlbumDetailController@albumDetail');
});

Route::group(['prefix' => 'gedan'],function(){
    Route::get('/gdlist','API\GedanController@gedanAll');
    Route::post('/gddetail','API\GedanController@gedanDetail');
    Route::get('/gddetail','API\GedanController@gedanDetail');
});

Route::group(['prefix' => 'rank'],function(){
    Route::get('/rklist','API\RankController@rankBoard');
    Route::post('/rkdetail','API\RankController@rankDetail');
    Route::get('/rkdetail','API\RankController@rankDetail');
    Route::get('/rksong','API\RankController@rankSong');
});

Route::group(['prefix' => 'search'],function(){
    Route::post('/word','API\SearchController@searchWord');
    Route::post('/shall','API\SearchController@allSuggest');
    Route::get('/word','API\SearchController@searchWord');
    Route::get('/hotwords','API\SearchController@HotWord');
    Route::get('/shall','API\SearchController@allSuggest');
});

