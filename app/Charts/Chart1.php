<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class Chart1
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        $programs = array();
        $count = array();
        $fs = DB::table('beneficiary_forms')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->join('services', 'services.id', '=', 'illnesses.service_id')
          ->where('services.id', '=', 1)
            ->select('illnesses.name', 'services.name as service_name', DB::raw("COUNT(beneficiary_forms.id) as count"))
            ->groupBy('illnesses.name', 'services.name')
            ->get();
        $service_name = $fs[0]->service_name?? "";
        foreach ($fs as $f){
            $programs[] = $f->name;
            $count[]=  $f->count;
        }
        return $this->chart->barChart()
            ->setTitle($service_name)
            ->setSubtitle("عدد الحالات المدرجة لكل مرض .")
            ->addData($service_name, $count)
            ->setXAxis($programs);
//            ->addData('San Francisco', [6, 9, 3, 4, 10, 8])
//            ->addData('Boston', [7, 3, 8, 2, 6, 4])
//            ->setXAxis(['January', 'February', 'March', 'April', 'May', 'June']);
    }
}
