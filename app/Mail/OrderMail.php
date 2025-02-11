<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderMail extends Mailable
{
   
    use Queueable, SerializesModels;
    public $user, $order, $item;
    public function __construct($user, $order, $item)
    {
        $this->user = $user;
        $this->order = $order;
        $this->item = $item;
    }
    public function build()
    { 
        return $this->view('mails.order')  
                    ->with([
                        'user' => $this->user,
                        'order' => $this->order ,
                        'item' => $this->item 
                    ]);
    }
}
