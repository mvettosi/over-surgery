<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationCompleted extends Mailable {
    use Queueable, SerializesModels;

    protected $username;
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->markdown('email.registration-completed')
            ->with([
                'username' => $this->username,
                'password' => $this->password,
            ]);
    }
}
