<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $request;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $url, $mailFrom = 'srs_bffhai_notification@znergee.com')
    {
        $this->url = $url;
        $this->request = $request;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SRS #'.$this->request->request_id.' - You have SRS for Approval')
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.submitted');
    }
}
