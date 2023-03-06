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

Route::post('/pcp-revision-update', 'PCPRevisionUpdateController@PCPRevisionUpdate');
Route::post('/pcp-bom-copy', 'PCPRevisionUpdateController@PCPBOMCopy');
Route::post('/pcp-get-revision-update', 'PCPRevisionUpdateController@PCPGetRevisionUpdate');

    