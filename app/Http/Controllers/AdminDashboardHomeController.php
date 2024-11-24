<?php

namespace App\Http\Controllers;

use App\Enums\UserRolesEnum;
use App\Models\Appointment;
use App\Models\Deal;
use App\Models\Service;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\Supply;
use Carbon\Carbon;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\DashboardHomeController;

use Illuminate\Http\Request;

class AdminDashboardHomeController extends Controller
{
    public function index()
    {
        $todayDate = Carbon::today()->toDateString();

        $totalCustomers = User::where('role_id', UserRolesEnum::Customer)->count();
        $totalEmployees = Employee::count();
        $totalServicesActive = Service::where('is_hidden', 0)->count();
        $totalServices = Service::count();

        $totalUpcomingDeals = Deal::where('start_date', '<', $todayDate)->count();
        $totalOngoingDeals = Deal::where('start_date', '<=', $todayDate)
            ->where('end_date', '>=', $todayDate)
            ->count();

        $totalUpcomingAppointments = Appointment::where('date', '>', $todayDate)->count();
        $todaysAppointments = Appointment::where('date', $todayDate)->count();
        $tommorowsAppointments = Appointment::where('date', Carbon::today()->addDay()->toDateString())->count();

        $bookingRevenueThisMonth = Appointment::where('created_at', '>', Carbon::today()->subMonth()->toDateTimeString())
            ->where('status', '!=', 0)
            ->sum('total');
        $bookingRevenueLastMonth = Appointment::where('created_at', '>', Carbon::today()->subMonths(2)->toDateTimeString())
            ->where('created_at', '<', Carbon::today()->subMonth()->toDateTimeString())
            ->where('status', '!=', 0)
            ->sum('total');

        $percentageRevenueChangeLastMonth = $bookingRevenueLastMonth != 0
            ? ($bookingRevenueThisMonth - $bookingRevenueLastMonth) / $bookingRevenueLastMonth * 100
            : 100;

       $todaysSchedule = Appointment::orderBy('time', 'asc')
            ->where('date', $todayDate)
            ->where('status', '!=', 0)
            ->orderBy('time', 'asc')
            ->with('service', 'employee', 'user')
            ->get();

        $tommorowsSchedule = Appointment::orderBy('time', 'asc')
            ->where('date', Carbon::today()->addDay()->toDateString())
            ->where('status', '!=', 0)
            ->orderBy('time', 'asc')
            ->with('service', 'employee', 'user')
            ->get();

      //  $timeSlots = TimeSlot::all(); //
        // Monthly revenue data for the chart
        $currentYear = Carbon::now()->year;

        $revenueData = Appointment::selectRaw('MONTH(created_at) as month, SUM(total) as total')
            ->whereYear('created_at', $currentYear)
            ->where('status', 2)
            ->groupBy('month')
            ->get();

        $months = $revenueData->pluck('month')->map(function($month) {
            return Carbon::create()->month($month)->format('F'); // Formats to full month name (e.g., "January")
        });

        $totals = $revenueData->pluck('total');

        $selectedYear = request()->input('year', Carbon::now()->year);

        // Fetch the top 3 grossing services for the selected year
        $topGrossingServices = DB::table('appointments')
            ->select('services.name as service_name', DB::raw('SUM(appointments.total) as total_earnings'))
            ->join('services', 'appointments.service_id', '=', 'services.id') // Join with services table
            ->whereYear('appointments.date', $selectedYear) // Filter by selected year
            ->groupBy('services.name') // Group by service name
            ->orderByDesc('total_earnings') // Sort by earnings in descending order
            ->limit(3) // Limit to top 3
            ->get();


        $lowQuantitySupplies = Supply::where('quantity', '<=', 5)->get();


        $expirationThreshold = 30; // days
        $nearExpirationSupplies = Supply::where('expiration_date', '<=', Carbon::today()->addDays($expirationThreshold))
            ->where('expiration_date', '>', $todayDate)
            ->with('online_supplier')
            ->get();

            $serviceCategoryRevenue = DB::table('appointments')
            ->select('categories.name as category_name', DB::raw('SUM(appointments.total) as total_revenue'))
            ->join('services', 'appointments.service_id', '=', 'services.id') // Join with services
            ->join('categories', 'services.category_id', '=', 'categories.id') // Join with categories
            ->where('appointments.status', 2) // Filter only completed appointments
            ->groupBy('categories.name') // Group by category name
            ->orderByDesc('total_revenue') // Sort by total revenue
            ->get();

        // Top Customers based on Appointment Revenue
        $topCustomers = DB::table('appointments')
        ->select(
            'users.name',
            'users.email',
            DB::raw('SUM(appointments.total) as total_revenue')
        )
        ->join('users', 'appointments.user_id', '=', 'users.id') // Join with users table
        ->where('appointments.status', '!=', 0) // Exclude canceled appointments
        ->groupBy('users.id', 'users.name', 'users.email') // Group by user fields
        ->orderByDesc('total_revenue') // Sort by total revenue
        ->limit(5) // Limit to the top 5 customers
        ->get();




        return view('dashboard.admin-employee', [
            'totalCustomers' => $totalCustomers,
            'totalEmployees' => $totalEmployees,
            'totalServicesActive' => $totalServicesActive,
            'totalServices' => $totalServices,
            'totalUpcomingDeals' => $totalUpcomingDeals,
            'totalOngoingDeals' => $totalOngoingDeals,
            'totalUpcomingAppointments' => $totalUpcomingAppointments,
            'todaysAppointments' => $todaysAppointments,
            'tommorowsAppointments' => $tommorowsAppointments,
            'bookingRevenueThisMonth' => $bookingRevenueThisMonth,
            'bookingRevenueLastMonth' => $bookingRevenueLastMonth,
            'percentageRevenueChangeLastMonth' => $percentageRevenueChangeLastMonth,
            'todaysSchedule' => $todaysSchedule,
            'tomorrowsSchedule' => $tommorowsSchedule,
           // 'timeSlots' => $timeSlots,
            'totals' => $totals,
            'months' => $months,
            'revenueData' => $revenueData,
            'lowQuantitySupplies' => $lowQuantitySupplies,
            'nearExpirationSupplies' => $nearExpirationSupplies,
            'topGrossingServices' => $topGrossingServices,
             'selectedYear' => $selectedYear,
             'serviceCategoryRevenue' => $serviceCategoryRevenue,
             'topCustomers' => $topCustomers,
            // Supplies nearing expiration

        ]);
    }

    public function downloadSalesReport()
    {
        // Fetch sales data
        $serviceCategoryRevenue = DB::table('appointments')
            ->select('categories.name as category_name', DB::raw('SUM(appointments.total) as total_revenue'))
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->where('appointments.status', 2)
            ->groupBy('categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Generate PDF for the report
        $pdf = Pdf::loadView('reports.sales-by-category-pdf', [
            'serviceCategoryRevenue' => $serviceCategoryRevenue
        ]);

        // Return the PDF as a download
        return $pdf->download('sales_report_by_category.pdf');
    }



}
