<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
   
    use Queueable, SerializesModels;
    public $user, $order, $item, $address;
    public function __construct($user, $order, $item, $address)
    {
        $this->user = $user;
        $this->order = $order;
        $this->item = $item;
        $this->address = $address;
    }
    public function build()
    { 
        return $this->view('mails.order')  
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order ,
                        'item' => $this->item ,
                          'address' => $this->address 
                    ]);
    }
}
