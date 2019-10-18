<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationCC extends Mailable
{
    use Queueable, SerializesModels;

    public $total = 30;
    public $order;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $password = null)
    {
        $this->order = $order;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reference = "";
        if(!empty($this->order->reference)) {
            $reference = ', ref: '.$this->order->reference;
        }

        // if(\App::environment('production')){
        //     $address = 'order@wheelzone.se';
        // } else {
        //     $address = 'sibar@abswheels.se';
        // }
        // $name = 'Wheelzone';
        $subject = 'Preliminär orderbekräftelse nr #'.$this->order->id.$reference;

        return $this->view('email.order_confirmation')
                // ->from($address, $name)
                // ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                // ->bcc($address, $name)
                // ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->replyTo($this->order->user->email)
                ->subject($subject)
                ->with([ 'password' => $this->password ]);
    }
}