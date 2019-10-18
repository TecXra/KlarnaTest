<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendToFriend extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $mailMessage;
    public $cartCalculator;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $mailMessage, $cartCalculator)
    {
        $this->name = $name;
        $this->mailMessage = $mailMessage;
        $this->cartCalculator = $cartCalculator;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // if(\App::environment('production')){
        //     $address = 'order@wheelzone.se';
        // } else {
        //     $address = 'sibar@abswheels.se';
        // }
        // $name = 'Wheelzone';
        $subject = $this->name .' vill tipsa dig om dessa produkter';

        return $this->view('email.send_to_friend')
                // ->from($address, $name)
                // ->cc($address, $name)
                // ->bcc($address, $name)
                // ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->replyTo(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->subject($subject);
    }
}
