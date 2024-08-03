<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['hello' => 'word'];
});

// Route::get(uri:'login',action:static fn ()=> User::firstOrFail()->createToken('auth_token')->plainTextToken)->name('login');


require __DIR__.'/auth.php';
