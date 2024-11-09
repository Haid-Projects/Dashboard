<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class MangeFormNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $timestamp;
    protected $reason;
    protected $number;

    /**
     * Create a new notification instance.
     *
     * @param bool $accepted
     * @param string|null $reason
     * @return void
     */
    public function __construct(bool $accepted, string $reason = null,$number)
    {
        if ($accepted) {
            $this->message = 'تم قبول الأستمارة من قبل الجمعية';
            $this->reason = null;
        } else {
            $this->message = 'عذراً تم رفض الأستمارة';
            $this->reason = $reason;
        }
        $this->number=$number;
        $this->timestamp = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
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

        if ($this->reason) {
            $data['reason'] = $this->reason;
        }

        // Create the FcmNotification instance
        $notification = FcmNotification::create()
            ->setTitle('إدارة الاستمارة')
            ->setBody($this->message);

        return FcmMessage::create()
            ->setData($data)
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
        $data = [
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'number'=>(string)$this->number,
        ];

        if ($this->reason) {
            $data['reason'] = $this->reason;
        }

        return $data;
    }
}
