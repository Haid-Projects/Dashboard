<?php

namespace App\Notifications;

use App\Models\Specialist;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class NewFormNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $message;
    protected $timestamp;
    protected $formId;
    protected $number;

    /**
     * Create a new notification instance.
     *
     * @param int $formId
     * @return void
     */
    public function __construct($formId , $number)
    {
        $this->message = 'يوجد استمارة جديدة';
        $this->timestamp = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
        $this->formId = $formId;
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
     * @param mixed $notifiable
     * @return FcmMessage
     */
    public function toFcm($notifiable)
    {
        $data = [
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'number'=>(string)$this->number,
        ];

        // Add form ID if the notifiable is a Specialist
        if ($notifiable instanceof Specialist && $this->formId !== null) {
            $data['form_id'] = (string)$this->formId;
        }

        $notification = FcmNotification::create()
            ->setTitle('استمارة جديدة')
            ->setBody($this->message);

        return FcmMessage::create()
            ->setData($data)
            ->setNotification($notification);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $data = [
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'number'=>(string)$this->number,
        ];

        // Add form ID if the notifiable is a Specialist
        if ($notifiable instanceof Specialist && $this->formId !== null) {
            $data['form_id'] = $this->formId;
        }

        return $data;
    }
}
