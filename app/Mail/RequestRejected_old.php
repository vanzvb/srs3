<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $requestId;
    public $rejectMessage;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($requestId, $rejectMessage, $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->requestId = $requestId;
        $this->rejectMessage = $rejectMessage;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SRS #'.$this->requestId.' - Rejected')
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.rejected');
    }
}
