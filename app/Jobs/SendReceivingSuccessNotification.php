<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Sims\SimsRcvDetail;
use App\Mail\Sims\ReceivingSuccess;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendReceivingSuccessNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public SimsRcvDetail $receivingDetails;
    public string $url;
    public string $mailFrom;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //temp sender
    public function __construct($receivingDetails, $url, $mailFrom = 'itqa@atomitsoln.com')
    {
        $this->receivingDetails = $receivingDetails;
        $this->url = $url;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // change to email of directors
            $recipients = [
                'ivan.deposoy@atomitsoln.com',
                'itqa@atomitsoln.com'
            ];

            foreach($recipients as $recipient) {
                Mail::to($recipient)->send(new ReceivingSuccess($this->receivingDetails, $this->url));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
