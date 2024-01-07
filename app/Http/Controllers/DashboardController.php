<?php

namespace App\Http\Controllers;

use App\Models\KeluhanPelanggan;
use App\Models\KeluhanStatusHis;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboardView');
    }
    public function getStatusChartData()
    {
        $receivedCount = KeluhanPelanggan::where('status_keluhan', '0')->count();
        $inProcessCount = KeluhanPelanggan::where('status_keluhan', '1')->count();
        $doneCount = KeluhanPelanggan::where('status_keluhan', '2')->count();

        return response()->json([
            'labels' => ['Received', 'In Process', 'Done'],
            'data' => [$receivedCount, $inProcessCount, $doneCount],
            'colors' => ['#3498db', '#f39c12', '#27ae60'],
        ]);
    }

    // public function getBarChartData()
    // {
    //     $startDate = now()->subMonths(6);
    //     $endDate = now();

    //     $statuses = ['0', '1', '2'];

    //     $data = [];

    //     foreach ($statuses as $status) {
    //         $count = KeluhanStatusHis::where('status_keluhan', $status)
    //             ->whereBetween('created_at', [$startDate, $endDate])
    //             ->count();

    //         $data['labels'][] = $status;
    //         $data['data'][] = $count;
    //         $data['colors'][] = '#' . substr(md5($status), 0, 6);
    //     }

    //     return response()->json($data);
    // }
    public function getBarChartData()
    {
        $months = [
            Carbon::now()->subMonths(5)->format('F'),
            Carbon::now()->subMonths(4)->format('F'),
            Carbon::now()->subMonths(3)->format('F'),
            Carbon::now()->subMonths(2)->format('F'),
            Carbon::now()->subMonths(1)->format('F'),
            Carbon::now()->format('F'),
        ];
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $statusData = KeluhanStatusHis::select('status_keluhan', 'created_at')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->orderBy('created_at')
            ->get();

        $statusCounts = $this->initializeMonthCounts($months);

        foreach ($statusData as $data) {
            $month = Carbon::parse($data->created_at)->format('F');
            $statusCounts[$month][$data->status_keluhan]++;
        }

        $chartData = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Received',
                    'data' => array_column($statusCounts, 0),
                    'backgroundColor' => '#3498db',
                ],
                [
                    'label' => 'In Process',
                    'data' => array_column($statusCounts, 1),
                    'backgroundColor' => '#f39c12',
                ],
                [
                    'label' => 'Done',
                    'data' => array_column($statusCounts, 2),
                    'backgroundColor' => '#27ae60',
                ],
            ],
        ];

        return response()->json($chartData);
    }

    private function initializeMonthCounts($months)
{
    $counts = [];
    foreach ($months as $month) {
        $counts[$month] = [0, 0, 0];
    }
    return $counts;
}

    private function getMonthsBetween($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $months = [];

        while ($start->lte($end)) {
            $months[] = $start->copy();
            $start->addMonth();
        }

        return $months;
    }


    public function getTop10Keluhan()
    {
        $top10Keluhan = KeluhanPelanggan::orderBy('created_at', 'asc')
            ->take(10)
            ->get();

        return response()->json($top10Keluhan);
    }


}
