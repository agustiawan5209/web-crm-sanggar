<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $url;
    public $subject;
    public $message;

    public function __construct($user, $url, $subject, $message)
    {
        $this->user = $user;
        $this->url = $url;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function build()
    {
        return $this->markdown('emails.notification')
                    ->with([
                        'user' => $this->user,
                        'url' => $this->url,
                        'message' => $this->message,
                        'subject' => $this->subject,
                    ]);
    }
}
