<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentReset extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $url;
    public $mailFrom;
    public $dateTime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $url, $dateTime, $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->request = $request;
        $this->url = $url;
        $this->mailFrom = $mailFrom;
        $this->dateTime = $dateTime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reset Appointment - SRS #'.$this->request->request_id)
                    ->from($this->mailFrom, 'BFFHAI')
                    ->markdown('emails.requests.appointment_reset');
    }
}
