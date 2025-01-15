<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

//  ----------------------------------
// | Testing User data Api End Points |
//  ----------------------------------

Route::get('/fetch', [ApiController::class, 'get']); // fetch all user
Route::get('/fetch/{role}', [ApiController::class, 'getBasedonRole']); // fetch all user with specific role
Route::post('/register', [ApiController::class, 'store']); // Register new User
Route::delete('/delete/{id}', [ApiController::class, 'delete']); // Delete specific user
Route::put('/update/{id}', [ApiController::class, 'update']); // Update Specific User data

//  -----------------------------------
// | Testing Order data Api End Points |
//  -----------------------------------

Route::get('/orders/total', [ApiController::class, 'getTotalOrders']); // Total orders
Route::get('/orders/total-price', [ApiController::class, 'getTotalPrice']); // Total price
Route::get('/orders/year/{year}', [ApiController::class, 'getTotalOrdersByYear']); // Orders by year
Route::get('/orders/user/{userId}', [ApiController::class, 'getOrdersByUser']); // Orders by specific user