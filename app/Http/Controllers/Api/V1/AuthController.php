<?php

namespace App\Http\Controllers\Api\V1;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Http\Requests\NewPassword;
use App\Models\User as Model;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth')->only(['me', 'refresh', 'password']);
    }

    /**
     * Login action
     * 
     * @param  Request $request Request object
     * 
     * @return mixed
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['id', 'password']);

        $token = $this->api->attempt($credentials);

        if ($token) {
            $response = $this->respondWithToken($token);

            $response['user'] = $this->api->user();

            return $response;
        }

        abort(401, 'Invalid credentials');
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->api->logout();

        return response()->json([
            'message' => 'Token invalidated'
        ]);
    }

    /**
     * Register user
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function register(UserRegister $request)
    {
        $data = $request->only(['name', 'username', 'password',
            'email_address', 'phone_number']);

        $model = Model::create($data);

        return $model;
    }

    /**
     * Change password for a user
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function password(NewPassword $request)
    {
        $data = $request->only(['current_password', 'new_password']);

        $user = $this->api->user();

        $check = Hash::check($data['current_password'], $user->password);

        if (!$check) {
            return response()->json([
                'message' => 'Current password does not match'
            ], 403);
        }

        $user->password = $data['new_password'];

        $user->save();

        return response(null, 202);
    }

    /**
     * Get the authenticated User.
     *
     * @return \App\Models\User
     */
    public function me()
    {
        return $this->api->user();
    }

    /**
     * Refresh a token.
     *
     * @return array
     */
    public function refresh()
    {
        $token = $this->api->user()->refresh();

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->api->factory()->getTTL() * 60
        ];
    }
}
