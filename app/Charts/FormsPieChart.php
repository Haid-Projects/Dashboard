<?php

namespace App\Charts;

use App\Models\Beneficiary;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FormsPieChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\PieChart
    {
        // Define the age limit for children
        $child = 12;
        $teen = 18;
        $young = 25;

        // Calculate the cutoff birthdate for the age limit
        $cutoffDate1 = Carbon::now()->subYears($child);
        $cutoffDate2 = Carbon::now()->subYears($teen);
        $cutoffDate3 = Carbon::now()->subYears($young);


        $children = Beneficiary::where('birthdate', '<', $cutoffDate1)->count();
        $teenagers = Beneficiary::where('birthdate', '<', $cutoffDate2)->count();
        $youth = Beneficiary::where('birthdate', '<', $cutoffDate3)->count();
        return $this->chart->pieChart()
            ->setTitle('فئات اعمار المستفيدين')
            ->addData([$children, $teenagers, $youth])
            ->setLabels(['اطفال', 'مراهقيين', 'شباب']);
    }
}
