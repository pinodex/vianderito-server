<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Models\User as Model;

class AuthController extends Controller
{
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
     * Get the authenticated User.
     *
     * @return \App\Models\User
     */
    public function me()
    {
        $user = $this->api->user();

        if ($user)
            return $user;

        return abort(401, 'Invalid credentials');
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
