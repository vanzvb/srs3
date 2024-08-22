<?php

namespace App\Traits;

use App\Models\SrsRequest;
use Illuminate\Support\Facades\Mail;

trait SendEmailNotifications
{
    public function backUpMail($mail)
    {
        $backup = Mail::getSwiftMailer();

        $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
        $transport->setUsername('srs_bffhai_notification@bffhai.com');
        $transport->setPassword('bw4$dd581w]2');

        $mailer = new \Swift_Mailer($transport);


        Mail::setSwiftMailer($mailer);
    
        if ($mail == 'RequestSubmittedRequestor') {

            Mail::to($this->email)
            ->send(new \App\Mail\RequestSubmittedRequestor($this->srsRequest, 'srs_bffhai_notification@znergee.com'));

        } else if ($mail == 'RequestSubmitted') {

            Mail::to($this->email)
                ->send(new \App\Mail\RequestSubmitted($this->srsRequest, $this->url, 'srs_bffhai_notification@znergee.com'));

        } else if ($mail == 'RequestApproved') {

            Mail::to($this->email)
                ->send(new \App\Mail\RequestApproved($this->srsRequest, $this->url, 'srs_bffhai_notification@znergee.com'));

        } else if ($mail == 'RequestRejected') {
            
            if ($this->cc) {
                Mail::to($this->email)
                ->cc($this->cc)
                ->send(new \App\Mail\RequestRejected($this->srsRequest->request_id, $this->reason, 'srs_bffhai_notification@znergee.com'));   
            }

            Mail::to($this->email)
                ->send(new \App\Mail\RequestRejected($this->srsRequest->request_id, $this->reason, 'srs_bffhai_notification@znergee.com'));

        } else if ($mail == 'AppointmentReset') {
            Mail::to($this->email)
                ->send(new \App\Mail\AppointmentReset($this->srsRequest, $this->url, $this->dateTime, 'srs_bffhai_notification@znergee.com'));
        }

        Mail::setSwiftMailer($backup);
    }
}
