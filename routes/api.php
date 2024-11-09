<?php

use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\User\UserQuestionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\StateManagerAuthController;
use App\Http\Controllers\Auth\SpecialistAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\User\UserBeneficiaryController;
use App\Http\Controllers\User\UserHomeController;
use App\Http\Controllers\StateManager\EventController;
use App\Http\Controllers\StateManager\ReviewBeneficiaryFormController;
use App\Http\Controllers\Specialist\SessionController;
use App\Http\Controllers\Specialist\ReviewFormController;
use App\Http\Controllers\User\UserArchiveController;
use App\Http\Controllers\User\UserLibraryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * User Routes
 */

Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('user/login', [UserAuthController::class, 'login']);
Route::post('/send-otp', [UserAuthController::class, 'sendOtp']);
Route::post('/verify-otp', [UserAuthController::class, 'verifyOtp']);

Route::group(['prefix' => 'user','middleware' => ['assign.guard:user']],function() {
    Route::get('logout', [UserAuthController::class, 'logout']);
    Route::get('profile', [UserAuthController::class, 'profile']);
    Route::post('/update_fcm_token', [UserAuthController::class, 'update_fcm_token']);
    //beneficiaries routes
    Route::post('createBeneficiary', [UserBeneficiaryController::class, 'createBeneficiary']);
    Route::get('myBeneficiaries', [UserBeneficiaryController::class, 'myBeneficiaries']);
    Route::get('beneficiaryInfo/{beneficiary_id}', [UserBeneficiaryController::class, 'beneficiaryInfo']);
    Route::get('beneficiarySessions/{beneficiary_id}', [UserBeneficiaryController::class, 'beneficiarySessions']);
    Route::get('sessionDetails/{session_id}', [UserBeneficiaryController::class, 'sessionDetails']);
    Route::get('comingSessions/{beneficiary_id}', [UserBeneficiaryController::class, 'comingSessions']);
    //home page routes
    Route::get('services', [UserHomeController::class, 'services']);
    Route::get('illnesses/{service_id}', [UserHomeController::class, 'illnesses']);
    Route::get('dimensions/', [UserHomeController::class, 'dimensions']);
    Route::get('questions/{illness_id}', [UserHomeController::class, 'questions']);

    Route::get('eventDetails/{event_id}', [EventController::class, 'eventDetails']);
   // Route::get('sessionDetails/{session_id}', [SessionController::class, 'sessionDetails']);
    Route::post('addNotesToSession/{form_id}', [UserBeneficiaryController::class, 'addNotesToSession']);
    Route::post('addRateToSession/{form_id}', [UserBeneficiaryController::class, 'addRateToSession']);
    Route::get('modification/{session_id}',[SessionController::class,'Modification']);
    /////////////////////////////////////////////////////////////
    Route::post('answer-form', [UserQuestionsController::class, 'answerForm']);
    Route::post('/check-existing-form', [UserQuestionsController::class, 'checkExistingForm']);


    //Archive route
    Route::get('Archive', [UserArchiveController::class, 'Archive']);
    Route::get('beneficiaryFormDetails/{form_id}', [UserArchiveController::class, 'beneficiaryFormDetails']);
    Route::post('re-review/{beneficiary_form_id}',[UserArchiveController::class,'ReReview']);
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);

    //library routes
    Route::get('subjects', [UserLibraryController::class, 'subjects'])->name('subjects');
    Route::get('mainTitles/{subject_id}', [UserLibraryController::class, 'mainTitles'])->name('mainTitles');
    Route::get('content/{main_title_id}', [UserLibraryController::class, 'content'])->name('content');
});

/**
 * State Manager Routes
 */
Route::post('state_manager/login', [StateManagerAuthController::class, 'login']);

Route::group(['prefix' => 'state_manager','middleware' => ['assign.guard:state_manager']],function() {
    Route::get('logout', [StateManagerAuthController::class, 'logout']);
    //Route::get('profile', [StateManagerAuthController::class, 'profile']);
    Route::post('/update_fcm_token', [StateManagerAuthController::class, 'update_fcm_token']);

    //events routes
    Route::post('createEvent', [EventController::class, 'create']);
    Route::get('allEvents', [EventController::class, 'createdEvents']);
    Route::get('comingEvents', [EventController::class, 'comingEvents']);
    Route::get('pastEvents', [EventController::class, 'pastEvents']);
    Route::get('eventDetails/{event_id}', [EventController::class, 'eventDetails']);

    Route::get('allBeneficiaries', [EventController::class, 'allBeneficiaries']);
    Route::get('invitedBeneficiaries/{event_id}', [EventController::class, 'invitedBeneficiaries']);

    Route::post('invite/{event_id}', [EventController::class, 'invite']);
    Route::post('takeAttendance/{event_id}', [EventController::class, 'takeAttendance']);

    //dealing with beneficiaries routes
    Route::get('newForms', [ReviewBeneficiaryFormController::class, 'newForms']);
    Route::get('closedForms', [ReviewBeneficiaryFormController::class, 'closedForms']);
    Route::get('activeForms', [ReviewBeneficiaryFormController::class, 'activeForms']);
    Route::get('rejectForm/{form_id}', [ReviewBeneficiaryFormController::class, 'rejectForm']);
    Route::post('manageForm/{from_id}', [ReviewBeneficiaryFormController::class, 'manageForm']);
    Route::post('closeForm/{form_id}', [ReviewBeneficiaryFormController::class, 'closeForm']);
    Route::post('updateBeneficiary/{beneficiary_id}', [ReviewBeneficiaryFormController::class, 'updateBeneficiary']);
    Route::get('specialists/{form_id}', [ReviewBeneficiaryFormController::class, 'specialists']);
    Route::get('beneficiaryInformation/{beneficiary_id}', [ReviewBeneficiaryFormController::class, 'beneficiaryInformation']);
    Route::get('beneficiaryOldSessions/{beneficiary_form_id}', [SessionController::class, 'beneficiaryOldSessions']);
    Route::post('addNotesToForm/{form_id}', [ReviewBeneficiaryFormController::class, 'addNotesToForm']);
    Route::get('beneficiaryHealthInformation/{beneficiary_id}',[ReviewBeneficiaryFormController::class,'beneficiaryHealthInformation']);
    Route::get('beneficiaryDimensionsData/{form_id}', [ReviewBeneficiaryFormController::class, 'beneficiaryDimensionsData']);
    Route::get('beneficiaryResult/{form_id}', [ReviewBeneficiaryFormController::class, 'beneficiaryResult']);
    Route::post('changeSpecialist/{form_id}', [ReviewBeneficiaryFormController::class, 'changeSpecialist']);
    Route::get('rank', [ReviewBeneficiaryFormController::class, 'rank']);
    Route::get('modification/{session_id}',[SessionController::class,'Modification']);
    //dealing with forms
    //////////////////////////////
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);


});


/**
 * Specialist Routes
 */

Route::post('specialist/login', [SpecialistAuthController::class, 'login']);

Route::group(['prefix' => 'specialist','middleware' => ['assign.guard:specialist']],function(){
    Route::get('logout', [SpecialistAuthController::class, 'logout']);
    //Route::get('profile', [UserAuthController::class, 'profile']);
    Route::post('/update_fcm_token', [SpecialistAuthController::class, 'update_fcm_token']);

    //session routes
    Route::post('createSession/{beneficiary_form_id}', [SessionController::class, 'createSession']);
    Route::delete('deleteSession/{session_id}', [SessionController::class, 'deleteSession']);
    Route::get('sessionDetails/{session_id}', [SessionController::class, 'sessionDetails']);
    Route::post('addNotesToSession/{session_id}', [SessionController::class, 'addNotesToSession']);
    Route::post('takeAttendance/{session_id}', [SessionController::class, 'takeAttendance']);
    Route::get('beneficiaryOldSessions/{beneficiary_form_id}', [SessionController::class, 'beneficiaryOldSessions']);
    //appointments routes
    Route::get('appointmentsToday', [SessionController::class, 'appointmentsToday']);
    Route::get('appointments', [SessionController::class, 'appointments']);
    Route::get('appointmentsByDate/{date}', [SessionController::class, 'appointmentsByDate']);

    //dealing with beneficiaries routes
    Route::get('newForms', [ReviewFormController::class, 'newForms']);
    Route::get('closedForms', [ReviewFormController::class, 'closedForms']);
    Route::get('activeForms', [ReviewFormController::class, 'activeForms']);
    Route::get('beneficiaryInformation/{beneficiary_form_id}', [ReviewFormController::class, 'beneficiaryInformation']);
    Route::post('addNotesToForm/{session_id}', [ReviewFormController::class, 'addNotesToForm']);
    Route::get('beneficiaryHealthInformation/{beneficiary_id}',[ReviewBeneficiaryFormController::class,'beneficiaryHealthInformation']);
    Route::get('beneficiaryDimensionsData/{form_id}', [ReviewFormController::class, 'beneficiaryDimensionsData']);
    Route::post('updateDimension/{form_id}',[ReviewFormController::class,'updateDimension']);
    Route::get('modificationLogs/{beneficiaryFormId}', [ReviewFormController::class, 'modificationLogs']);
    Route::post('toggleSociallyIntegrable/{beneficiary_id}',[SessionController::class,'toggleSociallyIntegrable']);
    Route::get('modification/{session_id}',[SessionController::class,'Modification']);
    /////////////////////
    Route::get('/notifications', [NotificationController::class, 'getNotifications']);
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
});


/**
 * Admin Routes
 */
