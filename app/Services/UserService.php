<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(UserRegistrationRequest $request): User
    {
        return  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

    }
}
