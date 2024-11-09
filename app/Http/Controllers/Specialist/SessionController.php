<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\BeneficiaryForm;
use App\Models\ModificationLog;
use App\Models\Session;
use App\Notifications\MangeFormNotification;
use App\Notifications\NewSessionNotification;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    use GeneralTrait;

    /**
     * Create a new session
     */
    public function createSession(Request $request, $beneficiary_form_id)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after:today',
            'time' => 'required',
            'name' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError('Please provide valid data.', 400);
        }
        $beneficiary_form = BeneficiaryForm::find($beneficiary_form_id);
        // $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
        $specialist = Auth::guard('specialist')->user();
        if ($specialist->id === $beneficiary_form->specialist_id) {
            $session = Session::create([
                'beneficiary_form_id' => $beneficiary_form_id,
                'specialist_id' => $specialist->id,
                'date' => $request->date,
                'time' => $request->time,
                'location' => $request->location,
                'name' => $request->name,
                'illness_id' => $beneficiary_form->illness_id, // validate and make sure that is correct
            ]);
            $user = $beneficiary_form->beneficiary->user;
            $user->notify(new NewSessionNotification($session, 'لديك جلسة جديدة', 'new_session',4));
            return $this->returnSuccessData($session, 'session created successfully', 200);
        }
        return $this->returnErrorMessage('you are unauthorized to create session to this beneficiary', 403);
    }

    /**
     * Delete a session
     */
    public function deleteSession($session_id)
    {
        $session = Session::find($session_id);
        $specialist = Auth::guard('specialist')->user();
        if ($specialist->id === $session->specialist_id) {
            if ($session->date > Carbon::now()) { // check this condition
                $session->delete();
                return $this->returnSuccessData($session, 'session deleted successfully', 200);
            }
            return $this->returnErrorMessage('you can not delete a session in the past', 400);
        }
        return $this->returnErrorMessage('you are unauthorized to create session to this beneficiary', 403);
    }

    /**
     * Get session details
     */
    public function sessionDetails($session_id)
    {
        $specialist = Auth::guard('specialist')->user();


        $session = Session::find($session_id);
        if ($session) {
            $beneficiary_form = BeneficiaryForm::find($session->beneficiary_form_id);
            $beneficiary = Beneficiary::find($beneficiary_form->beneficiary_id);
            if ($beneficiary_form->specialist_id === $specialist->id) {
                $session_details = DB::table('sessions')
                    ->join('beneficiary_forms', 'beneficiary_forms.id', '=', 'sessions.beneficiary_form_id')
                    ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
                    ->where('sessions.id', '=', $session_id)
                    ->select('sessions.date', 'sessions.time', 'sessions.id', 'sessions.location',
                        'sessions.name as session_name', 'sessions.rate', 'sessions.beneficiary_notes',
                        'beneficiaries.full_name as beneficiary_name')
                    ->first();
                return $this->returnSuccessData($session_details, 'session details', 200);
            }
            return $this->returnErrorMessage('you are unauthorized to see this info', 403);
        }
        return $this->returnErrorMessage('session not found', 404);
    }

    /**
     * Take attendance
     */
    public function takeAttendance(Request $request, $session_id)
    {
        $session = Session::find($session_id);
        $specialist = Auth::guard('specialist')->user();
        if ($specialist->id === $session->specialist_id) {
            //     if($session->date >  Carbon::now()){ // check this condition
            $session->has_attended = $request->has_attended;
            $session->save();
            return $this->returnSuccessData($session, 'attendance toked successfully', 200);
            //  }
            //  return $this->returnErrorMessage('you can not update a session in the past', 400);
        }
        return $this->returnErrorMessage('you are unauthorized to create session to this beneficiary', 403);
    }

    /**
     * Add notes to a session
     */
    public function addNotesToSession(Request $request, $session_id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'specialist_notes' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->returnValidationError('missing some credentials', 400);
        }

        $session = Session::with('beneficiaryForm.beneficiary')->find($session_id);
        if (!$session) {
            return $this->returnErrorMessage('الجلسة غير موجودة', 404);
        }

        $specialist = Auth::guard('specialist')->user();
        if ($specialist->id !== $session->specialist_id) {
            return $this->returnErrorMessage('انت لاتملك صلاحية على هذا المستفيد', 403);
        }

        // Update session notes and attendance
        $session->specialist_notes = $request->specialist_notes;
        $session->has_attended = $request->has_attended;
        $session->save();

        // Get the beneficiary via the BeneficiaryForm relationship
        $beneficiary = $session->beneficiaryForm->beneficiary;

        // Count attended and missed sessions efficiently
        $attendedSessionsCount = Session::whereHas('beneficiaryForm', function ($query) use ($beneficiary) {
            $query->where('beneficiary_id', $beneficiary->id);
        })->where('has_attended', true)->count();

        $missedSessionsCount = Session::whereHas('beneficiaryForm', function ($query) use ($beneficiary) {
            $query->where('beneficiary_id', $beneficiary->id);
        })->where('has_attended', false)->count();

        // Update the beneficiary's rate based on attended and missed sessions
        $beneficiary->rate = max(0, min(5, $attendedSessionsCount - $missedSessionsCount * 0.5));
        $beneficiary->save();

        return $this->returnSuccessMessage( 'تم أخذ الحضور بنجاح', 200);
    }


    /**
     * Get appointments today
     */
    public function appointmentsToday()
    {
        $specialist = Auth::guard('specialist')->user();
        $appointments = DB::table('sessions')
            ->join('beneficiary_forms', 'beneficiary_forms.id', '=', 'sessions.beneficiary_form_id')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->whereDay('date', '=', Carbon::today())
            ->where('sessions.specialist_id', '=', $specialist->id)
            ->select('sessions.name as session_name','sessions.date', 'sessions.time', 'sessions.id', 'beneficiaries.full_name as beneficiary_name','beneficiary_forms.id as form_id')
            ->get();
//        $appointments = Session::query()
//                        ->whereDay('date', '=', Carbon::today())
//                        ->where('specialist_id', '=', $specialist->id)
//                        ->get();
        return $this->returnSuccessData($appointments, 'appointments today', 200);
    }

    /**
     * Get appointments
     */
    public function appointments()
    {
        $specialist = Auth::guard('specialist')->user();
        $appointments = DB::table('sessions')
            ->join('beneficiary_forms', 'beneficiary_forms.id', '=', 'sessions.beneficiary_form_id')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->whereDay('date', '>=', Carbon::today())
            ->where('sessions.specialist_id', '=', $specialist->id)
            ->orderBy('sessions.date')
            ->select('sessions.date', 'sessions.time', 'sessions.id', 'beneficiaries.full_name')
            ->get();
        return $this->returnSuccessData($appointments, 'all appointments', 200);
    }

    /**
     * Get appointments in a specific date
     */
    public function appointmentsByDate($date)
    {
        $specialist = Auth::guard('specialist')->user();
        $appointments = DB::table('sessions')
            ->join('beneficiary_forms', 'beneficiary_forms.id', '=', 'sessions.beneficiary_form_id')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->whereDate('date', '=', $date)
            ->where('sessions.specialist_id', '=', $specialist->id)
            ->select('sessions.date', 'sessions.time', 'sessions.id', 'beneficiaries.full_name')
            ->get();
        return $this->returnSuccessData($appointments, 'appointment', 200);
    }

    /**
     * Get old sessions of a beneficiary
     */
    public function beneficiaryOldSessions($beneficiary_form_id)
    {
        $old_sessions = Session::query()
            ->whereDay('date', '<', Carbon::today())
            ->where('beneficiary_form_id', '=', $beneficiary_form_id)
            ->with('beneficiaryForm.beneficiary')
            ->get();
        $result = $old_sessions->map(function ($session) {
            return [
                'id' => $session->id,
                'beneficiary_form_id' => $session->beneficiary_form_id,
                'illness_id' => $session->illness_id,
                'specialist_id' => $session->specialist_id,
                'name' => $session->name,
                'location' => $session->location,
                'date' => $session->date,
                'time' => $session->time,
                'specialist_notes' => $session->specialist_notes,
                'beneficiary_notes' => $session->beneficiary_notes,
                'rate' => $session->rate,
                'has_attended' => $session->has_attended,
                'created_at' => $session->created_at,
                'updated_at' => $session->updated_at,
                'beneficiary_full_name' => $session->beneficiaryForm->beneficiary->full_name
            ];
        });
        return $this->returnSuccessData($result, 'old sessions with beneficiary full name', 200);
    }





    public function toggleSociallyIntegrable($beneficiary_id)
    {
        $beneficiary = Beneficiary::find($beneficiary_id);
        if (!$beneficiary) {
            return $this->returnErrorMessage('المستفيد غير موجود', 404);
        }

        $beneficiary->socially_integrable = !$beneficiary->socially_integrable;
        $beneficiary->save();
        return $this->returnSuccessMessage("تم العملية بنجاح",200);
    }


    public function Modification($session_id)
    {
        $modificationLog = ModificationLog::where('session_id', $session_id)->first();

        if (!$modificationLog) {
            return response()->json(['message' => 'Modification log not found'], 404);
        }

        return response()->json($modificationLog);
    }
}
