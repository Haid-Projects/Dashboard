<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewSessionNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $session;
    protected $message;
    protected $type;
    protected $timestamp;
    protected $number;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $session
     * @param  string $message
     * @param  string $type
     * @return void
     */
    public function __construct($session, $message, $type,$number)
    {
        $this->session = $session;
        $this->message = $message;
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
        // Create the FcmNotification instance
        $notification = FcmNotification::create()
            ->setTitle('جلسة جديدة')
            ->setBody($this->message);

        return FcmMessage::create()
            ->setData([
                'message' => $this->message,
                'session_id' =>(string) $this->session->id,
                'name' => $this->session->name,
                'date' => $this->session->date,
                'time' => $this->session->time,
                'location' => $this->session->location,
                'type' => $this->type,
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
        return [
            'message' => $this->message,
            'session_id' => $this->session->id,
            'name' => $this->session->name,
            'date' => $this->session->date,
            'time' => $this->session->time,
            'location' => $this->session->location,
            'type' => $this->type,
            'timestamp' => $this->timestamp,
            'number'=>(string)$this->number,
        ];
    }
}
