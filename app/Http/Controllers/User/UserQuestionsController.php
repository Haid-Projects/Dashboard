<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryAnswer;
use App\Models\BeneficiaryForm;
use App\Models\Dimension;
use App\Models\StateManager;
use App\Traits\GeneralTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewFormNotification;
use MongoDB\BSON\ObjectId;
use App\Jobs\HandleAdditionalDimensions;

class UserQuestionsController extends Controller
{
    use GeneralTrait;

    public function answerForm(Request $request)
    {
        $request->validate([
            'beneficiary_id' => 'required|integer|exists:beneficiaries,id',
            'illness_id' => 'required|integer|exists:illnesses,id',
            'dimension.dimension_id' => 'required|integer|exists:dimensions,id',
            'dimension.questions' => 'required|array',
            'answers' => 'required|array'
        ]);

        $dimension = Dimension::find($request->dimension['dimension_id']);
        $questions = $request->dimension['questions'];
        $answers = $request->answers;

        if (count($questions) != $request->dimension['questions_count'] || count($answers) != $request->dimension['questions_count']) {
            return $this->returnValidationError('عدد الإجابات لا يتطابق مع عدد الأسئلة', 400);
        }

        DB::beginTransaction();

        try {
            $form = BeneficiaryForm::firstOrCreate(
                [
                    'beneficiary_id' => $request->beneficiary_id,
                    'illness_id' => $request->illness_id
                ],
                [
                    'is_opened' => true,
                    'state_manager_notes' => null,
                    'specialist_notes' => null,
                    'total_points' => 0,
                ]
            );

            $totalPoints = 0;
            $noPoints = 0;
            $answerData = [];

            foreach ($questions as $index => $question) {
                $questionId = $question['id'];
                $answer = $answers[$index] ?? null;
                $points = $question['points'];
                $totalPoints += $points;
                if ($answer === false) {
                    $noPoints += $points;
                }

                $answerData[] = [
                    'question_id' => $questionId,
                    'label' => $question['label'],
                    'answer' => $answer,
                    'points' => $points,
                    'rank' => $question['rank']
                ];
            }

            // Calculate percentage of dimension points
            $totalDimensionPoints = ($noPoints / $totalPoints) * 100;

            $mongoRecord = BeneficiaryAnswer::where('beneficiary_form_id', $form->id)->first();

            $mongoData = $mongoRecord ? $mongoRecord->toArray() : [
                'beneficiary_form_id' => $form->id,
                'beneficiary_id' => $request->beneficiary_id,
                'illness_id' => $request->illness_id,
                'dimensions' => []
            ];

            $mongoData['dimensions'][] = [
                'dimension_id' => $dimension->id,
                'dimension_name' => $dimension->name,
                'questions' => $answerData,
            ];

            if ($mongoRecord) {
                $mongoRecord->update($mongoData);
            } else {
                $mongoRecord=BeneficiaryAnswer::create($mongoData);
            }
            $maxRankDimension = Dimension::where('illness_id', $request->illness_id)
                ->where('age_group', $dimension->age_group)
                ->orderBy('rank', 'desc')
                ->first();
            $form->form_id=$mongoRecord->_id;
            $form->save();
            if ($totalDimensionPoints >= $dimension->max_no || $dimension->id == $maxRankDimension->id) {
                HandleAdditionalDimensions::dispatch($form->id, $request->beneficiary_id, $request->illness_id, $dimension, $totalDimensionPoints);
                $response=$this->returnSuccessData(['up' => false], 'تم تسجيل طلبك بنجاح', 200);
            }
            else $response=$this->returnSuccessData(['up' => true], 'يمكنك الأنتقال الى البعد التالي', 200);

            DB::commit();

            return $response;
        } catch (Exception $e) {
            DB::rollBack();

            return $this->returnValidationError('حدث خطأ أثناء تسجيل الطلب: ' . $e->getMessage(), 500);
        }
    }


//    public function answerForm(Request $request)
//    {
//        $request->validate([
//            'beneficiary_id' => 'required|integer|exists:beneficiaries,id',
//            'illness_id' => 'required|integer|exists:illnesses,id',
//            'dimension.dimension_id' => 'required|integer|exists:dimensions,id',
//            'dimension.questions' => 'required|array',
//            'answers' => 'required|array'
//        ]);
//
//        $dimension = Dimension::find($request->dimension['dimension_id']);
//        $questions = $request->dimension['questions'];
//        $answers = $request->answers;
//
//        if (count($questions) != $request->dimension['questions_count'] || count($answers) != $request->dimension['questions_count']) {
//            return $this->returnValidationError('عدد الإجابات لا يتطابق مع عدد الأسئلة', 400);
//        }
//
//        DB::beginTransaction();
//
//        try {
//            $form = BeneficiaryForm::firstOrCreate(
//                [
//                    'beneficiary_id' => $request->beneficiary_id,
//                    'illness_id' => $request->illness_id
//                ],
//                [
//                    'is_opened' => true,
//                    'state_manager_notes' => null,
//                    'specialist_notes' => null,
//                    'total_points' => 0,
//                ]
//            );
//
//            $totalPoints = 0;
//            $noPoints = 0;
//            $answerData = [];
//
//            foreach ($questions as $index => $question) {
//                $questionId = $question['id'];
//                $answer = $answers[$index] ?? null;
//                $points = $question['points'];
//                $totalPoints += $points;
//                if ($answer === false) {
//                    $noPoints += $points;
//                }
//
//                $answerData[] = [
//                    'question_id' => $questionId,
//                    'label' => $question['label'],
//                    'answer' => $answer,
//                    'points' => $points,
//                    'rank' => $question['rank']
//                ];
//            }
//
//            // Calculate percentage of dimension points
//            $totalDimensionPoints = ($noPoints / $totalPoints) * 100;
//            $additionalDimensionsData = [];
//            $dimensionPercentagePoints = $totalDimensionPoints;
//
//            if ($totalDimensionPoints >=$dimension->max_no) {
//                $additionalDimensions = Dimension::where('illness_id', $request->illness_id)
//                    ->where('age_group', $dimension->age_group)
//                    ->where('rank', '>', $dimension->rank)
//                    ->orderBy('rank')
//                    ->get();
//
//                foreach ($additionalDimensions as $additionalDimension) {
//                    $additionalQuestions = $additionalDimension->questions()->orderBy('rank')->get();
//                    $questionsData = [];
//
//                    foreach ($additionalQuestions as $question) {
//                        $questionsData[] = [
//                            'question_id' => $question->id,
//                            'label' => $question->label,
//                            'answer' => false,
//                            'points' => $question->points,
//                            'rank' => $question->rank
//                        ];
//                    }
//
//                    $additionalDimensionsData[] = [
//                        'dimension_id' => $additionalDimension->id,
//                        'dimension_name' => $additionalDimension->name,
//                        'questions' => $questionsData,
//                        'max_no' => 100 // All questions will be 'no', so the percentage is 100%
//                    ];
//                }
//
//                $dimensionPercentagePoints += 100 * count($additionalDimensions);
//            }
//
//            $mongoRecord = BeneficiaryAnswer::where('beneficiary_form_id', $form->id)->first();
//
//            $mongoData = $mongoRecord ? $mongoRecord->toArray() : [
//                'beneficiary_form_id' => $form->id,
//                'beneficiary_id' => $request->beneficiary_id,
//                'illness_id' => $request->illness_id,
//                'dimensions' => []
//            ];
//
//            $mongoData['dimensions'][] = [
//                'dimension_id' => $dimension->id,
//                'dimension_name' => $dimension->name,
//                'questions' => $answerData,
//                'max_no' => abs($totalDimensionPoints) // Store percentage for current dimension
//            ];
//
//            foreach ($additionalDimensionsData as $additionalDimension) {
//                $mongoData['dimensions'][] = $additionalDimension;
//            }
//
//            if ($mongoRecord) {
//                $mongoRecord->update($mongoData);
//            } else {
//                $mongoRecord = BeneficiaryAnswer::create($mongoData);
//            }
//
//            // Sum of the percentage of each dimension
//            $form->total_points = $dimensionPercentagePoints;
//            $form->form_id = (string) ($mongoRecord->_id ?? new ObjectID());
//            $form->save();
//
//            $maxRankDimension = Dimension::where('illness_id', $request->illness_id)
//                ->where('age_group', $dimension->age_group)
//                ->orderBy('rank', 'desc')
//                ->first();
//
//            if (!empty($additionalDimensionsData) || $dimension->id == $maxRankDimension->id) {
//                $stateManagers = StateManager::all();
//                Notification::send($stateManagers, new NewFormNotification());
//                $response = $this->returnSuccessData(['up' => false], 'تم تسجيل طلبك بنجاح', 200);
//            } else {
//                $response = $this->returnSuccessData(['up' => true], 'يمكنك الأنتقال الى البعد التالي', 200);
//            }
//
//            DB::commit();
//
//            return $response;
//        } catch (\Exception $e) {
//            DB::rollBack();
//
//            return $this->returnValidationError('حدث خطأ أثناء تسجيل الطلب: ', 500);
//        }
//    }

    public function checkExistingForm(Request $request)
    {

        $request->validate([
            'beneficiary_id' => 'required|integer|exists:beneficiaries,id',
            'illness_id' => 'required|integer|exists:illnesses,id',
        ]);


        $existingForm = BeneficiaryForm::where('beneficiary_id', $request->beneficiary_id)
            ->where('illness_id', $request->illness_id)
            ->first();

        if ($existingForm) {
            return $this->returnSuccessData([
                'continue' => false
            ], 'عذراً المستفيد يمتلك استمارة من نفس النوع', 200);
        }

        return $this->returnSuccessData([
            'continue' => true
        ], '', 200);
    }




}


