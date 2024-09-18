<?php

namespace App\Mail\srs3;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestRenewal extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $email;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // $mailFrom = 'srs_bffhai_notification@znergee.com'
    // $mailFrom = 'srs.bffhai.notification@gmail.com'
    // $mailFrom = 'srs_bffhai_renewal@outlook.com'

    public function __construct($email, $url, $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->email = $email;
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
        return $this->subject('Sticker Application Renewal Request Version 3')
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.renewal');
    }
}
