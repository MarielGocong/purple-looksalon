<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function dailyReport()
    {
        $reports = Appointment::selectRaw('
                `date`,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2) // Assuming 2 means completed
            ->groupBy('date')
            ->get();

        return view('reports.daily', compact('reports'));
    }

    public function downloadPDF()
    {
        $reports = Appointment::selectRaw('
                `date`,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2)
            ->groupBy('date')
            ->get();

        $preparedBy = auth()->user()->name ?? 'System Admin'; // Use authenticated user or default to 'System Admin'
        $currentDateTime = now()->format('Y-m-d H:i:s'); // Current date and time

        $pdf = Pdf::loadView('reports.daily-pdf', compact('reports', 'preparedBy', 'currentDateTime'));
        return $pdf->download('daily-sales-report.pdf');
    }

    public function weeklyReport()
    {
        $reports = Appointment::selectRaw('
                YEARWEEK(`date`, 1) as week,
                MIN(`date`) as week_start,
                MAX(`date`) as week_end,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2) // Assuming 2 means completed
            ->groupBy('week')
            ->orderBy('week', 'DESC')
            ->get();

        return view('reports.weekly', compact('reports'));
    }

    public function downloadWeeklyPDF()
    {
        $reports = Appointment::selectRaw('
                YEARWEEK(`date`, 1) as week,
                MIN(`date`) as week_start,
                MAX(`date`) as week_end,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2) // Assuming 2 means completed
            ->groupBy('week')
            ->orderBy('week', 'DESC')
            ->get();

        $preparedBy = auth()->user()->name ?? 'System Admin';
        $currentDateTime = now()->format('Y-m-d H:i:s');

        $pdf = Pdf::loadView('reports.weekly-pdf', compact('reports', 'preparedBy', 'currentDateTime'));
        return $pdf->download('weekly-sales-report.pdf');
    }

    public function monthlyReport()
    {
        $reports = Appointment::selectRaw('
                YEAR(`date`) as year,
                MONTH(`date`) as month,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2) // Assuming 2 means completed
            ->groupBy('year', 'month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();

        return view('reports.monthly', compact('reports'));
    }

    public function downloadMonthlyPDF()
    {
        $reports = Appointment::selectRaw('
                YEAR(`date`) as year,
                MONTH(`date`) as month,
                SUM(`total`) as total_sales,
                GROUP_CONCAT(DISTINCT `service_id`) as services,
                GROUP_CONCAT(DISTINCT `employee_id`) as employees
            ')
            ->where('status', 2) // Assuming 2 means completed
            ->groupBy('year', 'month')
            ->orderBy('year', 'DESC')
            ->orderBy('month', 'DESC')
            ->get();

        $preparedBy = auth()->user()->name ?? 'System Admin';
        $currentDateTime = now()->format('Y-m-d H:i:s');

        $pdf = Pdf::loadView('reports.monthly-pdf', compact('reports', 'preparedBy', 'currentDateTime'));
        return $pdf->download('monthly-sales-report.pdf');
    }


    public function quarterlyReport()
{
    $reports = Appointment::selectRaw('
            QUARTER(`date`) as quarter,
            YEAR(`date`) as year,
            CONCAT("Q", QUARTER(`date`), " ", YEAR(`date`)) as quarter_label,
            MIN(`date`) as quarter_start,
            MAX(`date`) as quarter_end,
            SUM(`total`) as total_sales,
            GROUP_CONCAT(DISTINCT `service_id`) as services,
            GROUP_CONCAT(DISTINCT `employee_id`) as employees
        ')
        ->where('status', 2) // Assuming 2 means completed
        ->groupBy('year', 'quarter')
        ->orderBy('year', 'DESC')
        ->orderBy('quarter', 'DESC')
        ->get();

    return view('reports.quarterly', compact('reports'));
}

public function annualReport()
{
    $reports = Appointment::selectRaw('
            YEAR(`date`) as year,
            MIN(`date`) as year_start,
            MAX(`date`) as year_end,
            SUM(`total`) as total_sales,
            GROUP_CONCAT(DISTINCT `service_id`) as services,
            GROUP_CONCAT(DISTINCT `employee_id`) as employees
        ')
        ->where('status', 2) // Assuming 2 means completed
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();

    return view('reports.annual', compact('reports'));
}

public function downloadQuarterlyPDF()
{
    $reports = Appointment::selectRaw('
            QUARTER(`date`) as quarter,
            YEAR(`date`) as year,
            CONCAT("Q", QUARTER(`date`), " ", YEAR(`date`)) as quarter_label,
            MIN(`date`) as quarter_start,
            MAX(`date`) as quarter_end,
            SUM(`total`) as total_sales,
            GROUP_CONCAT(DISTINCT `service_id`) as services,
            GROUP_CONCAT(DISTINCT `employee_id`) as employees
        ')
        ->where('status', 2)
        ->groupBy('year', 'quarter')
        ->orderBy('year', 'DESC')
        ->orderBy('quarter', 'DESC')
        ->get();

    $preparedBy = auth()->user()->name ?? 'System Admin';
    $currentDateTime = now()->format('Y-m-d H:i:s');

    $pdf = Pdf::loadView('reports.quarterly-pdf', compact('reports', 'preparedBy', 'currentDateTime'));
    return $pdf->download('quarterly-sales-report.pdf');
}

public function downloadAnnualPDF()
{
    $reports = Appointment::selectRaw('
            YEAR(`date`) as year,
            MIN(`date`) as year_start,
            MAX(`date`) as year_end,
            SUM(`total`) as total_sales,
            GROUP_CONCAT(DISTINCT `service_id`) as services,
            GROUP_CONCAT(DISTINCT `employee_id`) as employees
        ')
        ->where('status', 2)
        ->groupBy('year')
        ->orderBy('year', 'DESC')
        ->get();

    $preparedBy = auth()->user()->name ?? 'System Admin';
    $currentDateTime = now()->format('Y-m-d H:i:s');

    $pdf = Pdf::loadView('reports.annual-pdf', compact('reports', 'preparedBy', 'currentDateTime'));
    return $pdf->download('annual-sales-report.pdf');
}

}
