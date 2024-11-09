<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dimension;
use App\Models\Illness;
use App\Models\Question;
use App\Models\Service;
use App\Models\StateManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgramsController extends Controller
{

    public function services(){
        $services = \App\Models\Service::all();
        return view('dashboard.programs.programs', ['services' => $services]);
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        Service::create([
            'name' => $request->name,
        ]);

        return redirect('services');
    }

    public function editService(Request $request, $service_id){
        $service = Service::find($service_id);
        $service->update([
            'name' => $request->name,
        ]);
        return redirect()->route('services');
    }

    public function deleteService($service_id){

        $service = Service::find($service_id);
        $service?->delete();
        return redirect('services');
    }

    public function illnesses($service_id){
        $illnesses = DB::table('illnesses')
                    ->join('services', 'services.id', '=', 'illnesses.service_id')
                    ->where('services.id' , '=', $service_id)
                    ->select('illnesses.id', 'illnesses.name', 'illnesses.icon', 'services.name as service_name')
                    ->get();
        $service = Service::find($service_id);
        return view('dashboard.programs.illnesses', ['illnesses' => $illnesses,'service_id' => $service_id, 'service_name' => $service->name]);
    }

    public function createIllness(Request $request,$service_id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        Illness::create([
            'name' => $request->name,
            'service_id' => $service_id,
           // 'icon' => "/icons/juzour.png",
        ]);

        return redirect()->route('illnesses', $service_id);
    }


    public function editIllness(Request $request, $illness_id){
        $illness = Illness::find($illness_id);
        $service_id = $illness->service_id;
        $illness->update([
            'name' => $request->name,
        ]);
        return redirect()->route('illnesses', $service_id);
    }

    public function deleteIllness($illness_id){

        $illness = Illness::find($illness_id);
        $service_id = $illness->service_id;
        $illness?->delete();
        return redirect()->route('illnesses', $service_id);
    }

    public function dimensions($illness_id){
        $dimensions = DB::table('dimensions')
                    ->join('illnesses', 'illnesses.id', '=', 'dimensions.illness_id')
                    ->where('illnesses.id' , '=', $illness_id)
                    ->select('dimensions.id', 'dimensions.name', 'dimensions.rank', 'dimensions.max_no', 'illnesses.icon', 'illnesses.name as illness_name')
                    ->get();
        $illness = Illness::find($illness_id);
        return view('dashboard.programs.dimensions', ['dimensions' => $dimensions,'illness_id' => $illness_id, 'illness_name' => $illness->name]);
    }

    public function createDimension(Request $request,$illness_id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        Dimension::create([
            'name' => $request->name,
            'illness_id' => $illness_id,
            'rank' => $request->rank,
           'max_no' => $request->max_no,
           'tips' => $request->tips ?? null,
           // 'icon' => "/icons/juzour.png",
        ]);

        return redirect()->route('dimensions', $illness_id);
    }

    public function editDimension(Request $request, $dimension_id){
        $dimension = Illness::find($dimension_id);
        $illness_id = $dimension->illness_id;
        $dimension->update([
            'name' => $request->name,
            'illness_id' => $illness_id,
            'rank' => $request->rank,
            'max_no' => $request->max_no,
            'tips' => $request->tips ,
        ]);
        return redirect()->route('dimensions', $illness_id);
    }

    public function deleteDimension($dimension_id){

        $dimension = Dimension::find($dimension_id);
        $illness_id = $dimension?->illness_id;
        $dimension?->delete();
        return redirect()->route('dimensions', $illness_id);

    }

    public function createQuestion(Request $request,$dimension_id){

        $validator = Validator::make($request->all(), [
            'label' => 'required',
            'rank' => 'required',
            'points' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        Question::create([
            'label' => $request->label,
            'dimension_id' => $dimension_id,
            'rank' => $request->rank,
            'points' => $request->points,
        ]);

        return redirect()->route('questions', $dimension_id);
    }
    public function editQuestion(Request $request, $question_id){
        $question = Question::find($question_id);
        $dimension_id = $question->dimension_id;
        $question->update([
            'label' => $request->label,
            'dimension_id' => $dimension_id,
            'rank' => $request->rank,
            'points' => $request->points,
        ]);
        return redirect()->route('questions', $dimension_id);
    }
    public function deleteQuestion($question_id){

        $question = Question::find($question_id);
        $dimension_id = $question?->dimension_id;
        $question?->delete();
        return redirect()->route('questions', $dimension_id);
    }

    public function test($dimension_id){
//       return $questions = DB::table('questions')
//            ->join('dimensions', 'dimensions.id', '=', 'questions.dimension_id')
//            ->where('dimensions.illness_id' , '=', $illness_id)
//            ->select('dimensions.name', 'questions.label')
//          // ->groupBy('dimensions.name', 'questions.label')
//            ->get();
        $dimension = Dimension::find($dimension_id);

        return view('dashboard.programs.questions', ['dimension' => $dimension]);
    }

}
