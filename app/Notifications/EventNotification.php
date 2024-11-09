<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class EventNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $beneficiaries;
    protected $type;
    protected $timestamp;
    protected $number;
    /**
     * Create a new notification instance.
     *
     * @param $event
     * @param $beneficiaries
     * @param $type
     * @return void
     */
    public function __construct($event, $beneficiaries, $type,$number)
    {
        $this->event = $event;
        $this->beneficiaries = $beneficiaries;
        $this->type = $type;
        $this->timestamp = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        $this->number=$number;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [FcmChannel::class, 'database'];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return FcmMessage
     */
    public function toFcm($notifiable)
    {
        $message = 'انت مدعو الى حدث جديد: ' . $this->event->name;
        if ($this->type === 'public') {
            $message .= ' (هذا الحدث لجميع المستفيدين)';
        } else {
            $beneficiaryNames = $this->beneficiaries->map(function ($beneficiary) {
                return $beneficiary->full_name;
            })->implode(', ');
            $message .= ' المستفيدين المدعويين: ' . $beneficiaryNames . ')';
        }

        // Create the FcmNotification instance
        $notification = FcmNotification::create()
            ->setTitle('دعوة لحدث جديد')
            ->setBody($message);

        return FcmMessage::create()
            ->setData([
                'event_id' =>  (string)$this->event->id,
                'event_name' => $this->event->name,
                'event_date' => $this->event->date,
                'message' => $message,
                'timestamp' => $this->timestamp,
                'number'=>(string)$this->number,
            ])
            ->setNotification($notification); // Use the FcmNotification instance here
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $message = 'انت مدعو الى حدث جديد: ' . $this->event->name;
        if ($this->type === 'public') {
            $message .= ' (هذا الحدث لجميع المستفيدين)';
        } else {
            $beneficiaryNames = $this->beneficiaries->map(function ($beneficiary) {
                return $beneficiary->full_name;
            })->implode(', ');
            $message .= ' المستفيدين المدعويين: ' . $beneficiaryNames . ')';
        }

        return [
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_date' => $this->event->date,
            'message' => $message,
            'timestamp' => $this->timestamp,
            'number'=>(string)$this->number,
        ];
    }
}
