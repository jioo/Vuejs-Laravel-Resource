<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use \Laravel\Passport\Client;

class UserController extends Controller
{
    private $client;

    /**
     * DefaultController constructor.
     */
    public function __construct()
    {
        $this->client = Client::where('password_client', 1)->first();
    }

    /**
     * Register user
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('username');
        $user->password = bcrypt($request->input('password'));

        // Return the article reource if query has been successful
        if($user->save()) {
            return $this->authenticate($request);
        }
    }

    /**
     * Login user
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        return $this->authenticate($request);
    }

    /* ========================================================================= *\
     * Helpers
    \* ========================================================================= */

    /**
     * Authenticate user
     *
     * @return \Route
     */
    private function authenticate($request) {
        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $this->client->id,
            'client_secret' => $this->client->secret,
            'username'      => $request->input('username'),
            'password'      => $request->input('password'),
            'scope'         => '',
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }

}
