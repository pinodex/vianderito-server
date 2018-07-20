<?php

/**
 * This file is part of the TwigBridge package.
 *
 * @copyright Robert Crowe <hello@vivalacrowe.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Components\Twig\Extension;

use Twig_Extension;
use Twig_SimpleFunction;
use Illuminate\Auth\AuthManager;

/**
 * Access Laravels auth class in your Twig templates.
 */
class Auth extends Twig_Extension
{
    /**
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Create a new auth extension.
     *
     * @param \Illuminate\Auth\AuthManager
     */
    public function __construct(AuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'App_Components_Twig_Extension_Auth';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('user_admin', function () {
                return $this->auth->guard('admin')->user();
            }),

            new Twig_SimpleFunction('user_web', function () {
                return $this->auth->guard('web')->user();
            }),
        ];
    }
}
