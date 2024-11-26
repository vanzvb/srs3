<?php

namespace App\Jobs\SRS_3;

use App\Models\SRS3_Model\SrsRequest;
use App\Mail\RequestRejected;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use App\Traits\SendEmailNotifications;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendRejectedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailNotifications;

    protected $srsRequest;
    protected $email;
    protected $reason;
    protected $cc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SrsRequest $srsRequest, $email, $reason, $cc = [])
    {
        $this->srsRequest = $srsRequest;
        $this->email = $email;
        $this->reason = $reason;
        $this->cc = $cc;
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

            Mail::mailer('smtp_2')->to($this->email)->send(new RequestRejected($this->srsRequest->request_id, $this->reason, $mailFrom));
        } catch(\Throwable $e) {
            $backup = Mail::getSwiftMailer();

            $transport = new \Swift_SmtpTransport('mail.znergee.com', 465, 'ssl');
            $transport->setUsername('srs_bffhai_notification@znergee.com');
            $transport->setPassword('bw4$dd581w]2');

            $mailFrom = 'srs_bffhai_notification@znergee.com';

            $mailer = new \Swift_Mailer($transport);

            Mail::setSwiftMailer($mailer);

            Mail::to($this->email)->send(new RequestRejected($this->srsRequest->request_id, $this->reason, $mailFrom));

            Mail::setSwiftMailer($backup);
        }
    }
}
