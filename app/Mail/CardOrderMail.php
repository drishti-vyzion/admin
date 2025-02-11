<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardOrderMail extends Mailable
{
   
    use Queueable, SerializesModels;
    public $user, $order;
    public function __construct($user, $order)
    {
        $this->user = $user;
        $this->order = $order;
    }
    public function build()
    { 
        return $this->view('mails.orders')  
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order ,
                    ]);
    }
}
