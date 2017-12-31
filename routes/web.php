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

$router->group(['prefix' => 'oauth'], function () use ($router) {
    $router->get('{driver}', ['as' => 'oauth', 'uses' => 'Auth\OAuthController@redirectToProvider'
    ]);
    $router->get('{driver}/callback', ['as' => 'oauth.callback', 'uses' => 'Auth\OAuthController@handleProviderCallback']);
});

$router->get('{path:.*}', function () {
    return view('index');
});
