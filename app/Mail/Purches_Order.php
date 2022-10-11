<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Purches_Order extends Mailable
{
    use Queueable, SerializesModels;

    public  $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
    //   $this->$mailData=$mailData;
       $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.purchesmail')
        ->subject($this->mailData['title'])
        ->with('mailData', $this->mailData);
    }
}
