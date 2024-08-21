<?php

namespace App\Jobs;

use App\Mail\RequestRenewal;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendEmailNotifications;
use Illuminate\Queue\SerializesModels;
use App\Mail\RequestSubmittedRequestor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendRequestRenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        try {
            // sending via MailGun

            $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com';

            Mail::mailer('smtp_2')->to($this->email)->send(new RequestRenewal($this->email, $this->url, $mailFrom));
        }
        catch(\Throwable $e) {
            // sending via Znergee
            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailFrom = 'srs_bffhai_notification@znergee.com';
    
            $mailer = new \Swift_Mailer($transport);
            
            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)->send(new RequestRenewal($this->email, $this->url, $mailFrom));

            Mail::setSwiftMailer($backup);
        };
    }
}
