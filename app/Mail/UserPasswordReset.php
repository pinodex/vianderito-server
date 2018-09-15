<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PasswordReset;

class UserPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Password reset model instance
     * @var App\Models\PasswordReset
     */
    private $model;

    /**
     * User model instance
     * @var App\Models\User
     */
    private $user;

    /**
     * Request object
     * @var Request
     */
    private $request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $model)
    {
        $this->model = $model;

        $this->user = $model->entity;

        $this->request = request();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user_password_reset', [
            'name' => $this->user->name,
            'token' => $this->model->token,
            'username' => $this->user->username,
            'ip_address' => $this->request->ip()
        ]);
    }
}
