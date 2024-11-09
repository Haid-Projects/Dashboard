<?php

namespace App\Http\Controllers\StateManager;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\BeneficiaryAnswer;
use App\Models\BeneficiaryForm;
use App\Models\File;
use App\Models\Illness;
use App\Models\Specialist;
use App\Notifications\NewFormNotification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\MangeFormNotification;
use Illuminate\Support\Facades\Notification;


class ReviewBeneficiaryFormController extends Controller
{
    use GeneralTrait;

    /**
     * Get the new forms
     */
    public function newForms(Request $request){
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '=', null)
            ->where('beneficiary_forms.deleted_at', '=', null)
            ->where('beneficiary_forms.hidden', '=', false)
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id', 'beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name','beneficiaries.id as beneficiary_id')
            ->orderBy('beneficiary_forms.total_points', 'desc');
        if ($full_name) {
            $query->where('beneficiaries.full_name', 'LIKE', "%$full_name%");
        }

        $new_forms = $query->get();
        return $this->returnSuccessData($new_forms, 'new forms to check', 200);
    }

    /**
     * Get the closed forms
     */
    public function closedForms(Request $request){
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '!=', null)
            ->where('beneficiary_forms.is_opened', '=', false)
            ->where('beneficiary_forms.deleted_at', '=', null)
            ->where('beneficiary_forms.hidden', '=', false)
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id','beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name','beneficiaries.id as beneficiary_id')
            ->orderBy('beneficiary_forms.total_points', 'desc');
        if ($full_name) {
            $query->where('beneficiaries.full_name', 'LIKE', "%$full_name%");
        }

        $closed_forms = $query->get();
        return $this->returnSuccessData($closed_forms, 'closed forms to check', 200);
    }

    /**
     * Get the active forms
     */
    public function activeForms(Request $request){
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '!=', null)
            ->where('beneficiary_forms.is_opened', '=', true)
            ->where('beneficiary_forms.deleted_at', '=', null)
            ->where('beneficiary_forms.hidden', '=', false)
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id','beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name','beneficiaries.id as beneficiary_id')
            ->orderBy('beneficiary_forms.total_points', 'desc');
        if ($full_name) {
            $query->where('beneficiaries.full_name', 'LIKE', "%$full_name%");
        }

        $active_forms = $query->get();
        return $this->returnSuccessData($active_forms, 'active forms to check', 200);
    }

    /**
     * Reject form
     */
    public function beneficiaryInformation($beneficiary_id){
        $beneficiary = Beneficiary::find($beneficiary_id);
        $beneficiary->makeVisible('birthdate');
        $beneficiary->user=$beneficiary->user()->get();
        return $this->returnSuccessData($beneficiary, 'معلومات المستفيد', 200);
    }


    /**
     * Get beneficiary information by id
     */
    public function beneficiaryHealthInformation($beneficiary_id){
        $beneficiary = Beneficiary::find($beneficiary_id);
        if($beneficiary){
               $file=$beneficiary->file();
                return $this->returnSuccessData($file, 'المعلومات الطبية للمستفيد', 200);
            }

        return $this->returnErrorMessage('المستفيد غير موجود', 404);
    }
    /**
     * get beneficiary DimensionsData
     */
    public function beneficiaryDimensionsData($form_id)
    {
        $form = BeneficiaryForm::with(['specialist'])->where('id', '=', $form_id)->first();
        if ($form) {
            $BeneficiaryAnswer = BeneficiaryAnswer::where('_id', '=', $form->form_id)->first();

            $additionalDimensionsData = [];
            foreach ($BeneficiaryAnswer['dimensions'] as $dimension) {
                $additionalDimensionsData[] = [
                    'dimension_id' => $dimension['dimension_id'],
                    'dimension_name' => $dimension['dimension_name'],
                    'questions_count' => count($dimension['questions']),
                    'questions' => $dimension['questions']
                ];
            }

            $data = [
                'dimensions_count' => count($BeneficiaryAnswer['dimensions']),
                'dimensions' => $additionalDimensionsData
            ];

            return $this->returnSuccessData($data, "تمت العملية بنجاح", 200);
        } else {
            return $this->returnErrorMessage("لم يتم العثور على نموذج مستفيد بهذه الهوية", 404);
        }
    }
    /**
     * beneficiaryResult
     */


    public function beneficiaryResult($form_id)
    {
        $form = BeneficiaryForm::with(['specialist'])->where('id', '=', $form_id)->first();
            if ($form->specialist) {
                $data['result'] = true;
                $data['specialist_name'] = $form->specialist->name;
                $data['specialist_notes'] = $form->specialist_notes;
                $data['state_manager_notes'] = $form->state_manager_notes;
                return $this->returnSuccessData($data, "النتيجة", 200);
            }
        else {
            $data['result'] = false;
            return $this->returnSuccessData($data, "النتيجة", 200);
        }
    }

    /**
     * update beneficiaryInfo
     */

    /**
     * update beneficiaryInfo
     */

    public function updateBeneficiary(Request $request, $beneficiary_id) {
        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required',
            'birthdate' => 'sometimes|required',
            'relative_relation' => 'sometimes|required',
            'gender' => 'sometimes|required',
            'marital_status' => 'sometimes|required',
            'questions_data.diseases' => 'sometimes|array',
            'questions_data.diseases_check_box' => 'sometimes|array',
            'questions_data.general_behaviors' => 'sometimes|array',
            'questions_data.social_skills' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'missing some credentials'], 400);
        }

        $beneficiary = Beneficiary::findOrFail($beneficiary_id);

        $beneficiary->update($request->only([
            'full_name',
            'birthdate',
            'relative_relation',
            'gender',
            'marital_status',
            'health_problems',
            'chronic_diseases',
            'medicines',
            'general_behaviors',
            'social_skills',
        ]));

        if ($request->has('questions_data')) {
            $file = File::where('beneficiary_id', $beneficiary->id)->first();

            if (!$file) {
                $file = new File();
                $file->beneficiary_id = $beneficiary->id;
            }
            $file->diseases = [];
            $file->diseases_check_box = [];
            $file->general_behaviors = [];
            $file->social_skills = [];

            $file->diseases = $request->questions_data['diseases'] ?? [];
            $file->diseases_check_box = $request->questions_data['diseases_check_box'] ?? [];
            $file->general_behaviors = $request->questions_data['general_behaviors'] ?? [];
            $file->social_skills = $request->questions_data['social_skills'] ?? [];

            $file->save();
        }

        return  $this->returnSuccessMessage("تم التعديل بنجاح",200);
    }
    /**
     * Reject form
     */
    public function rejectForm($form_id){
        $form = BeneficiaryForm::find($form_id);
        $form->delete(); // here it should be soft delete
        return $this->returnSuccessMessage('form rejected successfully', 200);
    }

    /**
     * Assign a specialist for a form
     */

    public function manageForm(Request $request, $form_id) {
        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required_if:check,true',
            'check' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError('missing some credentials', 400);
        }
        $form = BeneficiaryForm::find((int)$form_id);
        $form->state_manager_notes = $request->state_manager_notes;

        if ($request->check) {
            $form->specialist_id = $request->specialist_id;
            $form->state_manager_id = Auth::guard('state_manager')->user()->id;
            $form->save();

            $user = $form->beneficiary->user;
            $specialist=Specialist::query()->where('id',$request->specialist_id)->get();
            Notification::send($user, new MangeFormNotification(true,'',2));
            Notification::send($specialist, new NewFormNotification($form->id,3));
        } else {
            $mongoRecords = BeneficiaryAnswer::where('beneficiary_form_id', (int)$form_id)->get();
            foreach ($mongoRecords as $record) {
                $record->delete();
            }
            $user = $form->beneficiary->user;
            $reason = $request->state_manager_notes;
            $form->state_manager_notes = $request->state_manager_notes;
            Notification::send($user, new MangeFormNotification(false, $reason,3));
            $form->delete();
        }

        return $this->returnSuccessMessage('تمت العملية بنجاح', 200);
    }

    /**
     * Close a form
     */
    public function closeForm(Request$request,$form_id){
        $form = BeneficiaryForm::find($form_id);
        $form->is_opened = false;
        $form->state_manager_notes = $request->state_manager_notes;
        $form->save();
        return $this->returnSuccessMessage('تم اغلاق الحالة بنجاح', 200);
    }

    /**
     * Add notes to a form
     */
    public function addNotesToForm(Request $request, $form_id){
        //validation
        $validator = Validator::make($request->all(), [
            'notes' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        $form = BeneficiaryForm::find($form_id);
        $form->state_manager_notes = $request->notes;
        $form->save();
        return $this->returnSuccessData($form, 'notes added to the form successfully', 200);
    }

    public function changeSpecialist(Request $request, $form_id){

        $validator = Validator::make($request->all(), [
            'specialist_id' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }

        $form = BeneficiaryForm::find($form_id);
        $form->specialist_id = $request->specialist_id;
        $form->save();
        return $this->returnSuccessMessage('تم تغيير الأخصائي بنجاح', 200);
    }

    /**
     * Get Specialists for the state manager to choose
     */
    public function specialists($form_id){
        $form = BeneficiaryForm::find($form_id);
        $f = DB::table('sessions')
            ->join('specialists', 'specialists.id', '=', 'sessions.specialist_id')
            ->where('sessions.illness_id', '=', $form->illness_id)
            ->select('specialists.name', 'sessions.illness_id', DB::raw("AVG(sessions.rate) as average"))
            ->groupBy('specialists.name', 'sessions.illness_id')
            ->orderBy('average', 'desc')
            ->get();
        return $this->returnSuccessData($f, 'all the specialists to choose one', 200);
    }

    public function rank(){
        $sessionRates = DB::table('sessions')
            ->select(
                'sessions.specialist_id',
                'sessions.illness_id',
                'modification_logs.average_points_percentage as current_percentage',
                'sessions.created_at as current_date',
                DB::raw('LAG(modification_logs.average_points_percentage) OVER (PARTITION BY sessions.beneficiary_form_id, sessions.illness_id ORDER BY sessions.date) as previous_percentage'),
                DB::raw('LAG(sessions.date) OVER (PARTITION BY sessions.beneficiary_form_id, sessions.illness_id ORDER BY sessions.date) as previous_date')
            )
            ->join('modification_logs', 'sessions.id', '=', 'modification_logs.session_id')
            ->whereNotNull('modification_logs.average_points_percentage')
            ->get();


        $sessionRates = $sessionRates->map(function ($session) {
            if ($session->previous_percentage !== null && $session->previous_date !== null) {
                $dateDifference = (new \DateTime($session->current_date))->diff(new \DateTime($session->previous_date))->days;
                if ($dateDifference > 0) {
                    $improvementRate = ($session->current_percentage - $session->previous_percentage) / $dateDifference;
                    $session->daily_improvement_rate = $improvementRate;
                } else {
                    $session->daily_improvement_rate = 0;
                }
            } else {
                $session->daily_improvement_rate = 0;
            }
            return $session;
        });

   return $sessionRates;
        $specialistRates = $sessionRates->groupBy(['specialist_id', 'illness_id'])->map(function ($sessions) {
            $totalRate = $sessions->sum('daily_improvement_rate');
            $count = $sessions->count();
            return $totalRate / $count;
        });

        $rankedSpecialists = collect();

        foreach ($specialistRates as $key => $rate) {
            list($specialist_id, $illness_id) = $key;
            $rankedSpecialists->push([
                'specialist_id' => $specialist_id,
                'illness_id' => $illness_id,
                'average_daily_improvement_rate' => $rate
            ]);
        }

        $rankedSpecialists = $rankedSpecialists->sortByDesc('average_daily_improvement_rate')->values();

        $specialists = Specialist::all()->keyBy('id');
        $illnesses = Illness::all()->keyBy('id');

        $rankedSpecialists = $rankedSpecialists->groupBy('illness_id')->map(function ($group) use ($specialists, $illnesses) {
            return $group->values()->map(function ($item, $index) use ($specialists, $illnesses) {
                $specialist = $specialists->get($item['specialist_id']);
                $illness = $illnesses->get($item['illness_id']);
                return [
                    'rank' => $index + 1,
                    'specialist_id' => $item['specialist_id'],
                    'specialist_name' => $specialist ? $specialist->name : 'Unknown',
                    'illness_id' => $item['illness_id'],
                    'illness_name' => $illness ? $illness->name : 'Unknown',
                    'average_daily_improvement_rate' => $item['average_daily_improvement_rate']
                ];
            });
        });

// Output ranked specialists
        return $rankedSpecialists;
    }

}
