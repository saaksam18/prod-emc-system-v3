<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

use App\Http\Controllers\BasicData\AddMotorbike;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\operation\AddWPCustomer;
use App\Http\Controllers\operation\AddVisaCustomer;
use App\Http\Controllers\operation\AddCustomer;
use App\Http\Controllers\operation\AddMotorRental;
use App\Http\Controllers\operation\ExchangeMotorbike;
use App\Http\Controllers\operation\MotorbikeController;
use App\Http\Controllers\operation\RentalController;
use App\Http\Controllers\operation\ExchangeDepositController;

use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\Auth\LoginController;
use App\Exports\Exports;

$controller_path = 'App\Http\Controllers';
Auth::routes();
Route::get('configs/clear/saak101820', function () {
    Artisan::call('optimize:clear');
    return 'Application cache is clean';
});

Route::get('/', function () {
    //return view('welcome');
    return redirect('/login');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::get('logout', [LogoutController::class, 'perform'])->name('logout.perform');

    $controller_path = 'App\Http\Controllers';


    // Main Page Route
    Route::get('home', $controller_path . '\dashboard\Analytics@index')->name('home');

    // layout operation
    // AddCustomer
    Route::get('customers', [AddCustomer::class, 'index'])->name('customers.index');
    Route::get('customers/{customer}', [AddCustomer::class, 'show'])->name('customers.show');
    Route::get('customers/create', [AddCustomer::class, 'create'])->name('customers.create');
    Route::post('customers', [AddCustomer::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}/edit', [AddCustomer::class, 'edit'])->name('customers.edit');
    Route::put('customers/{customer}/update', [AddCustomer::class, 'update'])->name('customers.update');
    Route::delete('customers/{customer}', [AddCustomer::class, 'destroy'])->name('customers.destroy');
    Route::get('/export-contact', function () {
        $export = new Exports();
    
        return Excel::download($export, 'customer.csv');
    })->name('export-customer');

    //contact
    Route::get('contacts/{contacts}/edit', [AddCustomer::class, 'editContact'])->name('contacts.edit');
    Route::put('contacts/{contacts}/update', [AddCustomer::class, 'updateContact'])->name('contacts.update');
    
    // motorbike
    Route::get('motorbikes', [AddMotorbike::class, 'index'])->name('motorbikes.index');
    Route::get('motorbikes/{motorbike}', [AddMotorbike::class, 'show'])->name('motorbikes.show');
    Route::get('print-stock', [AddMotorbike::class, 'printStock'])->name('print-stock');
    Route::get('motor/create', [AddMotorbike::class, 'create'])->name('motorbikes.create');
    Route::post('motorbikes', [AddMotorbike::class, 'store'])->name('motorbikes.store');
    Route::get('motorbikes/{motorbike}/edit', [AddMotorbike::class, 'edit'])->name('motorbikes.edit');
    Route::get('motorbikes/{motorbike}/sold-stolen', [AddMotorbike::class, 'soldStolen'])->name('motorbikes.sold-stolen');
    Route::put('motorbikes/{motorbike}/sold-stolen-update', [AddMotorbike::class, 'soldStolenContract'])->name('motorbikes.soldStolenContract');
    Route::put('motorbikes/{motorbike}/update', [AddMotorbike::class, 'update'])->name('motorbikes.update');
    Route::delete('motorbikes/{motorbike}', [AddMotorbike::class, 'destroy'])->name('motorbikes.destroy');
    // end motorbike

    /* motorbike rental */
    Route::resource('rentals', AddMotorRental::class);
    Route::get('rentals/{rental}/add-coming-date', [AddMotorRental::class, 'addComingDate'])->name('rentals.add-coming-date');
    Route::put('rentals/add-coming-date/{rental}', [AddMotorRental::class, 'updateComningDate'])->name('rentals.update-coming-date');
    // exchange motorbike
    Route::get('rentals/{rental}/changeMotorEdit', [AddMotorRental::class, 'changeMotorEdit'])->name('rentals.changeMotorEdit');
    Route::put('rentals/exchange/{rental}', [AddMotorRental::class, 'exchangeMotor'])->name('rentals.exchange-motor');
    // exchange deposit
    Route::get('rentals/{rentals}/exchange-deposit', [AddMotorRental::class, 'changeDepositEdit'])->name('rentals.exchange-deposit');
    Route::put('rentals/{rentals}/update-deposit', [AddMotorRental::class, 'exchangeDeposit'])->name('rentals.update-deposit');
    // Exchange motorbike
    Route::get('rentals-report/exchange-motor', [AddMotorRental::class, 'exchangeMotorIndex'])->name('rentals-report.exchange-motor-index');
    // exchange deposit
    Route::get('rentals-report/exchange-deposit', [AddMotorRental::class, 'exchangeDepositIndex'])->name('rentals-report.exchange-deposit-index');
    /*end motorbike rental */

    /* visa & wp customer */
    // visa customer
    Route::resource('visas', AddVisaCustomer::class);
    Route::get('visa/reminder', [AddVisaCustomer::class, 'visaRemind'])->name('visa.reminder');
    Route::post('visas/{visa}/reminded', [AddVisaCustomer::class, 'reminded'])->name('visas.reminded');
    Route::get('visas/{visa}', [AddVisaCustomer::class, 'visaShow'])->name('visas.visaShow');
    Route::post('visas/{visa}', [AddVisaCustomer::class, 'delete'])->name('visas.delete');

    // wp customer
    Route::resource('work-permit', AddWPCustomer::class);
    Route::get('work-permit/reminder', [AddWPCustomer::class, 'wpRemind'])->name('work-permit.reminder');
    Route::post('work-permit/{wps}/reminded', [AddWPCustomer::class, 'reminded'])->name('work-permit.reminded');
    Route::get('wps/{wp}', [AddWPCustomer::class, 'wpShow'])->name('wps.wpShow');
    Route::post('work-permit/{wps}', [AddWPCustomer::class, 'delete'])->name('work-permit.delete');
    // end visa & wp customer

    /* layout report */
    // daily transaction
    Route::get('rentals-report/daily-rental-transaction', [AddMotorRental::class, 'dailyRental'])->name('rentals-report.daily-rental-transaction');
    // overdue customer
    Route::get('rentals-report/overdue-customer', [AddMotorRental::class, 'overdue'])->name('rentals-report.overdue-customer');

    // visa-wp customer
    /* end layout report */
});
