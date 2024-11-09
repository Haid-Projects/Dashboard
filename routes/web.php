<?php

use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\FormsController;
use App\Http\Controllers\Admin\ProgramsController;
use App\Http\Controllers\Admin\Reports\SpecialistsReportsController;
use App\Http\Controllers\Admin\Reports\StateManagersReportsController;
use App\Http\Controllers\Admin\Reports\BeneficiariesReportsController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\LibraryController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get("main", [StatisticsController::class, 'index'])->name('main');

Route::get("forms",[FormsController::class, 'forms']);


Route::get("state_managers", [EmployeesController::class, 'state_managers'])->name('state_managers');
Route::get("specialists", [EmployeesController::class, 'specialists'])->name('specialists');
Route::get("services", [ProgramsController::class, 'services'])->name('services');
Route::get("illnesses/{service_id}", [ProgramsController::class, 'illnesses'])->name('illnesses');
Route::get("dimensions/{illness_id}", [ProgramsController::class, 'dimensions'])->name('dimensions');
Route::get("questions/{dimension_id}", [ProgramsController::class, 'test'])->name('questions');

Route::post("createIllness/{service_id}", [ProgramsController::class, 'createIllness'])->name('createIllness');
Route::post("editIllness/{illness_id}", [ProgramsController::class, 'editIllness'])->name('editIllness');
Route::get("deleteIllness/{illness_id}", [ProgramsController::class, 'deleteIllness'])->name('deleteIllness');

Route::post("createDimension/{illness_id}", [ProgramsController::class, 'createDimension'])->name('createDimension');
Route::post("editDimension/{dimension_id}", [ProgramsController::class, 'editDimension'])->name('editDimension');
Route::get("deleteDimension/{dimension_id}", [ProgramsController::class, 'deleteDimension'])->name('deleteDimension');

Route::post("createService", [ProgramsController::class, 'create'])->name('createService');
Route::post("editService/{service_id}", [ProgramsController::class, 'editService'])->name('editService');
Route::get("deleteService/{service_id}", [ProgramsController::class, 'deleteService'])->name('deleteService');

Route::post("createQuestion/{dimension_id}", [ProgramsController::class, 'createQuestion'])->name('createQuestion');
Route::post("editQuestion/{question_id}", [ProgramsController::class, 'editQuestion'])->name('editQuestion');
Route::get("deleteQuestion/{question_id}", [ProgramsController::class, 'deleteQuestion'])->name('deleteQuestion');

Route::get("deleteSpecialist/{specialist_id}", [EmployeesController::class, 'deleteSpecialist'])->name('deleteSpecialist');
Route::get("deleteStateManager/{state_manager_id}", [EmployeesController::class, 'deleteStateManager'])->name('deleteStateManager');

Route::post("createSpecialist", [EmployeesController::class, 'createSpecialist'])->name('createSpecialist');
Route::post("editSpecialist/{specialist_id}", [EmployeesController::class, 'editSpecialist'])->name('editSpecialist');
Route::post("createStateManager", [EmployeesController::class, 'createStateManager'])->name('createStateManager');
Route::post("editStateManager/{state_manager_id}", [EmployeesController::class, 'editStateManager'])->name('editStateManager');


Route::get("subjects", [LibraryController::class, 'subjects'])->name('subjects');
Route::post("createSubject", [LibraryController::class, 'createSubject'])->name('createSubject');
Route::post("editSubject/{subject_id}", [LibraryController::class, 'editSubject'])->name('editSubject');
Route::get("deleteSubject/{subject_id}", [LibraryController::class, 'deleteSubject'])->name('deleteSubject');

Route::get("main_titles/{subject_id}", [LibraryController::class, 'mainTitles'])->name('main_titles');
Route::post("createMainTitle/{subject_id}", [LibraryController::class, 'createMainTitle'])->name('createMainTitle');
Route::post("editMainTitle/{main_title_id}", [LibraryController::class, 'editMainTitle'])->name('editMainTitle');
Route::get("deleteMainTitle/{main_title_id}", [LibraryController::class, 'deleteMainTitle'])->name('deleteMainTitle');

Route::get("paragraphs/{main_title_id}", [LibraryController::class, 'paragraphs'])->name('paragraphs');
Route::post("createParagraph/{main_title_id}", [LibraryController::class, 'createParagraph'])->name('createParagraph');
Route::post("editParagraph/{paragraph_id}", [LibraryController::class, 'editParagraph'])->name('editParagraph');
Route::get("deleteParagraph/{paragraph_id}", [LibraryController::class, 'deleteParagraph'])->name('deleteParagraph');

Route::get("medias/{paragraph_id}", [LibraryController::class, 'medias'])->name('medias');
Route::post("createMedia/{paragraph_id}", [LibraryController::class, 'createMedia'])->name('createMedia');
Route::post("editMedia/{media_id}", [LibraryController::class, 'editMedia'])->name('editMedia');
Route::get("deleteMedia/{media_id}", [LibraryController::class, 'deleteMedia'])->name('deleteMedia');


Route::get('notifications', [AdminNotificationController::class, 'notifications'])->name('notifications')->middleware('auth:admin');

Route::get("specialist_reports", [SpecialistsReportsController::class, 'sortByRate'])->name('specialist_reports');
Route::get("generateSpecialistsReportPdf", [SpecialistsReportsController::class, 'generatePdf'])->name('generateSpecialistsReportPdf');
Route::post('/generate-pdf', [SpecialistsReportsController::class, 'generatePDF']);
Route::get('/img2pdf', [SpecialistsReportsController::class, 'image2pdf']);


Route::get("state_manager_reports", [StateManagersReportsController::class, 'reports'])->name('state_manager_reports');
Route::get("beneficiary_reports/{beneficiary_form_id}", [BeneficiariesReportsController::class, 'reports'])->name('beneficiary_reports');



Route::get("t1/{illness_id}", [SpecialistsReportsController::class, 'sortByIllness'])->name('sortByIllness');
Route::get("t2", [SpecialistsReportsController::class, 'closedFormsForEachOne'])->name('closedFormsForEachOne');
Route::get("t3", [SpecialistsReportsController::class, 'openedFormsForEachOne'])->name('openedFormsForEachOne');
Route::get("t4", [SpecialistsReportsController::class, 'mostAbsentBeneficiaries'])->name('mostAbsentBeneficiaries');


Route::get("tb", function(){
    return view('dashboard.test');
});

Route::get('tele', [\App\Http\Controllers\TelegramMessageController::class, 'sendTelegramMessage']);
require __DIR__.'/auth.php';
