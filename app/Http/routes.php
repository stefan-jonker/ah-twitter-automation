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

$app->get('/', function() use ($app) {
    return "Lumen RESTful API By CoderExample (http://coderexample.com)";
});
 
$app->group(['namespace' => 'App\Http\Controllers'], function($app)
{
    $app->get('tweet','TweetController@index');
  
    $app->get('tweet/{id}','TweetController@gettweet');
      
    $app->post('tweet','TweetController@createTweet');
      
    $app->put('tweet/{id}','TweetController@updateTweet');
      
    $app->delete('tweet/{id}','TweetController@deleteTweet');
});

$app->get('admin', function ()  {
	return view('admin');
});
