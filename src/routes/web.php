<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Support\Facades\Redis;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection: OK';
    } catch (\Exception $e) {
        return 'Database connection: FAILED - ' . $e->getMessage();
    }
});

Route::get('/redis-check', function () {
   try {
       Redis::ping();
       return 'Redis ping: OK';
   } catch (\Exception $e) {
       return 'Redis ping: FAILED - ' . $e->getMessage();
   }
});
