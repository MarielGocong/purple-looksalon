<?php

use App\Http\Controllers;
use App\Enums\UserRolesEnum;
use App\Models\Role;
use App\Http\Controllers\UserSuspensionController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\DisplayContact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\SalesReportController;



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

//Route::get('/test', [App\Http\Controllers\AdminDashboardHome::class, 'index'])->name('test');


Route::get('/', [App\Http\Controllers\HomePageController::class, 'index'])->name('home');

Route::get('/about',[App\Http\Controllers\DisplayAbout::class, 'about'])->name('about');

Route::get('/contact', [App\Http\Controllers\DisplayContact::class, 'index'])->name('contact');
Route::post('/contact/store', [App\Http\Controllers\DisplayContact::class, 'storeContact'])->name('contact.store');

Route::get('/services', [App\Http\Controllers\DisplayService::class, 'index'])->name('services');

Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/services/{slug}', [App\Http\Controllers\DisplayService::class, 'show'])->name('view-service');
});

// Route::get('/services/{id}', [App\Http\Controllers\ServiceDisplay::class, 'show'])->name('services.show');
Route::get('/deals', [App\Http\Controllers\DisplayDeal::class, 'index'])->name('deals');
Route::get('/deals/apply/{deal}', [App\Http\Controllers\DisplayDeal::class, 'showServices'])->name('showServices');





// Users needs to be logged in for these routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {



        // middleware to give access only for admin
        Route::middleware([
            'validateRole:Admin'
        ])->group(function () {

            Route::prefix('manage')->group( function () {
                Route::resource('users', UserController::class)->names([
                    'index' => 'manageusers',         // GET /users (index)
                    'store' => 'manageusers.store',   // POST /users (store)
                    'create' => 'manageusers.create', // GET /users/create (create)
                    'edit' => 'manageusers.edit',     // GET /users/{id}/edit (edit)
                    'update' => 'manageusers.update', // PUT /users/{id} (update)
                    'destroy' => 'manageusers.destroy'// DELETE /users/{id} (destroy)
                ]);
                Route::put('users/{id}/suspend', [UserSuspensionController::class, 'suspend'])->name('manageusers.suspend');
                Route::put('users/{id}/activate', [UserSuspensionController::class, 'activate'])->name('manageusers.activate');

                Route::get('/dashboard/manage/contact', [DisplayContact::class, 'contact'])->name('managecontact');


                Route::get('employees', function () {
                    return view('dashboard.manage-employees.index');
                })->name('manageemployees');

                Route::get('admin/discount_code/list',[DiscountCodeController::class,'list'])->name('managediscountocodes');
                Route::get('admin/discount_code/add',[DiscountCodeController::class, 'add'])->name('managediscountcode.add');
                Route::post('admin/discount_code/add',[DiscountCodeController::class, 'insert'])->name('managediscountcode.insert');
                Route::get('admin/discount_code/edit/{$id}',[DiscountCodeController::class, 'edit'])->name('managediscountcode.edit');
                Route::post('admin/discount_code/edit/{$id}',[DiscountCodeController::class, 'update'])->name('managediscountcode.update');
                Route::get('admin/discount_code/delete/{$id}',[DiscountCodeController::class, 'delete'])->name('managediscountcode.delete');




            });



        });

        // middlleware to give access only for admin and employee
        Route::middleware([
            'validateRole:Admin,Employee'
        ])->group(function () {


            Route::post('/notifications/mark-as-read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

            Route::get('/notifications/redirect/{id}', [App\Http\Controllers\NotificationController::class, 'redirectToAppointment'])->name('notifications.redirectToAppointment');

            Route::prefix('dashboard')->group(function () {
                Route::get('/', [App\Http\Controllers\DashboardHomeController::class, 'index'])->name('dashboard');
            });

            Route::get('/admin/reports/top-customers', [App\Http\Controllers\AdminDashboardHomeController::class, 'index']);

            Route::get('/admin/reports/sales-by-category', [App\Http\Controllers\AdminDashboardHomeController::class, 'downloadSalesReport']);

            Route::get('/daily-report', [SalesReportController::class, 'dailyReport'])->name('daily.report');
            Route::get('/daily-report/pdf', [SalesReportController::class, 'downloadPDF'])->name('daily.report.pdf');

            Route::get('/weekly-report', [SalesReportController::class, 'weeklyReport'])->name('weekly.report');
            Route::get('/weekly-report/pdf', [SalesReportController::class, 'downloadWeeklyPDF'])->name('weekly.report.pdf');

            Route::get('/monthly-report', [SalesReportController::class, 'monthlyReport'])->name('monthly.report');
            Route::get('/monthly-report/pdf', [SalesReportController::class, 'downloadMonthlyPDF'])->name('monthly.report.pdf');

            Route::get('/quarterly-report', [SalesReportController::class, 'quarterlyReport'])->name('quarterly.report');
            Route::get('/quarterly-report/pdf', [SalesReportController::class, 'downloadQuarterlyPDF'])->name('quarterly.report.pdf');

            Route::get('/annual-report', [SalesReportController::class, 'annualReport'])->name('annual.report');
            Route::get('/annual-report/pdf', [SalesReportController::class, 'downloadAnnualPDF'])->name('annual.report.pdf');


            Route::prefix('manage')->group( function () {
                Route::get('services', function () {
                    return view('dashboard.manage-services.index');
                })->name('manageservices');

                Route::get('deals', function () {
                    return view('dashboard.manage-deals.index');
                })->name('managedeals');

                Route::get('concerns', function () {
                    return view('livewire.manage-concern');
                })->name('manageconcerns');

                Route::get('holidays', function () {
                    return view('dashboard.manage-holidays.index');
                })->name('manageholidays');

                Route::get('categories', function () {
                    return view('dashboard.manage-categories.index');
                })->name('managecategories' );

                Route::get('equipments', function () {
                    return view('dashboard.manage-equipments.index');
                })->name('manageequipments' );

                Route::get('appointments', function () {
                    return view('dashboard.manage-appointments.index');
                })->name('manageappointments');

                Route::get('onlinesupplier', function () {
                    return view('dashboard.manage-online-suppliers.index');
                })->name('manageonlinesuppliers');

                Route::get('supplies', function () {
                    return view('dashboard.manage-supplies.index');
                })->name('managesupplies');

                Route::get('jobcategories', function () {
                    return view('dashboard.manage-job-categories.index');
                })->name('managejobcategories' );

                Route::get('jobcategories/create', function () {
                    return view('dashboard.manage-job-categories.index');
                })->name('managejobcategories.create');

                Route::get('sales-report', function(){
                    return view('dashboard.sales-report.index');
                })->name('salesreport');






            } );



            // analytics route group
//            Route::prefix('analytics')->group(function () {
//                Route::get('/', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics');
//                Route::get('/revenue', [App\Http\Controllers\AnalyticsController::class, 'revenue'])->name('analytics.revenue');
//                Route::get('/appointments', [App\Http\Controllers\AnalyticsController::class, 'appointments'])->name('analytics.appointments');
//                Route::get('/customers', [App\Http\Controllers\AnalyticsController::class, 'customers'])->name('analytics.customers');
//                Route::get('/employees', [App\Http\Controllers\AnalyticsController::class, 'employees'])->name('analytics.employees');
//                Route::get('/services', [App\Http\Controllers\AnalyticsController::class, 'services'])->name('analytics.services');
//                Route::get('/locations', [App\Http\Controllers\AnalyticsController::class, 'locations'])->name('analytics.locations');
//            });
//                // graph route group
//                Route::prefix('graph')->group(function () {
//                    Route::get('/revenue', [App\Http\Controllers\GraphController::class, 'revenue'])->name('graph.revenue');
//                    Route::get('/appointments', [App\Http\Controllers\GraphController::class, 'appointments'])->name('graph.appointments');
//                    Route::get('/customers', [App\Http\Controllers\GraphController::class, 'customers'])->name('graph.customers');
//                    Route::get('/employees', [App\Http\Controllers\GraphController::class, 'employees'])->name('graph.employees');
//                    Route::get('/services', [App\Http\Controllers\GraphController::class, 'services'])->name('graph.services');
//                    Route::get('/locations', [App\Http\Controllers\GraphController::class, 'locations'])->name('graph.locations');
//                });


        });

        Route::middleware([
            'validateRole:Customer'
        ])->group(function () {

            Route::prefix('customer')->group( function () {
                Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('cart');
                Route::post('/', [App\Http\Controllers\CartController::class, 'store'])->name('cart.store');

                Route::delete('customer/cart/remove-item/{cart_service_id}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('cart.remove-item');


                Route::delete('/{id}', [App\Http\Controllers\CartController::class, 'destroy'])->name('cart.destroy');
                Route::post('/checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

                Route::get('customer-appointment', function () {
                    return view('dashboard.customer-view-appointment.index');
                })->name('customerview');
            });


            // Get the appointments of the user
//            Route::get('appointments', [App\Http\Controllers\AppointmentController::class, 'index'])->name('appointments');
//
//            // View an appointment
//            Route::get('appointments/{appointment_code}', [App\Http\Controllers\AppointmentController::class, 'show'])->name('appointments.show');
//
//            // Cancel an appointment
//            Route::delete('appointments/{appointment_code}', [App\Http\Controllers\AppointmentController::class, 'destroy'])->name('appointments.destroy');




        });
    });
