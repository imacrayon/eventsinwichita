<?php

namespace App\Notifications;

use App\Event;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventProposal extends Notification
{
    use Queueable;

    public $event;

    public $changes;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Event $event, $changes)
    {
        $this->event = $event;

        $this->changes = $changes;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('events.show', $this->event);

        return tap(new MailMessage, function ($mail) use ($url) {
            $mail->subject('Event Change Request')
                 ->line("Changes proposed to [{$this->event->name}]({$url})");

            foreach ($this->changes as $title => $change) {
                $mail->line('## '.Str::title($title))->line($change);
            }
        });
    }

    public function toArray($notifiable)
    {
        return [
            'event' => $this->event,
            'changes' => $this->changes,
        ];
    }
}
