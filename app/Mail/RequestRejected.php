<?php

namespace App\Mail;

use App\Models\SRS3_Model\SrsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $srsRequest;
    public $rejectMessage;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SrsRequest $srsRequest, $rejectMessage, $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->srsRequest = $srsRequest;
        $this->rejectMessage = $rejectMessage;
        $this->mailFrom = $mailFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SRS #' . $this->srsRequest->request_id . ' - Rejected')
            ->from($this->mailFrom, 'BFFHAI')
            ->markdown('emails.requests3.rejected');
    }
}
