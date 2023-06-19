<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

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

Route::get('account/verify/{token}', [App\Http\Controllers\Auth\AuthController::class, 'verifyAccount'])->name('user.verify');

Route::get('/company/companyAccountSwitch', [
    'uses' => 'App\Http\Controllers\CompanyController@companyAccountSwitch',
    'as' => 'company.companyAccountSwitch'
]);

Route::get('/test/index', [
    'uses' => 'App\Http\Controllers\TestController@index',
    'as' => 'test.index'
]);

Route::get('/lang',[
    'uses' => 'App\Http\Controllers\HomeController@lang',
    'as' => 'lang.index'
]);

Route::get('/', function () {
    return redirect('dashboard');
})->name('login.index');

Route::get('/clear', function() {
    $exitCode = Artisan::call('config:clear');
    echo $exitCode;
});


Auth::routes();
// Auth::routes(['register' => false]);

Route::get('/install',[
    'uses' => 'App\Http\Controllers\InstallController@index',
    'as' => 'install.index'
]);

Route::post('/install',[
    'uses' => 'App\Http\Controllers\InstallController@install',
    'as' => 'install.install'
]);

Route::resources([
    'student-register' => App\Http\Controllers\StudentRegisterController::class,
]);



Route::group(['middleware' => ['auth','is_verify_email']], function() {

    Route::get('/dashboard/get-chart-data', [
        App\Http\Controllers\DashboardController::class, 'getChartData'
    ]);

    Route::get('/company/companyAccountSwitch', [
        'uses' => 'App\Http\Controllers\CompanyController@companyAccountSwitch',
        'as' => 'company.companyAccountSwitch'
    ]);

    Route::get('/bill/getAddPaymentDetails',[
        'uses' => 'App\Http\Controllers\BillController@getAddPaymentDetails',
        'as' => 'bill.getAddPaymentDetails'
    ]);

    Route::post('/bill/addPaymentStore',[
        'uses' => 'App\Http\Controllers\BillController@addPaymentStore',
        'as' => 'bill.addPaymentStore'
    ]);

    Route::get('/invoice/getAddPaymentDetails',[
        'uses' => 'App\Http\Controllers\InvoiceController@getAddPaymentDetails',
        'as' => 'invoice.getAddPaymentDetails'
    ]);

    Route::post('/invoice/addPaymentStore',[
        'uses' => 'App\Http\Controllers\InvoiceController@addPaymentStore',
        'as' => 'invoice.addPaymentStore'
    ]);

    Route::get('/report/year',[
        'uses' => 'App\Http\Controllers\ReportController@year',
        'as' => 'report.year'
    ]);

    Route::get('/report/school',[
        'uses' => 'App\Http\Controllers\ReportController@school',
        'as' => 'report.school'
    ]);

    Route::get('/report/college',[
        'uses' => 'App\Http\Controllers\ReportController@college',
        'as' => 'report.college'
    ]);

    Route::get('/report/village',[
        'uses' => 'App\Http\Controllers\ReportController@village',
        'as' => 'report.village'
    ]);

    Route::get('/report/course',[
        'uses' => 'App\Http\Controllers\ReportController@course',
        'as' => 'report.course'
    ]);

    Route::get('/report/student',[
        'uses' => 'App\Http\Controllers\ReportController@student',
        'as' => 'report.student'
    ]);

    Route::get('/report/expense',[
        'uses' => 'App\Http\Controllers\ReportController@expense',
        'as' => 'report.expense'
    ]);

    Route::get('/report/incomeVsexpense',[
        'uses' => 'App\Http\Controllers\ReportController@incomeVsexpense',
        'as' => 'report.incomeVsexpense'
    ]);

    Route::get('/report/profitAndloss',[
        'uses' => 'App\Http\Controllers\ReportController@profitAndloss',
        'as' => 'report.profitAndloss'
    ]);

    Route::get('/report/tax',[
        'uses' => 'App\Http\Controllers\ReportController@tax',
        'as' => 'report.tax'
    ]);

    Route::get('users/{user}/read-items', 'App\Http\Controllers\UserController@readItemsOutOfStock');

    Route::get('/student/studentIndex',[
        'uses' => 'App\Http\Controllers\UserController@studentIndex',
        'as' => 'student.studentIndex'
    ]);

    Route::get('/student/createStudent',[
        'uses' => 'App\Http\Controllers\UserController@createStudent',
        'as' => 'student.createStudent'
    ]);

    Route::post('/student/storeStudent',[
        'uses' => 'App\Http\Controllers\UserController@storeStudent',
        'as' => 'student.storeStudent'
    ]);

    Route::get('/student/editStudent/{id}',[
        'uses' => 'App\Http\Controllers\UserController@editStudent',
        'as' => 'student.editStudent'
    ]);

    Route::post('/student/updateStudent/{id}',[
        'uses' => 'App\Http\Controllers\UserController@updateStudent',
        'as' => 'student.updateStudent'
    ]);

    Route::get('/users/editUser/{id}',[
        'uses' => 'App\Http\Controllers\UserController@editUser',
        'as' => 'users.editUser'
    ]);

    Route::post('/users/updateUser/{id}',[
        'uses' => 'App\Http\Controllers\UserController@updateUser',
        'as' => 'users.updateUser'
    ]);

    Route::delete('/student/destroyStudent',[
        'uses' => 'App\Http\Controllers\UserController@destroyStudent',
        'as' => 'student.destroyStudent'
    ]);

    Route::delete('/users/destroyUser',[
        'uses' => 'App\Http\Controllers\UserController@destroyUser',
        'as' => 'users.destroyUser'
    ]);

    Route::resources([
        'roles' => App\Http\Controllers\RoleController::class,
        'users' => App\Http\Controllers\UserController::class,
        'customer' => App\Http\Controllers\CustomerController::class,
        'revenue' => App\Http\Controllers\RevenueController::class,
        'vendor' => App\Http\Controllers\VendorController::class,
        'payment' => App\Http\Controllers\PaymentController::class,
        'currency' => App\Http\Controllers\CurrencyController::class,
        'category' => App\Http\Controllers\CategoryController::class,
        'tax' => App\Http\Controllers\TaxController::class,
        'smtp' => App\Http\Controllers\SmtpConfigurationController::class,
        'company' => App\Http\Controllers\CompanyController::class,
        'invoice' => App\Http\Controllers\InvoiceController::class,
        'bill' => App\Http\Controllers\BillController::class,
        'item' => App\Http\Controllers\ItemController::class,
        'account' => App\Http\Controllers\AccountController::class,
        'transfer' => App\Http\Controllers\TransferController::class,
        'transaction' => App\Http\Controllers\TransactionController::class,
        'offline-payment' => App\Http\Controllers\OfflinePaymentController::class,

        'expense' => App\Http\Controllers\ExpenseController::class,

        // 'scholarship' => App\Http\Controllers\ScholarshipController::class,
        'scholarship-class' => App\Http\Controllers\ScholarshipClassController::class,
        'scholarship-year' => App\Http\Controllers\ScholarshipYearController::class,
        'scholarship-village' => App\Http\Controllers\ScholarshipVillageController::class,
        'scholarship-school' => App\Http\Controllers\ScholarshipSchoolController::class,
        'scholarship-college' => App\Http\Controllers\ScholarshipCollegeController::class,
        'scholarship-teacher' => App\Http\Controllers\ScholarshipTeacherController::class,
    ]);



    Route::get('/scholarship/index',[
        'uses' => 'App\Http\Controllers\ScholarshipController@index',
        'as' => 'scholarship.index'
    ]);

    Route::get('/scholarship/pending',[
        'uses' => 'App\Http\Controllers\ScholarshipController@pending',
        'as' => 'scholarship.pending'
    ]);

    Route::get('/scholarship/approved',[
        'uses' => 'App\Http\Controllers\ScholarshipController@approved',
        'as' => 'scholarship.approved'
    ]);

    Route::get('/scholarship/payment_in_progress',[
        'uses' => 'App\Http\Controllers\ScholarshipController@payment_in_progress',
        'as' => 'scholarship.payment_in_progress'
    ]);

    Route::get('/scholarship/payment_done',[
        'uses' => 'App\Http\Controllers\ScholarshipController@payment_done',
        'as' => 'scholarship.payment_done'
    ]);

    Route::get('/scholarship/rejected',[
        'uses' => 'App\Http\Controllers\ScholarshipController@rejected',
        'as' => 'scholarship.rejected'
    ]);

    Route::get('/scholarship/create',[
        'uses' => 'App\Http\Controllers\ScholarshipController@create',
        'as' => 'scholarship.create'
    ]);

    Route::post('/scholarship/store',[
        'uses' => 'App\Http\Controllers\ScholarshipController@store',
        'as' => 'scholarship.store'
    ]);

    Route::post('/scholarship/update',[
        'uses' => 'App\Http\Controllers\ScholarshipController@update',
        'as' => 'scholarship.update'
    ]);

    Route::get('/scholarship/{id}/show',[
        'uses' => 'App\Http\Controllers\ScholarshipController@show',
        'as' => 'scholarship.show'
    ]);

    Route::get('/scholarship/{id}/download',[
        'uses' => 'App\Http\Controllers\ScholarshipController@download',
        'as' => 'scholarship.download'
    ]);

    Route::get('/scholarship/edit/{id}',[
        'uses' => 'App\Http\Controllers\ScholarshipController@edit',
        'as' => 'scholarship.edit'
    ]);

    Route::delete('/scholarship/{id}/destroy',[
        'uses' => 'App\Http\Controllers\ScholarshipController@destroy',
        'as' => 'scholarship.destroy'
    ]);

    Route::get('/getItems', 'App\Http\Controllers\InvoiceController@getItems')->name('invoice.getItems');
    Route::get('/getBillItems', 'App\Http\Controllers\BillController@getBillItems')->name('bill.getBillItems');
    //Route::get('/getProduct', 'App\Http\Controllers\ProductController@getProduct')->name('product.getProduct');


    Route::post('/invoice/generateItemData',[
        'uses' => 'App\Http\Controllers\InvoiceController@generateItemData',
        'as' => 'invoice.generateItemData'
    ]);

    Route::get('/c/c', [App\Http\Controllers\CurrencyController::class, 'code'])->name('currency.code');

    Route::get('/profile/setting',[
        'uses' => 'App\Http\Controllers\ProfileController@setting',
        'as' => 'profile.setting'
    ]);

    Route::post('/profile/updateSetting',[
        'uses' => 'App\Http\Controllers\ProfileController@updateSetting',
        'as' => 'profile.updateSetting'
    ]);
    Route::get('/profile/password',[
        'uses' => 'App\Http\Controllers\ProfileController@password',
        'as' => 'profile.password'
    ]);

    Route::post('/profile/updatePassword',[
        'uses' => 'App\Http\Controllers\ProfileController@updatePassword',
        'as' => 'profile.updatePassword'
    ]);
    Route::get('/profile/view',[
        'uses' => 'App\Http\Controllers\ProfileController@view',
        'as' => 'profile.view'
    ]);

});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/dashboard',[
    'uses' => 'App\Http\Controllers\DashboardController@index',
    'as' => 'dashboard'
    ]);
});

Route::group(['middleware' => ['auth']], function() {

    Route::get('/apsetting',[
    'uses' => 'App\Http\Controllers\ApplicationSettingController@index',
    'as' => 'apsetting'
    ]);

    Route::post('/apsetting/update',[
    'uses' => 'App\Http\Controllers\ApplicationSettingController@update',
    'as' => 'apsetting.update'
    ]);
});

// general Setting
Route::group(['middleware' => ['auth']], function() {

    Route::get('/general',[
    'uses' => 'App\Http\Controllers\GeneralController@index',
    'as' => 'general'
    ]);

    Route::post('/general',[
    'uses' => 'App\Http\Controllers\GeneralController@edit',
    'as' => 'general'
    ]);

    Route::post('/general/localisation',[
    'uses' => 'App\Http\Controllers\GeneralController@localisation',
    'as' => 'general.localisation'
    ]);

    Route::post('/general/invoice',[
    'uses' => 'App\Http\Controllers\GeneralController@invoice',
    'as' => 'general.invoice'
    ]);

    Route::post('/general/bill',[
        'uses' => 'App\Http\Controllers\GeneralController@bill',
        'as' => 'general.bill'
    ]);

    Route::post('/general/defaults',[
    'uses' => 'App\Http\Controllers\GeneralController@defaults',
    'as' => 'general.defaults'
    ]);

});

Route::get('/home', function() {
    return redirect()->to('dashboard');
});
