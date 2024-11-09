<?php

namespace App\Http\Controllers\StateManager;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Event;
use App\Models\Participant;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Faker\Extension\GeneratorAwareExtensionTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EventNotification;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    use GeneralTrait;
    /**
     * Create a new Event
     */

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'date' => 'required',
            'time' => 'required',
            'type' => 'required',
            'location' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->returnValidationError('missing some credentials', 400);
        }

        $state_manager = Auth::guard('state_manager')->user();
        $event = Event::create([
            'state_manager_id' => $state_manager->id,
            'name' => $request->name,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        if ($request->type === "public") {
            $all_beneficiaries = Beneficiary::with('user')->get();
            $users = $all_beneficiaries->groupBy('user_id');

            $participants = [];
            foreach ($all_beneficiaries as $beneficiary) {
                $participants[] = [
                    'event_id' => $event->id,
                    'beneficiary_id' => $beneficiary->id,
                ];
            }
            Participant::insert($participants);

            foreach ($users as $user_id => $beneficiaries) {
                Notification::send($beneficiaries->first()->user, new EventNotification($event, $beneficiaries, 'public',1));
            }
        } else {
            $beneficiaries = Beneficiary::with('user')->whereIn('id', $request->beneficiaries)->get();
            $users = $beneficiaries->groupBy('user_id');
            $participants = [];
            foreach ($beneficiaries as $beneficiary) {
                $participants[] = [
                    'event_id' => $event->id,
                    'beneficiary_id' => $beneficiary->id,
                ];
            }
            Participant::insert($participants);
            foreach ($users as $user_id => $user_beneficiaries) {
             Notification::send($user_beneficiaries->first()->user, new EventNotification($event, $user_beneficiaries, 'private',1));
            }
        }
        return $this->returnSuccessData($event, 'Event created successfully', 200);
    }

    /**
     * Get all the created events of a state manager
     */
    public function createdEvents(){
        $state_manager = Auth::guard('state_manager')->user();
        $events = Event::where('state_manager_id', $state_manager->id)->get();
        return $this->returnSuccessData($events, 'all events',200);
    }
    /**
    * Get all the created events of a state manager
    */
    public function comingEvents(){
        $state_manager = Auth::guard('state_manager')->user();
        $events = Event::query()->whereDate('date', '>', Carbon::now())->get();
        return $this->returnSuccessData($events, 'coming events',200);
    }

    /**
    * Get all the created events of a state manager
    */
    public function pastEvents(){
        $state_manager = Auth::guard('state_manager')->user();
        $events = Event::query()->whereDate('date', '<', Carbon::now())->get();
        return $this->returnSuccessData($events, 'past events',200);
    }

    /**
     * Get event details
     */
    public function eventDetails( $event_id){
        $event = Event::find($event_id);
        return $this->returnSuccessData($event, 'event details',200);
    }
/**
     * Invite people to an event
     */
    public function invite(Request $request, $event_id){
        //validation
        $validator = Validator::make($request->all(), [
            'beneficiaries' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        foreach($request->beneficiaries as $beneficiary){
            Participant::create([
                'event_id' => $event_id,
                'beneficiary_id' => $beneficiary
            ]);
            // send notifications to the users to attend the event
        }
        return $this->returnSuccessMessage('beneficiaries invited successfully',200);
    }

    /**
     * Take attendance for beneficiaries
     */
    public function takeAttendance(Request $request, $event_id){
        //validation
        $validator = Validator::make($request->all(), [
            'beneficiaries' => 'required',
        ]);
        if($validator->fails()) {
            return $this->returnValidationError('missing some credentials',400);
        }
        foreach($request->beneficiaries as $beneficiary){
            // take attendance to beneficiaries that attended the event
            $participant = Participant::where([
                            'event_id' => $event_id,
                            'beneficiary_id' => $beneficiary,
                             ])->first();
            $participant->did_com = true;
            $participant->save();
        }
        return $this->returnSuccessMessage('attendance has been taken successfully',200);
    }

    /**
     * Get all beneficiaries
     */
    public function allBeneficiaries(){
        $beneficiaries = Beneficiary::where('socially_integrable',true)->select('id', 'full_name')->get();
        return $this->returnSuccessData($beneficiaries, "all beneficiaries " , 200);
    }
    /**
     * Get all beneficiaries that are invited to an event
     */
    public function invitedBeneficiaries($event_id){
        $invited_beneficiaries = DB::table('participants')
                                ->join('beneficiaries', 'beneficiaries.id', '=', 'participants.beneficiary_id')
                                ->where('participants.event_id', '=', $event_id)
                                ->select('beneficiaries.full_name', 'participants.id')
                                ->orderBy('beneficiaries.full_name', 'asc')
                                ->get();
        return $this->returnSuccessData($invited_beneficiaries, "invited people to the event", 200);
    }
}
