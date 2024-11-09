<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Dimension;
use App\Models\Illness;
use App\Models\Question;
use App\Models\Service;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserHomeController extends Controller
{
    use GeneralTrait;

    public function categorizeAge($age)
    {
        if ($age >= 1 && $age <= 6) {
            return 1;
        } elseif ($age >= 7 && $age <= 17) {
            return 2;
        } else
            return 3;
    }

    /**
     * Get services for the home page of the user
     */
    public function services(){
        $services = Service::all();
        return $this->returnSuccessData($services, 'all services provided by the app', 200);
    }
    /**
     * Get the illnesses of a service
     */
    public function illnesses($service_id){
        $illnesses = Illness::where('service_id', '=', $service_id)->get();
        return $this->returnSuccessData($illnesses, 'all illnesses of a service', 200);
    }
    /**
     * Get the dimensions of an illness
     */
    public function dimensions(Request $request){
        $Beneficiary=Beneficiary::where('id','=',$request->Beneficiary_id)->firstOrFail();
        $age_group=$this->categorizeAge($Beneficiary->age);
        $dimensions = Dimension::where('illness_id', $request->illness_id)
            ->where('age_group',$age_group)
            ->withCount('questions')
            ->with(['questions' => function ($query) {
                $query->select('id', 'label', 'rank','points', 'dimension_id')
                    ->orderBy('rank', 'asc');
            }])
            ->get(['id', 'name', 'illness_id', 'age_group']);
        $dimensions_count = $dimensions->count();

        $response_data = $dimensions->map(function ($dimension) {
            return [
                'dimension_id' => $dimension->id,
                'name' => $dimension->name,
                'tips'=>$dimension->tips,
                'questions_count' => $dimension->questions_count,
                'questions' => $dimension->questions
            ];
        });

        return response()->json([
            'data' => [
                'dimensions_count' => $dimensions_count,
                'dimensions' => $response_data
            ],
            'message' => 'questions of an illness filtered by age group',
            'status_code' => 200
        ]);
    }
    /**
     * Get the questions of an illness
     */
    public function questions($illness_id){
        $dimensions = Dimension::where('illness_id', '=', $illness_id)->get('id');
        $questions = DB::table('questions')
                    ->whereIn('dimension_id', $dimensions)
                    ->groupBy('id', 'dimension_id', 'label', 'rank', 'points', 'created_at', 'updated_at')
                    ->orderBy('rank', 'asc')
                    ->select('id', 'label', 'rank', 'dimension_id')
                    ->get();

        $dimensions_count = DB::table('dimensions')->where('illness_id', '=', $illness_id)->count();
       return response()->json([
           'data' => [
               'dimensions_count' => $dimensions_count,
               'questions' => $questions
           ],
           'message' => 'questions of an illness',
           'status_code' => 200
       ]);
    }
}
