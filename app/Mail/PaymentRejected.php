<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $total = 30;
    public $order;
    public $resultCode;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $resultCode, $password = null)
    {
        $this->order = $order;
        $this->$resultCode = $resultCode;
        $this->password = $password;
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
        $subject = 'Orderbekräftelse';

        return $this->view('email.payment_rejected')
                // ->from($address, $name)
                // ->cc($address, $name)
                // ->bcc($address, $name)
                ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->replyTo(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->subject($subject)
                ->with([ 'password' => $this->password ]);
    }
}
