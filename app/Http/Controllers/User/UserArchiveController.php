<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BeneficiaryAnswer;
use App\Models\BeneficiaryForm;
use App\Models\ReReviewRequest;
use App\Notifications\ReReviewRequestNotification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class UserArchiveController extends Controller
{
    use GeneralTrait;
    public function Archive(){
        $user_id = Auth::guard('user')->user()->id;
        $forms = BeneficiaryForm::with(['beneficiary:id,full_name', 'illness:id,name'])
            ->whereHas('beneficiary', function ($query) use ($user_id) {
                $query->where('user_id', $user_id);
            })
            ->whereNotNull('state_manager_id')
            ->where('beneficiary_forms.hidden', '=', false)
            ->orderBy('beneficiary_id')
            ->orderBy('created_at')
            ->get()
            ->map(function ($form) {
                return [
                    'id'=>$form->id,
                    'beneficiary_name' => $form->beneficiary->full_name,
                    'illness_name' => $form->illness->name,
                    'date' => $form->created_at->toDateString()
                ];
            });
        return $this->returnSuccessData($forms,"تمت العملية بنجاح",200);

    }

    public function beneficiaryFormDetails($form_id)
    {
        $form = BeneficiaryForm::with(['beneficiary', 'illness', 'specialist', 'stateManager'])
            ->where('id', '=', $form_id)
            ->first();

        if ($form) {
            $BeneficiaryAnswer = BeneficiaryAnswer::where('_id', '=', $form->form_id)->first();

            $additionalDimensionsData = [];
            foreach ($BeneficiaryAnswer['dimensions'] as $dimension) {
                $dimensionData = [
                    'dimension_id' => $dimension['dimension_id'],
                    'dimension_name' => $dimension['dimension_name'],
                    'questions_count' => count($dimension['questions']),
                    'questions' => $dimension['questions'],
                ];
                $additionalDimensionsData[] = $dimensionData;
            }

            $data = [
                'full_name' => $form->beneficiary->full_name,
                'illness_name' => $form->illness->name,
                'specialist_name' => $form->specialist->name ?? null,
                'state_manager_name' => $form->stateManager->name ?? null,
                'created_at' => $form->created_at,
                'deleted_at' => $form->deleted_at,
                'specialist_notes' => $form->specialist_notes,
                'rank' => $form->rank,
                'dimensions_count' => count($BeneficiaryAnswer['dimensions']),
                'dimensions' => $additionalDimensionsData
            ];

            return $this->returnSuccessData($data, "تمت العملية بنجاح", 200);
        } else {
            return $this->returnErrorMessage("لم يتم العثور على نموذج مستفيد بهذه الهوية", 404);
        }
    }

    public function ReeeReview(Request $request, $beneficiary_form_id)
    {
        $request->validate([
            'note' => 'required|string',
        ]);

        $beneficiaryForm = BeneficiaryForm::findOrFail($beneficiary_form_id);
        if ($beneficiaryForm->is_opened == 1) {
            return $this->returnErrorMessage("لا يمكن اعادة النظر الأستمارة غير مغلقة بعد", 404);
        }
        $user = Auth::guard('user')->user();
        $existingRequest = ReReviewRequest::where('beneficiary_form_id', $beneficiary_form_id)
            ->where('user_id', $user->id)
            ->first();
        if ($existingRequest) {
            return $this->returnErrorMessage("لقد قمت بالفعل بإرسال طلب إعادة النظر لهذه الاستمارة", 400);
        }
        $reReviewRequest = ReReviewRequest::create([
            'beneficiary_form_id' => $beneficiaryForm->id,
            'user_id' => $user->id,
            'note' => $request->note,
        ]);
        $admins = Admin::all();
        if ($admins->isNotEmpty()) {
            Notification::send($admins, new ReReviewRequestNotification($reReviewRequest));
        }

        return $this->returnSuccessMessage('تم ارسال الطلب بنجاح', 200);
    }

    public function ReReview(Request $request, $beneficiary_form_id)
    {
        // Get MongoDB Server Version
        $serverInfo = DB::connection('mongodb')
            ->getMongoClient()
            ->selectDatabase('admin')
            ->command(['buildInfo' => 1])
            ->toArray();
        $serverVersion = $serverInfo[0]['version'];

        // Get jenssegers/mongodb Package Version
        $composerFile = file_get_contents(base_path('composer.lock'));
        $composerData = json_decode($composerFile, true);

        $packageVersion = null;
        foreach ($composerData['packages'] as $package) {
            if ($package['name'] == 'jenssegers/mongodb') {
                $packageVersion = $package['version'];
                break;
            }
        }

        return response()->json([
            'MongoDB Server Version' => $serverVersion,
            'jenssegers/mongodb Package Version' => $packageVersion,
        ]);
    }


}
