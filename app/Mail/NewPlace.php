<?php

namespace App\Mail;

use App\Place;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPlace extends Mailable
{
    use Queueable, SerializesModels;

      public $place;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Place $place)
    {
        $this->place = $place;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Place Created')
            ->markdown('emails.new-place');
    }
}
