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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('oauth/{driver}', [
    'as' => 'oauth', 'uses' => 'Auth\OAuthController@redirectToProvider'
]);

$router->get('oauth/{driver}/callback', [
    'as' => 'oauth.callback', 'uses' => 'Auth\OAuthController@handleProviderCallback'
]);

$router->get('{path}', function () {
    return view('index');
    // where('path', '(.*)')
});

$router->get('password/reset/{token}', ['as' => 'password.reset', function () {
    return view('index');
}]);
