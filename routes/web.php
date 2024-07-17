<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\ExcelExportController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Travelr\DriverController;
use App\Http\Controllers\Travelr\AppUserController;
use App\Http\Controllers\Travelr\SettingController;
use App\Http\Controllers\Travelr\DispatchController;
use App\Http\Controllers\Admin\CompanyUserController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Travelr\JobHistoryController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\CustomerControllers\reportController;
use App\Http\Controllers\Admin\CustomerControllers\OutOfStockController;
use App\Http\Controllers\Admin\CustomerControllers\PriceAuditController;
use App\Http\Controllers\Admin\CustomerControllers\OpportunityController;
use App\Http\Controllers\Admin\CustomerControllers\NotificationController;
use App\Http\Controllers\Admin\CustomerControllers\BusinessOverviewController;
use App\Http\Controllers\Admin\CustomerControllers\MarketingActivityController;
use App\Http\Controllers\Admin\CustomerControllers\SellinSelloutDataController;
use App\Http\Controllers\Admin\CustomerControllers\StockCountByStoreController;
use App\Http\Controllers\Admin\CustomerControllers\ProductExpiryTrackerController;
use App\Http\Controllers\Admin\CustomerControllers\MerchandiserTimeSheetController;
use App\Http\Controllers\Admin\CustomerControllers\PlanogramComplianceTrackerController;

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

Route::get('/',  function(){
    $user = Auth::user();

if ($user) {
    switch (true) {
        case $user->roles->contains('name', 'admin'):
            // User has an "admin" role
            // Handle admin-specific actions
            return redirect('/dispatch');

            break;



        case $user->roles->contains('name', 'manager'):
            // User has a "manager" role
            // Handle manager-specific actions
            return redirect('/business_overview');

            break;

        case $user->roles->contains('name', 'merchandiser'):
            // User has a "merchandiser" role
            // Handle merchandiser-specific actions
            Session::flush();
            Auth::logout();
            return redirect('login');
            break;

        default:
            // User has other or no roles
            // Handle other user roles or cases
            Session::flush();
            Auth::logout();
            return redirect('login'); // Handle unknown roles appropriately

            break;
    }
} else {
    // Handle the case where no user is authenticated
    return redirect('/login'); // Handle unknown roles appropriately

}


});

Auth::routes();


// for authentication through email
// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');
// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();
//     return redirect('/home');
// })->middleware(['auth', 'signed'])->name('verification.verify');
// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('message', 'Verification link sent!');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::group(['middleware' => ['auth', 'role:admin']], function() {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('company', CompanyController::class);
    Route::get('company/edit/{target?}/{parameter?}', [CompanyController::class, 'edit'])->name('company-edit');
    Route::get('company/delete/{parameter?}', [CompanyController::class, 'delete'])->name('company-delete');
    //users
    Route::resource('user', CompanyUserController::class);
    Route::get('user/edit/{target?}/{parameter?}', [CompanyUserController::class, 'edit'])->name('user-edit');
    Route::get('user/delete/{parameter?}', [CompanyUserController::class, 'delete'])->name('user-delete');

    //stores
    Route::resource('store', StoreController::class);
    Route::get('store/edit/{target?}/{parameter?}', [StoreController::class, 'edit'])->name('store-edit');
    Route::get('store/delete/{parameter?}', [StoreController::class, 'delete'])->name('store-delete');
    Route::get('/export-excel', [ExcelExportController::class, 'export'])->name('export.excel');

    //products
    Route::resource('product', ProductController::class);
    Route::get('product/edit/{target?}/{parameter?}', [ProductController::class, 'edit'])->name('product-edit');
    Route::get('product/delete/{parameter?}', [ProductController::class, 'delete'])->name('product-delete');

     //products
     Route::resource('category', CategoryController::class);
     Route::get('category/edit/{target?}/{parameter?}', [CategoryController::class, 'edit'])->name('category-edit');



     Route::get('/dispatch', [DispatchController::class, 'index'])->name('dispatch.index');
     Route::post('/dispatch/store', [DispatchController::class, 'store'])->name('dispatch.store');
     Route::put('/dispatch/update/{id}', [DispatchController::class, 'update'])->name('dispatch.update');

     Route::post('/update-status',[DispatchController::class, 'updateStatus'])->name('updateStatus');
     Route::post('/dispatch/{id}/cancel', [DispatchController::class, 'cancel']);

    //  Route::post('/dispatch/store', [DispatchController::class, 'store'])->name('dispatch.store');
    //  Route::post('/dispatch/{id}/update-status', [DispatchController::class, 'updateStatus']);
    //  Route::post('/dispatch/{id}/cancel', [DispatchController::class, 'cancel']);
    //  Route::post('/dispatch/{id}/update', [DispatchController::class, 'update']);
     

     Route::resource('appuser', AppUserController::class);

     Route::resource('driver', DriverController::class);

     Route::resource('JobHistory', JobHistoryController::class);
     Route::get('jobHistory/edit/{target?}/{parameter?}', [JobHistoryController::class, 'edit'])->name('job-history-edit');


     Route::resource('setting', SettingController::class);

     // Route::get('/file-import',[StoreController::class,
    //         'importView'])->name('import-view');

    Route::post('/import',[StoreController::class,
            'import'])->name('import');

    Route::get('/export-store',[StoreController::class,
            'exportUsers'])->name('export-store');

            //product import ecport file

    Route::post('/importProduct',[ProductController::class,
    'importProduct'])->name('import-product');

    Route::get('/export-product',[ProductController::class,
            'exportUsers'])->name('export-product');

            //for category
    Route::post('/importCategory',[CategoryController::class,
    'importCategory'])->name('import-category');

    Route::get('/export-category',[CategoryController::class,
            'exportUsers'])->name('export-category');
});


Route::group(['middleware' => ['auth', 'role:manager']], function() {


    Route::get('generatePeport', [reportController::class, 'generateReport'])->name('generate-report');

    Route::resource('manager-dashboard', DashboardController::class);
    Route::resource('business_overview', BusinessOverviewController::class);
    Route::resource('merchandiser_time_sheet', MerchandiserTimeSheetController::class);
    Route::resource('price_audit', PriceAuditController::class);
    Route::resource('stock_count_by_store', StockCountByStoreController::class);
    Route::resource('planogram_compliance_tracker', PlanogramComplianceTrackerController::class);
    Route::resource('sellin_vs_sellout_data', SellinSelloutDataController::class);
    Route::resource('marketing_activity', MarketingActivityController::class);
    Route::resource('product_expiry_tracker', ProductExpiryTrackerController::class);
    Route::resource('out_of_stock', OutOfStockController::class);
    Route::resource('web_opportunity', OpportunityController::class);

    Route::resource('web_notification', NotificationController::class);
    Route::get('/notification-edit/{target?}/{parameter?}', [NotificationController::class, 'edit'])->name('edit-notification');
    // Route::get('/notification', [NotificationController::class, 'index'])->name('notification');
    // Route::get('/notification-store', [NotificationController::class, 'createNotification'])->name('create-notification');
    // Route::post('/notification-add', [NotificationController::class, 'store'])->name('add-notification');
    // Route::get('/notification-destroy/{target?}', [NotificationController::class, 'destroy'])->name('destroy-notification');
    // Route::get('/getData', [MerchandiserTimeSheetController::class, 'getDataByStore'])->name('getData');
});




// for forget password feature
// Route::get('/forgot-password', function () {
//     return view('auth.forgot-password');
// })->middleware('guest')->name('password.request');
// Route::post('/forgot-password', function (Request $request) {
//     $request->validate(['email' => 'required|email']);
//     $status = Password::sendResetLink(
//         $request->only('email')
//     );
//     return $status === Password::RESET_LINK_SENT
//                 ? back()->with(['status' => __($status)])
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.email');

// for reset password function
// Route::get('/reset-password/{token}', function ($token) {
//     return view('auth.reset-password', ['token' => $token]);
// })->middleware('guest')->name('password.reset');
// Route::post('/reset-password', function (Request $request) {
//     $request->validate([
//         'token' => 'required',
//         'email' => 'required|email',
//         'password' => 'required|min:8|confirmed',
//     ]);
//     $status = Password::reset(
//         $request->only('email', 'password', 'password_confirmation', 'token'),
//         function ($user, $password) {
//             $user->forceFill([
//                 'password' => Hash::make($password)
//             ])->save();
//             event(new PasswordReset($user));
//         }
//     );
//     return $status === Password::PASSWORD_RESET
//                 ? redirect()->route('login')->with('status', __($status))
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.update');
