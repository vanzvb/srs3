<?php

namespace App\Jobs;

use App\Mail\AppointmentReset;
use App\Traits\SendEmailNotifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AppointmentResetNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendEmailNotifications;

    protected $srsRequest;
    protected $email;
    protected $url;
    protected $dateTime;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($srsRequest, $email, $url, $dateTime)
    {
        $this->srsRequest = $srsRequest;
        $this->email = $email;
        $this->url = $url;
        $this->dateTime = $dateTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::mailer('smtp_2')->to($this->email)->send(new AppointmentReset($this->srsRequest, $this->url, $this->dateTime));
        } catch(\Throwable $e) {
            $this->backUpMail('AppointmentReset');
        }
    }
}
