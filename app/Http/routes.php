<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$app->get('/redirect/{post}', function ($post) {
//    $sitename = DB::select('select * from wp_options where `option_name` LIKE \'siteurl\'');
//    return Redirect::to($sitename[0]->option_value.'/'.$post);
//});
$app->get('/', function () use ($app) {
    return $app->version();
});
$app->get('/phpinfo', function () use ($app) {
    return phpinfo();
});
$app->get('/file', ['uses'=>'ImageController@file']);
$app->get('/page', ['uses'=>'ImageController@page']);
$app->get('/pageCached/{id}/{key}/{name}', ['uses'=>'ImageController@pageCached']);
$app->get('/fileCached/{id}/{key}/{name}', ['uses'=>'ImageController@fileCached']);
$app->get('/redirect/{post}', ['uses'=>'ImageController@redirect']);
