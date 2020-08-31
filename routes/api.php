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
//, 'middleware' => 'checkUser'
Route::group(['prefix' => 'user'],function(){
    Route::post('/ugd','API\UserController@getUserGedan');
    Route::get('/ugd','API\UserController@getUserGedan');
    Route::post('/cmgd','API\UserController@createMyGedan');
    Route::get('/cmgd','API\UserController@createMyGedan');
    Route::post('/upui','API\UserController@updateUserInfo');
    Route::get('/upui','API\UserController@updateUserInfo');
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
});

Route::group(['prefix' => 'search'],function(){
    Route::post('/word','API\SearchController@searchWord');
    Route::post('/shall','API\SearchController@allSuggest');
    Route::get('/word','API\SearchController@searchWord');
    Route::get('/hotwords','API\SearchController@HotWord');
    Route::get('/shall','API\SearchController@allSuggest');
});

