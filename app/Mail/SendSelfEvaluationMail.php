<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSelfEvaluationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $woman;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $woman)
    {
        $this->data = $data;
        $this->woman = $woman;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('app@amakomaya.com', "Woman Evaluation From App")
                    ->subject('Woman Name : '.$this->woman->name.', Phone : '.$this->woman->phone.' - Self Evaluation Report')
                    ->markdown('mails.self-evaluation');
    }
}