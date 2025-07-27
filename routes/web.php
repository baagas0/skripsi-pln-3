<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScoringLv1Controller;
use App\Http\Controllers\ScoringLv3Controller;
use App\Http\Controllers\ScoringLv4Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/hash', function () {
    $pw = Hash::make('pln#573*');
    dd($pw);
});

// Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'registerPost'])->name('register');
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginPost'])->name('login');
});


Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        // return view('welcome');
        return redirect()->route('dashboard');
    })->name('home');


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/user/update-email', [DashboardController::class, 'updateEmail'])->name('user.update-email');

    routeController('/diklat-planning', 'DiklatPlanningController');
    routeController('/diklat', 'DiklatController');
    routeController('/vendor', 'VendorController');
    routeController('/employee', 'EmployeeController');
    routeController('/proccess-vip', 'ProccessVipController');
    routeController('/scorring', 'ScoringController');
    routeController('/scorring/progress', 'ScoringProgressController');
    routeController('/scorring/lv1/{id}', 'ScoringLv1Controller');
    routeController('/scorring/lv2/{id}', 'ScoringLv2Controller');
    routeController('/scorring/lv3/{id}', 'ScoringLv3Controller');
    routeController('/scorring/lv4/{id}', 'ScoringLv4Controller');
    routeController('/scorring/lv5/{id}', 'ScoringLv5Controller');
    routeController('/certificate', 'CertificateController');
    routeController('/report', 'ReportController');
    routeController('/activity', 'ActivityController');
    routeController('/user', 'UserController');
    routeController('/unit', 'UnitController');
    routeController('/area', 'AreaController');

    // Add specific route for PDF download with levels parameter
    Route::get('/report/download-pdf/{diklatId}', 'App\Http\Controllers\ReportController@getDownloadPdf')->name('report.download-pdf');
    
    // Employee password setting route
    Route::post('/employee/set-password/{id}', [App\Http\Controllers\EmployeeController::class, 'postSetPassword'])
        ->name('employee.set-password')
        ->middleware('auth');
});

Route::post('/form/check/{hash_slug}', [LoginController::class, 'postCheckEmployee'])->name('form.check');

Route::get('form/lv1/{hash_slug}', [ScoringLv1Controller::class, 'getWelcomeForm'])->name('form.lv1.welcome');
Route::get('form/lv1/employee/login', [ScoringLv1Controller::class, 'getEmployeeLoginForm'])->name('form.lv1.employee.login');
Route::get('form/lv1/{hash_slug}/form', [ScoringLv1Controller::class, 'getForm'])->name('form.lv1.form');
Route::post('form/lv1/{hash_slug}/submit', [ScoringLv1Controller::class, 'postSubmit'])->name('form.lv1.submit');

Route::get('form/lv3/{hash_slug}/form', [ScoringLv3Controller::class, 'getForm'])->name('form.lv3.form');
Route::post('form/lv3/{id}/submit', [ScoringLv3Controller::class, 'postStore'])->name('form.lv3.submit');

Route::get('form/lv4/{hash_slug}/form', [ScoringLv4Controller::class, 'getForm'])->name('form.lv4.form');
Route::post('form/lv4/{id}/submit', [ScoringLv4Controller::class, 'postStore'])->name('form.lv4.submit');
