<x-dashboard>
    <div class="p-4 sm:ml-64">
        <div class="flex justify-between mx-7">
            <h2 class="text-2xl font-bold">Weekly Sales Report</h2>
            <a href="{{ route('weekly.report.pdf') }}" class="btn btn-primary" style="margin-bottom: 20px;">Download PDF</a>
        </div>
        <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 overflow-x-scroll min-w-screen">
            <thead class="bg-gray-50">
            <tr>
                <th>Week</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Sales</th>
                <th>Services Rendered</th>
                <th>Employees Involved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->week }}</td>
                <td>{{ $report->week_start }}</td>
                <td>{{ $report->week_end }}</td>
                <td>{{ number_format($report->total_sales, 2) }}</td>
                <td>{{ $report->services }}</td>
                <td>{{ $report->employees }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
        </div>
    </div>
</x-dashboard>
