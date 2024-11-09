<?php

namespace App\Charts;

use App\Models\BeneficiaryForm;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class FormsProgramsChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $programs = array();
        $count = array();
        $fs = DB::table('beneficiary_forms')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->join('services', 'services.id', '=', 'illnesses.service_id')
            ->select('services.name', DB::raw("COUNT(beneficiary_forms.id) as count"))
            ->groupBy('services.name')
            ->get();
        foreach ($fs as $f){
            $programs[] = $f->name;
            $count[]=  $f->count;
        }
        return $this->chart->donutChart()
            ->setTitle('عدد الاستمارات الخاضعة تحت كل برنامج')
           // ->setSubtitle('Season 2021.')
            ->addData($count)
            ->setLabels($programs);
    }
}
