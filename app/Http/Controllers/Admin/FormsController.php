<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormsController extends Controller
{
    public function forms(Request $request){
        $forms = DB::table('beneficiary_forms')
                    ->join('beneficiaries', 'beneficiaries.id' , '=', 'beneficiary_forms.beneficiary_id')
                    ->join('illnesses', 'illnesses.id' , '=', 'beneficiary_forms.illness_id')
                    ->where('beneficiaries.full_name', 'LIKE', "%". $request->query('name') ."%")
                    ->select('beneficiaries.full_name', 'beneficiary_forms.illness_id','illnesses.name as illness_name', 'beneficiary_forms.specialist_id', 'beneficiary_forms.id', 'beneficiary_forms.total_points', 'beneficiary_forms.created_at')
                    ->paginate(1);
        return view('dashboard.forms', ['forms' => $forms]);
    }
}
