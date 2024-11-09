<?php

namespace Database\Seeders;


use App\Models\Dimension;
use App\Models\Illness;
use App\Models\Question;
use App\Models\Service;
use App\Models\Specialist;
use App\Models\StateManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\User::factory(1)->create();

//         \App\Models\User::factory()->create([
//             'name' => 'Test User',
//             'email' => 'test@example.com',
//         ]);
        Specialist::create([
            'name' => 'sp',
            'username' => 's1',
            'password' => Hash::make('1234'),
            'phone_number' => '0987654321'
        ]);
        Specialist::create([
            'name' => 'sp2',
            'username' => 's2',
            'password' => Hash::make('1234'),
            'phone_number' => '0987654321'
        ]);
        StateManager::create([
            'name' => 'sm',
            'username' => 's1',
            'password' => Hash::make('1234')
        ]);
        // Services
        $services = [
            ['name' => 'احتياجات خاصة'],
            ['name' => 'مشاكل اجتماعية']
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $illnesses = [
            'احتياجات خاصة' => [
                'توحد', 'تخلف عقلي', 'متلازمة داون', 'نقص السمع', 'نقص الرؤية'
            ],
            'مشاكل اجتماعية' => [
                'اضطراب السلوك', 'الاكتئاب', 'القلق', 'الفصام', 'الإدمان'
            ]
        ];

        $ageGroups = [
            1 => ['التواصل البصري', 'التقليد', 'الادراك', 'رعاية الذات', 'مهارات اجتماعية'],
            2 => ['التواصل البصري', 'التقليد', 'الادراك', 'رعاية الذات', 'مهارات اجتماعية', 'صعوبات قراءة'],
            3 => ['التواصل البصري', 'التقليد', 'الادراك', 'رعاية الذات', 'مهارات اجتماعية', 'صعوبات قراءة', 'صعوبات كتابة', 'صعوبات تعلم']
        ];

        $socialDimensions = [
            1 => ['التعامل مع الضغوط', 'التكيف الاجتماعي', 'الاتصال الشخصي', 'حل المشكلات', 'الدعم العاطفي'],
            2 => ['التعامل مع الضغوط', 'التكيف الاجتماعي', 'الاتصال الشخصي', 'حل المشكلات', 'الدعم العاطفي', 'التفاعل الاجتماعي'],
            3 => ['التعامل مع الضغوط', 'التكيف الاجتماعي', 'الاتصال الشخصي', 'حل المشكلات', 'الدعم العاطفي', 'التفاعل الاجتماعي', 'التعبير عن الذات', 'الوعي الذاتي']
        ];

        $questionsCount = [
            1 => 5,
            2 => 7,
            3 => 8
        ];

        foreach ($services as $service) {
            $serviceModel = Service::where('name', $service['name'])->first();
            foreach ($illnesses[$service['name']] as $illness) {
                $illnessModel = Illness::create([
                    'name' => $illness,
                    'service_id' => $serviceModel->id
                ]);

                $dimensions = $service['name'] === 'احتياجات خاصة' ? $ageGroups : $socialDimensions;

                foreach ($dimensions as $ageGroup => $dimensionList) {
                    $rank = 1;
                    foreach ($dimensionList as $dimension) {
                        $dimensionModel = Dimension::create([
                            'name' => $dimension,
                            'illness_id' => $illnessModel->id,
                            'age_group' => $ageGroup,
                            'max_no' => 80,
                            'rank' => $rank++
                        ]);

                        for ($i = 1; $i <= $questionsCount[$ageGroup]; $i++) {
                            Question::create([
                                'label' => "$dimension - سؤال $i",
                                'rank' => $i,
                                'points' => rand(1, 10),
                                'dimension_id' => $dimensionModel->id
                            ]);
                        }
                    }
                }
            }
        }
    }
//        $service1 = Service::create(['name' => 'احتياجات خاصة']);
//
//        $illness1 = Illness::create([
//            'name' => 'توحد',
//            'icon' => 'icon1.png',
//            'service_id' => $service1->id,
//        ]);
//        $this->createDimensions($illness1,1);
//
//        $illness2 = Illness::create([
//            'name' => 'متلازمة داون',
//            'icon' => 'icon2.png',
//            'service_id' => $service1->id,
//        ]);
//        $this->createDimensions($illness2,2);
//
//        $illness3 = Illness::create([
//            'name' => 'تخلف عقلي',
//            'icon' => 'icon3.png',
//            'service_id' => $service1->id,
//        ]);
//        $this->createDimensions($illness3,1);
//
//        $illness4 = Illness::create([
//            'name' => 'نقص السمع',
//            'icon' => 'icon4.png',
//            'service_id' => $service1->id,
//        ]);
//        $this->createDimensions($illness4,1);
//
//        $illness5 = Illness::create([
//            'name' => 'نقص النمو',
//            'icon' => 'icon5.png',
//            'service_id' => $service1->id,
//        ]);
//        $this->createDimensions($illness5,2);
//
//        // Create servic2 and its illnesses
//        $service2 = Service::create(['name' => 'مشاكل اجتماعية']);
//
//        $illness6 = Illness::create([
//            'name' => 'طفل معنف',
//            'icon' => 'icon1.png',
//            'service_id' => $service2->id,
//        ]);
//        $this->createDimensions($illness6,1);
//
//        $illness7 = Illness::create([
//            'name' => 'امرأة معنفة',
//            'icon' => 'icon2.png',
//            'service_id' => $service2->id,
//        ]);
//        $this->createDimensions($illness7,1);
//    }
//
//    private function createDimensions(Illness $illness,$age)
//    {
//        $dimension1 = Dimension::create([
//            'name' =>'البعد البصري',
//            'illness_id' => $illness->id,
//            'tips' => 'Some tips for Dimension 1',
//            'rank' => 1,
//            'age_group' => $age,
//            'max_no' => 3,
//        ]);
//        $this->createQuestions($dimension1);
//
//        $dimension2 = Dimension::create([
//            'name' => 'البعد السمعي',
//            'illness_id' => $illness->id,
//            'tips' => 'Some tips for Dimension 2',
//            'rank' => 2,
//            'age_group' => $age,
//            'max_no' => 4,
//        ]);
//        $this->createQuestions($dimension2);
//
//        $dimension3 = Dimension::create([
//            'name' => 'البعد الحركي',
//            'illness_id' => $illness->id,
//            'tips' => 'Some tips for Dimension 3',
//            'rank' => 3,
//            'age_group' => $age,
//            'max_no' => 2,
//        ]);
//        $this->createQuestions($dimension3);
//
//        $dimension4 = Dimension::create([
//            'name' => 'البعد النطقي',
//            'illness_id' => $illness->id,
//            'tips' => 'Some tips for Dimension 4',
//            'rank' => 4,
//            'age_group' => $age,
//            'max_no' => 40,
//        ]);
//        $this->createQuestions($dimension4);
//
//        $dimension5 = Dimension::create([
//            'name' => 'البعد الحسي',
//            'illness_id' => $illness->id,
//            'tips' => 'Some tips for Dimension 5',
//            'rank' => 5,
//            'age_group' => $age,
//            'max_no' => 1,
//        ]);
//        $this->createQuestions($dimension5);
//    }
//
//    private function createQuestions(Dimension $dimension)
//    {
//        Question::create([
//            'label' => 'Question 1 for ' . $dimension->name,
//            'rank' => 1,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 2 for ' . $dimension->name,
//            'rank' => 2,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 3 for ' . $dimension->name,
//            'rank' => 3,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 4 for ' . $dimension->name,
//            'rank' => 4,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 5 for ' . $dimension->name,
//            'rank' => 5,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 6 for ' . $dimension->name,
//            'rank' => 6,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 7 for ' . $dimension->name,
//            'rank' => 7,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 8 for ' . $dimension->name,
//            'rank' => 8,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 9 for ' . $dimension->name,
//            'rank' => 9,
//            'points' => rand(1, 5),
//            'dimension_id' => $dimension->id,
//        ]);
//
//        Question::create([
//            'label' => 'Question 10 for ' . $dimension->name,
//            'rank' => 10,
//            'points' => rand(1,5),
//            'dimension_id' => $dimension->id,
//        ]);
//    }


}
