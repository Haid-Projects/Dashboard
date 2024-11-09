<?php

namespace App\Http\Controllers\Admin;

use App\Charts\Chart1;
use App\Charts\Chart2;
use App\Charts\FormsChart;
use App\Charts\FormsPieChart;
use App\Charts\FormsProgramsChart;
use App\Http\Controllers\Controller;
use App\Models\BeneficiaryForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Chart1 $chart1, Chart2 $chart2, FormsChart $chart, FormsPieChart $pie_chart, FormsProgramsChart $donut_chart){
      //  $accepted_fors = BeneficiaryForm::
        $rejected_forms = BeneficiaryForm::onlyTrashed()->count();
        $all_forms = BeneficiaryForm::withTrashed()->count();
//        $f = DB::table('beneficiary_forms')
//            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
//            ->join('services', 'services.id', '=', 'illnesses.service_id')
//            ->select('services.name', DB::raw("COUNT(beneficiary_forms.id) as count"))
//            ->groupBy('services.name')
//            ->get();
//        return $f;
        return view('dashboard.main', [
            'donut_chart' => $donut_chart->build(),
            'chart1' => $chart1->build(),
            'chart2' => $chart2->build(),
            'chart' => $chart->build(),
            'pie_chart' => $pie_chart->build(),
            'rejected_forms' => $rejected_forms,
            'all_forms' => $all_forms,
        ]);
    }
}
