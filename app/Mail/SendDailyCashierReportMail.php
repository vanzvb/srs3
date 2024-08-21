<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendDailyCashierReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $date;
    public $mailFrom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $date, $maiFrom = "bffhai@zn.donotreply.notification.znergee.com")
    {
        $this->file = $file;
        $this->date = $date;
        $this->mailFrom = $maiFrom;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {    
        // format date to Y-m-d
        $formattedDate = date('Y-m-d', strtotime($this->date));

        return $this->subject('[BFFHAI] Daily Cashier Report - ' . $this->date)
        ->from($this->mailFrom, 'BFFHAI')
        ->attachData($this->file->output(), 'daily_cashier_report_'. $formattedDate .'.pdf')
        ->markdown('emails.reports.daily_cashier_report');
    }
}
