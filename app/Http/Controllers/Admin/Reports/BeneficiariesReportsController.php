<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Charts\FormStatusLineChart;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\BeneficiaryForm;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficiariesReportsController extends Controller
{
    public function reports(FormStatusLineChart $chart, $beneficiary_form_id){
        $beneficiary_form = BeneficiaryForm::find($beneficiary_form_id);
        $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
        $p_sessions = Session::query()->where('beneficiary_form_id', $beneficiary_form_id)->where('has_attended', true)->count();
        $a_sessions = Session::query()->where('beneficiary_form_id', $beneficiary_form_id)->where('has_attended', false)->count();

        return view('dashboard.reports.beneficiaries_reports', [
                'chart' => $chart->build($beneficiary_form_id),
                'beneficiary_form' => $beneficiary_form,
                'beneficiary' => $beneficiary,
                'p_sessions' => $p_sessions,
                'a_sessions' => $a_sessions,
            ]);
    }
}
