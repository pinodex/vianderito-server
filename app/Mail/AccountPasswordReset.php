<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\PasswordReset;
use App\Models\Account;

class AccountPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * HTTP Request object
     * @var Request
     */
    private $request;

    /**
     * Account model
     * @var Account
     */
    private $account;

    /**
     * Password reset model
     * @var PasswordReset
     */
    private $model;

    /**
     * Agent instance for request UA
     * @var Agent
     */
    private $agent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PasswordReset $model)
    {
        $this->request = request();
        $this->model = $model;
        $this->account = $model->entity;

        $this->agent = new Agent();
        $this->agent->setUserAgent($this->request->userAgent());
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $deviceName = sprintf('%s (%s)',
            $this->agent->browser(), $this->agent->platform());

        return $this
            ->markdown('emails.account_password_reset_request')
            ->subject('Vianderito employee account password reset')
            ->with([
                'name' => $this->account->name,
                'username' => $this->account->username,
                'ip_address' => $this->request->ip(),
                'device_name' => $deviceName,
                'reset_link' => route('admin.passwordReset', [
                    'account' => $this->account,
                    'token' => $this->model->token
                ])
            ]);
    }
}
