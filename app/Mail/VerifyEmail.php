<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class VerifyEmail extends Mailable
{
    use Queueable;

    public $user;

  public function __construct($user)
    {
        $this->user = $user;
    }

  
   public function build()
    {
        return $this->subject('Confirm Your Email Address')
            ->view('emails.verify',['user'=>$this->user]); // Create this Blade file
    }
}
