<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\SaveAvatar;
use App\Http\Requests\UpdateProfile;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * Index action show profile information
     * 
     * @return mixed
     */
    public function index()
    {
        return $this->api->user();
    }

    /**
     * Save user profile details
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function save(UpdateProfile $request)
    {
        $data = $request->only(['name', 'username', 'email_address', 'phone_number']);

        $user = $this->api->user();

        $user->fill($data);

        $user->save();

        return response(null, 202);
    }

    /**
     * Get user picture links
     * 
     * @return mixed
     */
    public function picture()
    {
        return $this->api->user()->picture;
    }

    /**
     * Set user picture
     * 
     * @param Request $request Request object
     *
     * @return mixed
     */
    public function setPicture(SaveAvatar $request)
    {
        $file = $request->file('file');
        $user = $this->api->user();

        $user->picture = $file;
        $user->save();

        return $this->picture();
    }
}
