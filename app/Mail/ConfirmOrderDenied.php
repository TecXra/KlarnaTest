<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmOrderDenied extends Mailable
{
    use Queueable, SerializesModels;

    // public $total = 30;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
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
        $subject = env('APP_NAME').' order status: Avslag';

        return $this->view('email.confirm_order_denied')
                // ->from($address, $name)
                // ->cc($address, $name)
                // ->bcc($address, $name)
                // ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->replyTo(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->subject($subject);
    }
}
