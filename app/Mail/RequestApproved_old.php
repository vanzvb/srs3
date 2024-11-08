<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $url, $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->request = $request;
        $this->url = $url;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SRS #'.$this->request->request_id.' - Approved')
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.approved');
    }
}
