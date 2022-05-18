<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * The user instance.
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\User $user
     * @return void
     */
    public function __construct(UserService $user)
    {
        $this->user = $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Http\Requests\StoreUserRequest  $request
     * @return \Http\Requests\StoreUserRequest
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = $this->user->createUser($request->all());

            return response()->json([
                'message' => 'Usuário criado com sucesso!',
                'data' => $user
            ], 201);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
