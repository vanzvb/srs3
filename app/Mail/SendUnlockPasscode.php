<?php

namespace App\Mail;

use App\Models\SrsUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendUnlockPasscode extends Mailable
{
    use Queueable, SerializesModels;

    public SrsUser $user;
    public int $passcode;
    public string $url;
    public string $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SrsUser $user, int $passcode, string $url,string $mailFrom = 'bffhai@zn.donotreply.notification.znergee.com')
    {
        $this->user = $user;
        $this->passcode = $passcode;
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
        return $this->subject('Unlock Account Passcode')
            ->from($this->mailFrom, 'BFFHAI')
            ->markdown('emails.auth.unlock');
    }
}
