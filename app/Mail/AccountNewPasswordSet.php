<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use App\Models\Account;

class AccountNewPasswordSet extends Mailable
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
     * Agent instance for request UA
     * @var Agent
     */
    private $agent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Request $request, Account $account)
    {
        $this->request = $request;
        $this->account = $account;

        $this->agent = new Agent();
        $this->agent->setUserAgent($request->userAgent());
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

        $timestamp = sprintf('%s (%s)',
            date('M d, Y h:i a'), date_default_timezone_get());

        return $this
            ->markdown('emails.account_new_password_set')
            ->subject('Vianderito employee account password changes')
            ->with([
                'name' => $this->account->name,
                'ip_address' => $this->request->ip(),
                'device_name' => $deviceName,
                'timestamp' => $timestamp
            ]);
    }
}
