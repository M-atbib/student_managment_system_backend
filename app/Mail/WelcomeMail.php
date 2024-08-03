<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $loginUrl;
    public $loginemail;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $loginUrl, $loginemail, $password)
    {
        $this->username = $username;
        $this->loginUrl = $loginUrl;
        $this->loginemail = $loginemail;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.welcome')
                    ->subject('Plateforme groupe el houria')
                    ->with([
                        'username' => $this->username,
                        'loginUrl' => $this->loginUrl,
                        'loginemail' => $this->loginemail,
                        'password' => $this->password,
                    ]);
    }
}
