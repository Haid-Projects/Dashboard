<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryAnswer;
use App\Models\BeneficiaryForm;
use App\Models\ModificationLog;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReviewFormController extends Controller
{
    use GeneralTrait;
    /**
     * Get the new forms
     */
    public function newForms(Request $request){
        $specialist = Auth::guard('specialist')->user();
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '=', $specialist->id)
            ->where('beneficiary_forms.hidden', '=', false)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sessions')
                    ->whereRaw('sessions.beneficiary_form_id = beneficiary_forms.id');
            })
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id', 'beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name', 'beneficiaries.id as beneficiary_id')
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
        $specialist = Auth::guard('specialist')->user();
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '=', $specialist->id)
            ->where('beneficiary_forms.is_opened', '=', false)
            ->where('beneficiary_forms.hidden', '=', false)
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id', 'beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name', 'beneficiaries.id as beneficiary_id')
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
        $specialist = Auth::guard('specialist')->user();
        $full_name = $request->input('full_name'); // Retrieve the full_name from the request

        $query = DB::table('beneficiary_forms')
            ->join('beneficiaries', 'beneficiaries.id', '=', 'beneficiary_forms.beneficiary_id')
            ->join('illnesses', 'illnesses.id', '=', 'beneficiary_forms.illness_id')
            ->where('beneficiary_forms.specialist_id', '=', $specialist->id)
            ->where('beneficiary_forms.is_opened', '=', true)
            ->where('beneficiary_forms.hidden', '=', false)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sessions')
                    ->whereRaw('sessions.beneficiary_form_id = beneficiary_forms.id');
            })
            ->select('beneficiaries.full_name', 'beneficiary_forms.id as form_id', 'beneficiary_forms.created_at', 'beneficiary_forms.total_points', 'illnesses.name', 'beneficiaries.id as beneficiary_id')
            ->orderBy('beneficiary_forms.total_points', 'desc');
        if ($full_name) {
            $query->where('beneficiaries.full_name', 'LIKE', "%$full_name%");
        }

        $active_forms = $query->get();

        return $this->returnSuccessData($active_forms, 'active forms to check', 200);
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
        $form->specialist_notes = $request->notes;
        $form->save();
        return $this->returnSuccessData($form, 'notes added to the form successfully', 200);
    }
    /**
     * beneficiaryInformation
     */

    public function beneficiaryInformation($beneficiary_form_id){
        $form = BeneficiaryForm::find($beneficiary_form_id);
        $beneficiary = $form->beneficiary;
        $beneficiary->state_manager_notes=$form->state_manager_notes;
        $beneficiary->user=$beneficiary->user()->get();
        return $this->returnSuccessData($beneficiary, 'beneficiary information', 200);
    }

    /**
     * update dimension
     */
    public function updateDimension(Request $request, $form_id)
    {
        $request->validate([
            'dimension_id' => 'required|integer|exists:dimensions,id',
            'answers' => 'required|array',
            'answers.*' => 'required|boolean',
            'session_id' => 'required|integer|exists:sessions,id',
        ]);

        $mongoRecord = BeneficiaryAnswer::where('beneficiary_form_id', (int) $form_id)->first();
        if (!$mongoRecord) {
            return $this->returnErrorMessage('السجل غير موجود', 404);
        }

        $mongoData = $mongoRecord->toArray();
        $dimensionIndex = -1;
        $dimensionId = $request->input('dimension_id');

        foreach ($mongoData['dimensions'] as $index => $dimension) {
            if ($dimension['dimension_id'] == $dimensionId) {
                $dimensionIndex = $index;
                $dimensionName = $dimension['dimension_name'];
                break;
            }
        }

        if ($dimensionIndex === -1) {
            return $this->returnErrorMessage('البعد غير موجود', 404);
        }

        $modifications = [];
        $answers = $request->input('answers');
        foreach ($mongoData['dimensions'][$dimensionIndex]['questions'] as $index => &$question) {
            if (isset($answers[$index]) && $question['answer'] !== $answers[$index]) {
                $oldAnswer = $question['answer'] ? 'نعم' : 'لا';
                $newAnswer = $answers[$index] ? 'نعم' : 'لا';
                $modifications[] = "- السؤال: \"{$question['label']}\" انتقل من {$oldAnswer} إلى {$newAnswer}";
                $question['answer'] = $answers[$index];
            }
        }

        // Update the MongoDB record with the new answers
        $mongoRecord->dimensions = $mongoData['dimensions'];
        $mongoRecord->save();

        // Recalculate the averagePointsPercentage
        $totalPercentagePoints = 0;
        $numDimensions = count($mongoData['dimensions']);

        foreach ($mongoData['dimensions'] as $dim) {
            $dimensionTotalPoints = 0;
            $dimensionNoPoints = 0;
            foreach ($dim['questions'] as $question) {
                $dimensionTotalPoints += $question['points'];
                if ($question['answer'] === false) {
                    $dimensionNoPoints += $question['points'];
                }
            }
            $totalDimensionPoints = ($dimensionNoPoints / $dimensionTotalPoints) * 100;
            $totalPercentagePoints += $totalDimensionPoints;
        }

        $averagePointsPercentage = $totalPercentagePoints / $numDimensions;

        $form = BeneficiaryForm::find($form_id);
        $form->total_points = $averagePointsPercentage;
        $form->save();

        if (!empty($modifications)) {
            $session_id = $request->input('session_id');
            $modificationLog = ModificationLog::firstOrNew(['session_id' => $session_id, 'beneficiary_form_id' => $form_id]);

            // Format the modifications string
            $formattedModifications = "اسم البعد: {$dimensionName}\n" . implode("\n", $modifications);

            // Append new modifications to existing ones
            if ($modificationLog->exists) {
                $existingModifications = $modificationLog->modifications;
                $modificationLog->modifications = $existingModifications . "\n" . $formattedModifications;
            } else {
                $modificationLog->modifications = $formattedModifications;
            }

            $modificationLog->average_points_percentage = (100-$averagePointsPercentage);
            $modificationLog->save();
        }

        return $this->returnSuccessMessage('تم التعديل بنجاح', 200);
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

    public function modificationLogs($beneficiaryFormId)
    {
        $validated = Validator::make(['beneficiary_form_id' => $beneficiaryFormId], [
            'beneficiary_form_id' => 'required|integer|exists:beneficiary_forms,id',
        ]);

        if ($validated->fails()) {

           $this->returnValidationError("لا يوجد استمارة",200);
        }

        $modificationLogs = ModificationLog::where('beneficiary_form_id', $beneficiaryFormId)
            ->orderBy('created_at', 'asc')
            ->get();

        $chartData = $modificationLogs->map(function($log) {
            return [
                'average_points_percentage' => $log->average_points_percentage,
                'created_at' => $log->created_at->toDateTimeString()
            ];
        });

        return $this->returnSuccessData($chartData,"تمت العملية بنجاح",200);
    }



}
