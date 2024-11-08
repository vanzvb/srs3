<?php

namespace App\Jobs\SRS_3;

use App\Models\SRS3_Model\SrsRequest;
use App\Mail\RequestApproved;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendEmailNotifications;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendApprovedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailNotifications;

    protected $srsRequest;
    protected $email;
    protected $url;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SrsRequest $srsRequest, $email, $url, $type = '')
    {
        $this->srsRequest = $srsRequest;
        $this->email = $email;
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            // $backup = Mail::getSwiftMailer();

            if ($this->type == 'resend') {
                // $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                // $transport->setUsername('srs.bffhai.appointment4@gmail.com');
                // $transport->setPassword('nxgqzvqqqjemdpln');

                // $mailFrom = 'srs.bffhai.appointment4@gmail.com';

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

                Mail::mailer('smtp_2')->to($this->email)->send(new RequestApproved($this->srsRequest, $this->url, $mailFrom));
            } else {
                // $currentTime = date('G:i');

                // if ($currentTime >= '00:00' && $currentTime <= '10:59') {
                //     // use this configuration from 12:00 AM to 10:59 AM

                //     $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                //     $transport->setUsername('srs.bffhai.appointment@gmail.com');
                //     $transport->setPassword('qtlkkqkhdxgclalv');

                //     $mailFrom = 'srs.bffhai.appointment@gmail.com';
                // } elseif ($currentTime >= '11:00' && $currentTime <= '15:59') {
                //     // use this configuration from 11:00 AM to 3:59 PM

                //     $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                //     $transport->setUsername('srs.bffhai.appointment1@gmail.com');
                //     $transport->setPassword('alilmnjobmafkust');

                //     $mailFrom = 'srs.bffhai.appointment1@gmail.com';
                // } elseif ($currentTime >= '16:00' && $currentTime <= '20:59') {
                //     // use this configuration from 4:00 PM to 8:59 PM

                //     $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                //     $transport->setUsername('srs.bffhai.appointment2@gmail.com');
                //     $transport->setPassword('wgaidjqdsicuqmlb');

                //     $mailFrom = 'srs.bffhai.appointment2@gmail.com';
                // } elseif ($currentTime >= '21:00' && $currentTime <= '23:59') {
                //     // use this configuration from 9:00 PM to 11:59 PM

                //     $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                //     $transport->setUsername('srs.bffhai.appointment3@gmail.com');
                //     $transport->setPassword('jlgeqwvgzbcqmxnf');

                //     $mailFrom = 'srs.bffhai.appointment3@gmail.com';
                // }

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

                Mail::mailer('smtp_2')->to($this->email)->send(new RequestApproved($this->srsRequest, $this->url, $mailFrom));
            }

            //  Mail::setSwiftMailer($backup);
        } catch (\Throwable $e) {
            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailFrom = 'srs_bffhai_notification@znergee.com';

            $mailer = new \Swift_Mailer($transport);

            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)->send(new RequestApproved($this->srsRequest, $this->url, $mailFrom));

            Mail::setSwiftMailer($backup);

            //$this->backUpMail('RequestApproved');
        }
    }
}
