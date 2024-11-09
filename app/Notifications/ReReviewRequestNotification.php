<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ReReviewRequest;

class ReReviewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reReviewRequest;
    protected $timestamp;

    public function __construct(ReReviewRequest $reReviewRequest)
    {
        $this->reReviewRequest = $reReviewRequest;
        $this->timestamp = Carbon::now()->setTimezone(config('app.timezone'))->toDateTimeString();
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'يوجد طلب اعادة نظر لإستمارة مغلقة',
            'Request_id'=>$this->reReviewRequest->id,
            'form_id' => $this->reReviewRequest->beneficiary_form_id,
            'note' => $this->reReviewRequest->note,
            'timestamp' => $this->timestamp,
        ]);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A beneficiary has requested a re-review for their form.')
            ->line('Form ID: ' . $this->reReviewRequest->beneficiary_form_id)
            ->line('Note: ' . $this->reReviewRequest->note)
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'يوجد طلب اعادة نظر لإستمارة مغلقة',
            'Request_id'=>$this->reReviewRequest->id,
            'form_id' => $this->reReviewRequest->beneficiary_form_id,
            'note' => $this->reReviewRequest->note,
            'timestamp' => $this->timestamp,
        ];
    }


}
