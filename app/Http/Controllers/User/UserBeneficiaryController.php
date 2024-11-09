<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\BeneficiaryForm;
use App\Models\File;
use App\Models\Session;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserBeneficiaryController extends Controller
{
    use GeneralTrait;

    /**
     * Create a new beneficiary
     */
    public function createBeneficiary(Request $request){
        //validation
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'birthdate' => 'required',
            'relative_relation' => 'required',
            'gender' => 'required',
            'marital_status' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        //creating new beneficiary
        $user = Auth::guard('user')->user();
        $beneficiary = Beneficiary::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'birthdate' => $request->birthdate,
            'relative_relation' => $request->relative_relation,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
        ]);

        $file=File::create([
            'beneficiary_id'=>$beneficiary->id,
            'diseases'=>$request->questions_data['diseases'],
            'diseases_check_box'=>$request->questions_data['diseases_check_box'],
            'general_behaviors'=>$request->questions_data['general_behaviors'],
            'social_skills'=>$request->questions_data['social_skills'],
        ]);
        $beneficiary->file_id=$file->_id;
        $beneficiary->save();
        return $this->returnSuccessData($beneficiary, 'beneficiary created successfully', 200);
    }

    /**
     * Create a Form
     */
    public function createForm(Request $request){

    }

    /**
     * Get the beneficiaries of the authenticated user
     */
    public function myBeneficiaries(){
        $user = Auth::guard('user')->user();
        $beneficiaries = Beneficiary::where('user_id', '=', $user->id)->get();
        return $this->returnSuccessData($beneficiaries, 'beneficiaries of the authenticated user', 200);
    }

    /**
     * Get beneficiary information by id
     */
    public function beneficiaryInfo($beneficiary_id){
        $user = Auth::guard('user')->user();
        $beneficiary = Beneficiary::find($beneficiary_id);
        if($beneficiary){
            if($beneficiary->user_id === $user->id){
                $beneficiary->question_data=$beneficiary->file();
                return $this->returnSuccessData($beneficiary, 'beneficiary info', 200);
            }
            return $this->returnErrorMessage('you are unauthorized to see this info', 403);
        }
        return $this->returnErrorMessage('beneficiary not found', 404);
    }

    /**
     * Get all the sessions that belongs to beneficiary
     */
    public function beneficiarySessions($beneficiary_id){
        $user = Auth::guard('user')->user();
        $beneficiary = Beneficiary::find($beneficiary_id);
        if($beneficiary){
            if($beneficiary->user_id === $user->id){
                $beneficiary_forms = BeneficiaryForm::where('beneficiary_id', '=', $beneficiary->id)->get('id');
                $beneficiary_sessions = Session::whereIn('beneficiary_form_id', $beneficiary_forms)->get();
                return $this->returnSuccessData($beneficiary_sessions, 'beneficiary sessions', 200);
            }
            return $this->returnErrorMessage('you are unauthorized to see this info', 403);
        }
        return $this->returnErrorMessage('beneficiary not found', 404);
    }

    /**
     * Get session details
     */
    public function sessionDetails($session_id){
        $user = Auth::guard('user')->user();
        $session = Session::find($session_id);
        if($session){
            $beneficiary_form = BeneficiaryForm::find($session->beneficiary_form_id);
            $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
           // return $beneficiary;
            if($beneficiary->user_id === $user->id){
                $session_details = DB::table('sessions')
                                    ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
                                    ->where('sessions.id', '=', $session_id)
                                    ->select('sessions.date','sessions.time','sessions.rate','sessions.beneficiary_notes',
                                        'sessions.name as session_name','specialists.name as specialist_name', 'specialists.phone_number'/*,'sessions.location'*/)
                                    ->first();
                return $this->returnSuccessData($session_details, 'session details', 200);
            }
            return $this->returnErrorMessage('you are unauthorized to see this info', 403);
        }
        return $this->returnErrorMessage('session not found', 404);
    }

    /**
     * Add notes to a session
     */
    public function addNotesToSession(Request $request, $session_id){
        //validation
        $validator = Validator::make($request->all(), [
            'notes' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        $session = Session::find($session_id);
        $user = Auth::guard('user')->user();
        $beneficiary_form = BeneficiaryForm::find($session->beneficiary_form_id);
        $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
        if($beneficiary->user_id === $user->id) {
            $session->beneficiary_notes = $request->notes;
            $session->save();
            return $this->returnSuccessData($session, 'notes added successfully', 200);
        }
        return $this->returnErrorMessage('unauthorized to add notes to this session', 403);
    }

    /**
     * Add notes to a session
     */
    public function addRateToSession(Request $request, $session_id){
        //validation
        $validator = Validator::make($request->all(), [
            'rate' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        $session = Session::find($session_id);
        $user = Auth::guard('user')->user();
        $beneficiary_form = BeneficiaryForm::find($session->beneficiary_form_id);
        $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
        if($beneficiary->user_id === $user->id) {
            $session->rate = $request->rate;
            $session->save();
            return $this->returnSuccessData($session, 'session rated successfully', 200);
        }
        return $this->returnErrorMessage('unauthorized to rate this session', 403);
    }

    /**
     * Get comingSessions
     */
    public function comingSessions($beneficiary_id){
        $comingSessions = DB::table('sessions')
                    ->join('beneficiary_forms', 'beneficiary_forms.id', '=', 'sessions.beneficiary_form_id')
                    ->where('beneficiary_forms.beneficiary_id', '=', $beneficiary_id)
                    ->whereDay('sessions.date', '>', Carbon::today())
                    ->select('sessions.name', 'sessions.date', 'sessions.time', 'sessions.id')
                    ->get();
        return $this->returnSuccessData($comingSessions, 'appointments in the future',200);
    }



}
