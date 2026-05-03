<?php

namespace App\Services;

use App\Exceptions\InvalidUserCredentialsException;
use App\Models\User;

class UserService
{
    public function register(array $data): array
    {
        $user = User::create($data);

        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $data): array
    {
        if (!auth()->attempt($data)) {
            throw new InvalidUserCredentialsException();
        }

        $user = auth()->user();
        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(): void
    {
        auth()->user()->currentAccessToken()->delete();
    }
}
