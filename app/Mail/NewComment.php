<?php

namespace App\Mail;

use App\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewComment extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;

    public $resource;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, $resource)
    {
        $this->comment = $comment;

        $this->resource = $resource;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("{$this->comment->user->name} commented on {$this->resource->name}")
            ->markdown('emails.new-comment');
    }
}
