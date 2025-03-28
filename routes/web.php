<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstimationController;
use App\Http\Controllers\RoomController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    //Route::get('/profile', [ProfileController::class, 'edit'])->middleware('role:Admin')->name('profile.edit');
    //Route::patch('/profile', [ProfileController::class, 'update'])->middleware('role:Admin')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('role:Admin')->name('profile.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');


    Route::get('/prodotti', [ProductController::class, 'index'])->name('products.index');
    Route::get('/prodotti/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/prodotti/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/prodotti/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/prodotti/{product}/{room}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/prodotti/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/prodotti/{product}/delete', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories', [ProfileController::class, 'destroy'])->name('categories.index');
    Route::get('/quotations', [ProfileController::class, 'destroy'])->name('quotations.index');
    Route::get('/room/{room}', [ProductController::class, 'filterByRoom'])->where('room','[0-9]+')->name('room.products');
    Route::get('/quotation/add/{product}', [QuotationController::class, 'show'])->name('products.addtocart');
    Route::post('/quotation/add/{product}/{room}', [QuotationController::class, 'addToQuotation'])->name('quotation.add');
    Route::get('/quotation/{quotation}/view', [QuotationController::class, 'view'])->where('quotation','[0-9]+')->name('quotation.view');
    Route::get('/currentquotation', [QuotationController::class, 'viewPendingQuotation'])->name('quotation.pending.view');
    Route::get('/currentquotation', [QuotationController::class, 'viewGroupedQuotation'])->name('quotation.current.view');
    Route::put('/quotation/update', [QuotationController::class, 'updateQuotation'])->name('quotation.update');
    Route::get('/quotation/remove/{product}/{quotation}/{room}', [QuotationController::class, 'removeFromQuotation'])->name('quotation.remove');
    Route::get('/quotation/{quotation}/export-pdf', [QuotationController::class, 'exportQuotationToPdf'])->where('quotation','[0-9]+')->name('quotation.exportPdf');
    Route::get('/products/search', [ProductController::class, 'searchByRoom'])->name('products.searchByRoom');
    Route::post('/quotation/add-product', [QuotationController::class, 'addProductToQuotation'])->name('quotation.addProduct');
    Route::post('/quotations/{quotation}/upload-pdf', [QuotationController::class, 'uploadPdf'])->name('quotations.uploadPdf');
    Route::post('/quotations/{quotation}/remove-pdf', [QuotationController::class, 'removePdf'])->name('quotations.removePdf');
    Route::get('quotation/create', [QuotationController::class, 'create'])->name('quotation.create');
    Route::post('quotation/store', [QuotationController::class, 'store'])->name('quotation.store');
    Route::get('quotation/{quotation}/titleChange', [QuotationController::class, 'titleChange'])->name('quotation.titlechange');
    Route::post('quotation/{quotation}/updateTitle', [QuotationController::class, 'updateTitle'])->name('quotation.updateTitle');



    //quotations
    //Route::resource('room', RoomController::class);

    Route::get('/room', [RoomController::class, 'index'])->name('room.index');
    Route::get('/room/create', [RoomController::class, 'create'])->name('room.create');
    Route::post('/room/store', [RoomController::class, 'store'])->name('room.store');
    Route::get('/room/{room}/edit', [RoomController::class, 'edit'])->name('room.edit');
    Route::put('/room/{room}', [RoomController::class, 'update'])->name('room.update');
    Route::delete('/room/{room}', [RoomController::class, 'destroy'])->name('room.destroy');


    // Quotation list
    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations.index');    
    // Quotation edit
    Route::get('/quotations/{quotation}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    // Quotation view
    Route::get('/quotations/{quotation}/view', [QuotationController::class, 'view'])->name('quotations.view');
    Route::get('/quotations/{quotation}/confirm', [QuotationController::class, 'confirmQuotation'])->name('quotations.confirm');
    Route::get('/quotations/{quotation}/complete', [QuotationController::class, 'completeQuotation'])->name('quotations.complete');
    
    //estimation routes
    Route::get('/sensors', [EstimationController::class, 'sensors'])->name('estimations.sensor');    
    Route::get('/estimations', [EstimationController::class, 'index'])->name('estimations.index');
    Route::get('/estimations/create', [EstimationController::class, 'create'])->name('estimations.create');
    Route::post('/estimations/store', [EstimationController::class, 'store'])->name('estimations.store');
    Route::get('/estimations/{estimate}/edit', [EstimationController::class, 'edit'])->name('estimations.edit');
    Route::get('/estimations/{estimate}/show', [EstimationController::class, 'show'])->name('estimations.show');
    Route::put('/estimations/{estimate}', [EstimationController::class, 'update'])->name('estimations.update');
    Route::delete('/estimations/{estimate}/delete', [EstimationController::class, 'destroy'])->name('estimations.destroy');
    Route::get('/estimations/view', [EstimationController::class, 'fetch'])->name('estimations.fetch');

// Quotation update
    Route::put('/quotations/{quotation}', [QuotationController::class, 'update'])->name('quotations.update');

// Quotation delete
    Route::delete('/quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');

    // Route to delete a product from a quotation

    Route::delete('/quotations/{quotation}/product/{product}', [QuotationController::class, 'deleteProduct'])->name('quotations.product.delete');

    Route::post('/quotations/{quotation}/send-email', [QuotationController::class, 'sendQuotationEmail'])->name('quotations.send-email');
//Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
    Route::delete('/users/{user}/profile', [UserController::class, 'destroyProfile'])->name('users.profile.destroy');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/users/{user}/activity/{activity}', [UserController::class, 'activityShow'])->name('users.activity.show');
    Route::get('/users/{user}/activity/{activity}/delete', [UserController::class, 'activityDelete'])->name('users.activity.delete');
    Route::get('/users/{user}/login', [UserController::class, 'login'])->name('users.login');
    Route::get('/users/{user}/login-as', [UserController::class, 'loginAs'])->name('users.login-as');
    Route::get('/users/{user}/login-as-logout', [UserController::class, 'loginAsLogout'])->name('users.login-as-logout');
    Route::get('/users/{user}/login-as-logout-all', [UserController::class, 'loginAsLogoutAll'])->name('users.login-as-logout-all');
    Route::get('/users/{user}/login-as-logout-other', [UserController::class, 'loginAsLogoutOther'])->name('users.login-as-logout-other');
    Route::get('/users/{user}/login-as-logout-other-all', [UserController::class, 'loginAsLogoutOtherAll'])->name('users.login-as-logout-other-all');

});

Route::resources([
    'roles' => RoleController::class,
   // 'users' => UserController::class,
]);
require __DIR__.'/auth.php';
