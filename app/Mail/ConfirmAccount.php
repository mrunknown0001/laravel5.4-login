<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmAccount extends Mailable
{
    use Queueable, SerializesModels;


    public $code, $id_no;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code, $id_no)
    {
        //
        $this->code = $code;
        $this->id_no = $id_no;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('webmaster@wbdvlpr.cf', 'Web Master Name')
                ->view('mails.confirmaccount')
                ->subject('Confirm Account');
    }
}
