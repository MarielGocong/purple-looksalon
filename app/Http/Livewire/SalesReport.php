<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReport extends Component
{
    public $salesData; // To store sales data

    // Handle range change and update dates accordingly
    public function mount()
    {
        // Fetch sales data when the component is mounted
        $this->getTodaysSales();
    }

    public function getTodaysSales()
    {
        $today = Carbon::today(); // Get today's date

        // Fetch sales data for today
        $this->salesData = DB::table('appointments')
            ->selectRaw('DATE(date) as date, SUM(total) as total_sales, COUNT(*) as total_appointments')
            ->where('status', 2) // Only completed appointments
            ->whereDate('date', '=', $today) // Filter for today's date
            ->groupBy('date') // Group by date
            ->orderByDesc('date') // Sort by date in descending order
            ->get();
    }

    public function generatePdf()
    {
        // Check if there's any sales data
        if ($this->salesData->isEmpty()) {
            session()->flash('error', 'No sales data available for today.');
            return;
        }

        // Generate PDF from the sales data
        $pdf = Pdf::loadView('livewire.sales-report-pdf', ['salesData' => $this->salesData]);

        // Return the PDF to download
        return $pdf->download('sales-report.pdf');
    }

    public function render()
    {
        return view('livewire.sales-report', [
            'salesData' => $this->salesData
        ]);
    }
}
