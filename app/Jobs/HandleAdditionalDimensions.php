<?php

namespace App\Jobs;

use App\Models\BeneficiaryAnswer;
use App\Models\Dimension;
use App\Models\BeneficiaryForm;
use App\Models\ModificationLog;
use App\Models\StateManager;
use App\Notifications\NewFormNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class   HandleAdditionalDimensions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $formId;
    protected $beneficiaryId;
    protected $illnessId;
    protected $dimension;
    protected $totalDimensionPoints;

    public function __construct($formId, $beneficiaryId, $illnessId, $dimension, $totalDimensionPoints)
    {
        $this->formId = $formId;
        $this->beneficiaryId = $beneficiaryId;
        $this->illnessId = $illnessId;
        $this->dimension = $dimension;
        $this->totalDimensionPoints = $totalDimensionPoints;
    }

    public function handle()
    {
        $additionalDimensionsData = [];
        $dimensionPercentagePoints = $this->totalDimensionPoints;

        if ($dimensionPercentagePoints >= $this->dimension->max_no) {
            $additionalDimensions = Dimension::where('illness_id', $this->illnessId)
                ->where('age_group', $this->dimension->age_group)
                ->where('rank', '>', $this->dimension->rank)
                ->orderBy('rank')
                ->get();

            foreach ($additionalDimensions as $additionalDimension) {
                $additionalQuestions = $additionalDimension->questions()->orderBy('rank')->get();
                $questionsData = [];

                foreach ($additionalQuestions as $question) {
                    $questionsData[] = [
                        'question_id' => $question->id,
                        'label' => $question->label,
                        'answer' => false,
                        'points' => $question->points,
                        'rank' => $question->rank
                    ];
                }

                $additionalDimensionsData[] = [
                    'dimension_id' => $additionalDimension->id,
                    'dimension_name' => $additionalDimension->name,
                    'questions' => $questionsData,
                ];
            }
        }

        $mongoRecord = BeneficiaryAnswer::where('beneficiary_form_id', $this->formId)->first();
        $mongoData = $mongoRecord ? $mongoRecord->toArray() : [
            'beneficiary_form_id' => $this->formId,
            'beneficiary_id' => $this->beneficiaryId,
            'illness_id' => $this->illnessId,
            'dimensions' => []
        ];

        foreach ($additionalDimensionsData as $additionalDimension) {
            $mongoData['dimensions'][] = $additionalDimension;
        }

        if ($mongoRecord) {
            $mongoRecord->update($mongoData);
        } else {
            $mongoRecord= BeneficiaryAnswer::create($mongoData);
        }


        $totalPoints = 0;
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
            echo $totalDimensionPoints;
            $totalPoints += $totalDimensionPoints;
        }

        $averagePointsPercentage = $totalPoints / $numDimensions;

        $form = BeneficiaryForm::find($this->formId);
        $form->total_points = $averagePointsPercentage;
        $form->hidden=false;
        $form->form_id=$mongoRecord->_id;
        $form->save();
        $modificationLog = ModificationLog::create([
            'beneficiary_form_id'=>$form->id,
            'modifications'=>"first time",
            'average_points_percentage'=>(100-$averagePointsPercentage),
        ]);


        $stateManagers = StateManager::all();
        Notification::send($stateManagers, new NewFormNotification(null,5));
    }
}
