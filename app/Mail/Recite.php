<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Recite extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
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
        // $subject = 'Orderbekräftelse nr #'.$this->order->id.$reference;
        $subject = 'Kontant kvitto order nr #'.$this->order->id.$reference;

        return $this->view('email.recite')
                // ->from($address, $name)
                // ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                // ->bcc($address, $name)
                ->cc(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->replyTo(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                ->subject($subject);
    }
}
