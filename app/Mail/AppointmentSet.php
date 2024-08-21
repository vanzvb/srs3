<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentSet extends Mailable
{
    use Queueable, SerializesModels;

    public $request;
    public $appointmentDate;
    public $appointmentTime;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $appointmentDate, $appointmentTime, $mailFrom = "bffhai@zn.donotreply.notification.znergee.com")
    {
        $this->request = $request;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SRS #'.$this->request->request_id.' - Appointment Set')
        ->from($this->mailFrom, 'BFFHAI')
        ->markdown('emails.requests.appointment_set');
    }
}
