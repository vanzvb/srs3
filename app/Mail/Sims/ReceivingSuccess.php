<?php

namespace App\Mail\Sims;

use App\Models\Sims\SimsRcvDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceivingSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public SimsRcvDetail $receivingDetails;
    public string $url;
    public string $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receivingDetails, $url, $mailFrom = 'itqa@atomitsoln.com')
    {
        $this->receivingDetails = $receivingDetails;
        $this->url = $url;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SIMS Receiving #'.$this->receivingDetails->rcvID.' - Success')
            ->from($this->mailFrom, 'Test Email')
            ->markdown('emails.sims.receive-success');
    }
}
