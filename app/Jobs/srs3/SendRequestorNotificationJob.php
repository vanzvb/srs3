<?php

namespace App\Jobs\srs3;

// use App\Models\SrsRequest;
use App\Mail\srs3\RequestSubmittedRequestor;
use App\Models\SRS3_Model\SrsRequest;
use App\Traits\SendEmailNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRequestorNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailNotifications;

    protected $srsRequest;
    protected $email;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SrsRequest $srsRequest, $email, $type = '')
    {
        $this->srsRequest = $srsRequest;
        $this->email = $email;
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

            // $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');

            // \Log::info('Type: ' . $this->type);

            // if ($this->type == 'renewal') {
                
            //     \Log::info('Entered renewal');

            //     $transport->setUsername('srs_bffhai_renewal@znergee.com');
            //     $transport->setPassword("$~ms)12_;3|2");

            //     $mailFrom = 'srs_bffhai_renewal@znergee.com';
            // } else {

            //     \Log::info('Entered new');

            //     $transport->setUsername('srs_bffhai_new@znergee.com');
            //     $transport->setPassword('Bhm82+H&uwv94YG"');

            //     $mailFrom = 'srs_bffhai_new@znergee.com';
            // }

            // $mailer = new \Swift_Mailer($transport);

            // Mail::setSwiftMailer($mailer);

            // Mail::to($this->email)->send(new RequestSubmittedRequestor($this->srsRequest, $mailFrom));

            // Mail::setSwiftMailer($backup);

            $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

            Mail::mailer('smtp_2')->to($this->email)->send(new RequestSubmittedRequestor($this->srsRequest, $mailFrom));
        } catch(\Throwable $e) {

            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailer = new \Swift_Mailer($transport);

            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)
                ->send(new RequestSubmittedRequestor($this->srsRequest, 'srs_bffhai_notification@znergee.com'));
            
            Mail::setSwiftMailer($backup);

            // $this->backUpMail('RequestSubmittedRequestor');
        };
    }
}
