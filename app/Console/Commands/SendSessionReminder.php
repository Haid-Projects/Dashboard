<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Session;
use App\Models\User;
use App\Notifications\NewSessionNotification;
use Carbon\Carbon;

class SendSessionReminder extends Command
{
    protected $signature = 'send:session-reminder';
    protected $description = 'Send a session reminder notification two hours before the session time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $twoHoursLater = $now->copy()->addHours(2);

        $sessions = Session::where('date', $now->toDateString())
            ->where('time', '>=', $now->format('H:i:s'))
            ->where('time', '<=', $twoHoursLater->format('H:i:s'))
            ->where('notification_sent', false)
            ->get();

        foreach ($sessions as $session) {
            $user = $session->beneficiaryForm->beneficiary->user;
            $user->notify(new NewSessionNotification($session, 'لقد اقترب موعد جلستك', 'session_reminder',6));

            $session->notification_sent = true;
            $session->save();
        }

        return Command::SUCCESS;
    }
}
