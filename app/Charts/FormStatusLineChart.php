<?php

namespace App\Charts;

use App\Models\ModificationLog;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class FormStatusLineChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($beneficiaryFormId): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $percent = array();
        //$percent[] = 0;
        $date = array();
        $modificationLogs = ModificationLog::where('beneficiary_form_id', $beneficiaryFormId)
            ->orderBy('created_at', 'asc')
            ->get();

        $chartData = $modificationLogs->map(function($log) {
            return [
                'average_points_percentage' => $log->average_points_percentage,
                'created_at' => $log->created_at->toDateTimeString()
            ];
        });

        foreach ($chartData as $sr){
            $percent[] = $sr["average_points_percentage"];
            $date[] = date('d-M', strtotime( $sr["created_at"]));
        }

        return $this->chart->lineChart()
            ->setTitle('معدل تطور الحالة على مدار مسار العلاج')
//            ->setSubtitle('Improvement percentage over time.')
            ->addData('Improvement percentage', $percent)
            ->setXAxis($date);
    }
}
