<?php

namespace App\Services;

use App\Exceptions\InvalidUserCredentialsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

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
        if (!Auth::attempt($data)) {
            throw new InvalidUserCredentialsException();
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user && $user->currentAccessToken() instanceof PersonalAccessToken) {
            $user->currentAccessToken()->delete();
        }
    }
}
