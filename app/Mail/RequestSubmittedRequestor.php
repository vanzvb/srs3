<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestSubmittedRequestor extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // $mailFrom = 'srs_bffhai_notification@znergee.com'
    // $mailFrom = 'srs.bffhai.notification@gmail.com'

    public function __construct($request, $mailFrom = 'srs_bffhai_notification@znergee.com')
    {
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
        return $this->subject('SRS #'.$this->request->request_id.' - Submitted Request')
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.submitted_requestor');
    }
}
