<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return redirect()->to('/dashboard');
});


//
// Api
// ----------------------------
Route::group(['middleware' => [], 'prefix' => 'api'], function () {
    Route::get('/', function () {
        return view('api.index');
    });

    Route::controller('user', 'Api\UserController');
    Route::controller('timeline', 'Api\TimelineController');
    Route::controller('topic', 'Api\TopicController');
    Route::controller('notice', 'Api\NoticeController');
    Route::controller('wechat', 'Api\WeChatController');
    Route::controller('system', 'Api\SystemController');
    Route::controller('feedBack', 'Api\feedBackController');

});

Route::group(['middleware' => [], 'prefix' => 'style'], function () {

    Route::controller('list', 'Style\ListController');
    Route::controller('eval', 'Style\EvalController');
});

//
// Dashboard
// ----------------------------
Route::get('/dashboard/log-in', 'Dashboard\HomeController@getLogIn');
Route::post('/dashboard/log-in', 'Dashboard\HomeController@postLogIn');
Route::get('/dashboard/log-out', 'Dashboard\HomeController@getLogOut');
Route::group(['middleware' => ['auth.admin'], 'prefix' => 'dashboard'], function () {
    Route::controller('data', 'Dashboard\DataController');
    Route::controller('timeline', 'Dashboard\TimelineController');
    Route::controller('user', 'Dashboard\UserController');
    Route::controller('feedback', 'Dashboard\FeedBackController');
    Route::controller('topic', 'Dashboard\TopicController');
    Route::controller('report', 'Dashboard\ReportController');
    Route::controller('setting', 'Dashboard\SettingController');
    Route::controller('trend', 'Dashboard\TrendController');
    Route::controller('/', 'Dashboard\HomeController');
});

