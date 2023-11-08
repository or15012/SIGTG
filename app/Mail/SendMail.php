<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    private $vista;
    public $subject;
    public $Info;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($vista, $asunto, $info)
    {
        $this->vista = $vista;
        $this->subject = $asunto;
        $this->Info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->vista);
    }
}
