<?php

namespace App\Jobs\srs3;

// use App\Models\SrsRequest;
use App\Models\SRS3_Model\SrsRequest;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use App\Mail\RequestSubmitted;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendEmailNotifications;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendHoaNotificationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailNotifications;

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
            $backup = Mail::getSwiftMailer();

            if ($this->type == 'resend') {

                // \Log::info('Entered resend hoa notification');

                // $transport = new \Swift_SmtpTransport('smtp.mailgun.org', 587, 'tls');
                // $transport->setUsername('bffhai@zn.donotreply.notification.znergee.com');
                // $transport->setPassword('kN;KJv6(7>Ut');

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

                // Mail::mailer('smtp_2')->to($this->email)
                //     ->cc([$mailFrom])
                //     ->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));

                Mail::mailer('smtp_2')->to($this->email)
                    ->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));

                ## -- 

                // $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                // $transport->setUsername('srs.bffhai.appointment4@gmail.com');
                // $transport->setPassword('nxgqzvqqqjemdpln');

                // $mailFrom = 'srs.bffhai.appointment4@gmail.com';

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                // Mail::to($this->email)
                //     ->cc([$mailFrom])
                //     ->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));
                    
            } else {
                // $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
                // $transport->setUsername('srs_bffhai_approval@znergee.com');
                // $transport->setPassword('Qc]t4<{2!GL&n)Pj');

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

                Mail::mailer('smtp_2')->to($this->email)->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));

                ## ---

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
                //     $transport->setUsername('srs.for.approval1@gmail.com');
                //     $transport->setPassword('ravsnhyzvopmvauo');
    
                //     $mailFrom = 'srs.for.approval1@gmail.com';
                // } elseif ($currentTime >= '21:00' && $currentTime <= '23:59') {
                //     // use this configuration from 9:00 PM to 11:59 PM
    
                //     $transport = new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl');
                //     $transport->setUsername('srs.bffhai.appointment3@gmail.com');
                //     $transport->setPassword('jlgeqwvgzbcqmxnf');
    
                //     $mailFrom = 'srs.bffhai.appointment3@gmail.com';
                // }

                // $mailer = new \Swift_Mailer($transport);

                // Mail::setSwiftMailer($mailer);

                // Mail::to($this->email)->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));
            }

            // Mail::setSwiftMailer($backup);

        } catch(\Throwable $e) {
            // $this->backUpMail('RequestSubmitted');

            // $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

            // Mail::mailer('smtp_2')->to($this->email)->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));

            ## ----

            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailFrom = 'srs_bffhai_notification@znergee.com';

            $mailer = new \Swift_Mailer($transport);

            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)->send(new RequestSubmitted($this->srsRequest, $this->url, $mailFrom));

            Mail::setSwiftMailer($backup);
        }

    }
}
