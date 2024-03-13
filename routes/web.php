<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminControllers;
use App\Http\Controllers\AuthControllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('home',[AuthControllers::class,'home'])->name('home');
    // Route pour le vote
    Route::post('vote',[AuthControllers::class,'vote'])->name('vote');

    // Apreès Vote
    Route::get('confirm',[AuthControllers::class,'confirm'])->name('confirm');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*********************** ZONE POUR LES ADMINS ***********************/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminControllers::class,'dashboard'])->name('dashboard');

    /******** ** ZONE DEDIEE AUX ROUTES POUR LES ELCTIONS ** ********/
    Route::get('/new-activities', [AdminControllers::class,'postActivites'])->name('new-activities');

    Route::get('activities', [AdminControllers::class,'activities'])->name('activities');

    Route::post('new-activities',[AdminControllers::class,'newActivities'])->name('post-activities');

    Route::get('overview/{id}', [AdminControllers::class,'overview'])->name('overview');

    Route::post('deleteAll/{id}', [AdminControllers::class,'deleteActivity'])->name('deleteAll');

    /******** ** ZONE DEDIEE AUX ROUTES POUR LES ELCTIONS ** ********/

    /********* ** ZONE DEDIEE AUX ROUTE POUR LES STRIPE ** *********/
    Route::get('/create-stripe-session/{id}', [AdminControllers::class,'createStripeSession'])->name('stripe');
    Route::get('/billing/success',[AdminControllers::class,'handlePaymentSuccess'])->name('billing.success');
    Route::get('/billing/cancel', [AdminControllers::class,'handlePaymentCancel'])->name('billing.cancel');
});

require __DIR__.'/auth.php';
