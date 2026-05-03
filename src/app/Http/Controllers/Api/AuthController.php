<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private UserService $userService)
    {}

    public function register(RegisterUserRequest $request)
    {
        $data = $this->userService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно зарегистрирован',
            'data' => [
                'user' => UserResource::make($data['user']),
                'token' => $data['token']
            ]
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {
        $data = $this->userService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно авторизовался',
            'data' => [
                'user' => UserResource::make($data['user']),
                'token' => $data['token']
            ]
        ]);
    }

    public function logout()
    {
        $this->userService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Пользователь успешно вышел из аккаунта'
        ]);
    }
}
