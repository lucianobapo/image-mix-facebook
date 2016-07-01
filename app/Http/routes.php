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

$app->get('/{post}', function ($post) use ($app) {
    $sitename = DB::select('select * from wp_options where `option_name` LIKE \'siteurl\'');
    dd($sitename);
    foreach ($sitename as $item) {
        if ($item->meta_key=="_yoast_wpseo_opengraph-description") $meta['description'] = $item->meta_value;
        if ($item->meta_key=="_yoast_wpseo_opengraph-title") $meta['title'] = $item->meta_value;
    }
    return Redirect::to(''.$post);
//    dd($post);
});
$app->get('/', function () use ($app) {
    $sitename = DB::select('select * from wp_options where `option_name` LIKE \'siteurl\'');
    dd($sitename);
    return $app->version();
});
$app->get('/phpinfo', function () use ($app) {
    return phpinfo();
});
$app->get('/file', ['uses'=>'ImageController@file']);
$app->get('/page', ['uses'=>'ImageController@page']);
$app->get('/pageCached/{id}/{key}/{name}', ['uses'=>'ImageController@pageCached']);
$app->get('/fileCached/{id}/{key}/{name}', ['uses'=>'ImageController@fileCached']);
