<?php

namespace App\Jobs;

use App\Mail\AppointmentSet;
use App\Models\SrsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendAppointmentSetNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected SrsRequest $srsRequest;
    protected string $email;
    protected string $appointmentDate;
    protected string $appointmentTime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($srsRequest, $email, $appointmentDate, $appointmentTime)
    {
        $this->srsRequest = $srsRequest;
        $this->email = $email;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

            Mail::mailer('smtp_2')->to($this->email)->send(new AppointmentSet($this->srsRequest, $this->appointmentDate, $this->appointmentTime, $mailFrom));
        } catch (\Exception $e) {
            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailFrom = 'srs_bffhai_notification@znergee.com';

            $mailer = new \Swift_Mailer($transport);

            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)->send(new AppointmentSet($this->srsRequest, $this->appointmentDate, $this->appointmentTime, $mailFrom));

            Mail::setSwiftMailer($backup);
        }
    }
}
