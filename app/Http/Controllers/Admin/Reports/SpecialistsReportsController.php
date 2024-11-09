<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Charts\Chart1;
use App\Charts\Chart2;
use App\Charts\FormsChart;
use App\Charts\FormsPieChart;
use App\Charts\FormsProgramsChart;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Spatie\Browsershot\Browsershot;

use function view;

class SpecialistsReportsController extends Controller
{
    public function sortByRate(Request $request, Chart1 $chart1, Chart2 $chart2, FormsChart $chart, FormsPieChart $pie_chart, FormsProgramsChart $donut_chart){
        $specialists = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->select('specialists.name', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name')
            ->orderBy('average', 'desc')
            ->get();

        $illness_name = $request->query('name');
        $sortByIllness = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->join('illnesses', 'illnesses.id', '=', 'sessions.illness_id')
            ->where('illnesses.name', 'LIKE', "%".$illness_name."%")
            ->select('specialists.name', 'illnesses.name as illness_name', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name', 'illnesses.name')
            ->orderBy('average', 'desc')
            ->get();

        $closedFormsForEachOne = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', false)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();

        $openedFormsForEachOne = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', true)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();




        return view('dashboard.reports.specialist_reports', [
            'specialists' => $specialists,
            'sortByIllness' => $sortByIllness,
            'closedFormsForEachOne' => $closedFormsForEachOne,
            'openedFormsForEachOne' => $openedFormsForEachOne,
            'donut_chart' => $donut_chart->build(),
            'chart1' => $chart1->build(),
            'chart2' => $chart2->build(),
            'chart' => $chart->build(),
            'pie_chart' => $pie_chart->build(),
            ]);
    }

    public function sortByIllness($illness_id){
        $f = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->where('sessions.illness_id', '=', $illness_id)
            ->select('specialists.name', 'sessions.illness_id', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name', 'sessions.illness_id')
            ->orderBy('average', 'desc')
            ->get();
        return $f;
    }

    public function closedFormsForEachOne(){
        $f = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', false)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();
        return $f;
    }

    public function openedFormsForEachOne(){
        $f = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', true)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();
        return $f;
    }

    public function mostAbsentBeneficiaries(){
         $f = DB::table('beneficiary_forms')
             ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
             ->join('sessions', 'sessions.beneficiary_form_id', '=', 'beneficiary_forms.id')
        //     ->where('beneficiary_forms.has_attended', '!=', null)
             ->where('sessions.has_attended', '=', false)
             ->select('beneficiaries.full_name', DB::raw("count(sessions.id) as count"))
             ->groupBy('beneficiaries.full_name')
             ->orderBy('count', 'desc')
             ->get();


        return $f;
    }

    public function generatePdf(Request $request, Chart1 $chart1, Chart2 $chart2, FormsChart $chart, FormsPieChart $pie_chart, FormsProgramsChart $donut_chart){
        $specialists = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->select('specialists.name', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name')
            ->orderBy('average', 'desc')
            ->get();

        $illness_name = $request->query('name');
        $sortByIllness = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->join('illnesses', 'illnesses.id', '=', 'sessions.illness_id')
            ->where('illnesses.name', 'LIKE', "%".$illness_name."%")
            ->select('specialists.name', 'illnesses.name as illness_name', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name', 'illnesses.name')
            ->orderBy('average', 'desc')
            ->get();

        $closedFormsForEachOne = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', false)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();

        $openedFormsForEachOne = DB::table('beneficiary_forms')
            ->join('specialists', 'specialists.id', '=', 'beneficiary_forms.specialist_id')
            ->where('beneficiary_forms.is_opened', '=', true)
            ->select('specialists.name', DB::raw("count(beneficiary_forms.id) as count"))
            ->groupBy('specialists.name')
            ->orderBy('count', 'desc')
            ->get();




        $data =  [
            'specialists' => $specialists,
            'sortByIllness' => $sortByIllness,
            'closedFormsForEachOne' => $closedFormsForEachOne,
            'openedFormsForEachOne' => $openedFormsForEachOne,
            'donut_chart' => $donut_chart->build(),
            'chart1' => $chart1->build(),
            'chart2' => $chart2->build(),
            'chart' => $chart->build(),
            'pie_chart' => $pie_chart->build(),
        ];

        $url = $request->input('url');
        $p = Browsershot::url($url)
            ->setOption('landscape', true)
            ->windowSize(3840, 2160)
            ->waitUntilNetworkIdle()
            ->screenshot();
 //           ->save('/googlescreenshot.jpg');

        //return "done";
        return response()->download($p);
    }

    public function image2pdf(Request $request){

//        // Path to your image
//        $imagePath = public_path('images/screenshot.png'); // Adjust the path as needed

        $imagePath = $request->query('image');
        return response()->json(['data' => $imagePath]);
        // Generate the PDF from the image
        $pdf = PDF::loadView('dashboard.pdf.image', ['imagePath' => $imagePath]);

        // Download the PDF
        return $pdf->download('screenshot.pdf');
    }
}
