<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Create user.
     * @param array $data
     * @return object 
    */
    public function createUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        
        return $this->user->create($data);
    }
}