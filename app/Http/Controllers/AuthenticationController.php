<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Requests\Authentication\RegistrationRequest;
use App\Http\Resources\User\UserResource;
use App\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class AuthenticationController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }

    /**
     * Register
     * @param RegistrationRequest $request
     * @return void
     */
    public function register(RegistrationRequest $request)
    {
        try {
            $data = $request->validated();
            // Append User Role
            $data['role'] = RoleEnum::USER->value;
            // Remove password confirmation from data
            unset($data['password_confirmation']);
            $user = $this->userService->createUser($data);
            $response = new UserResource($user);
        } catch (\Exception $exception) {
            return Response::error($exception);
        }
        return Response::success($response, 'User registered successfully');

    }

    /**
     * Login
     * @param LoginRequest $request
     * @return void
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!auth()->attempt($credentials)) {
                return Response::custom([], 403, "Email or password is incorrect");
            }
            $user = auth()->user();
            $token = $user->createToken('authToken')->plainTextToken;
            $user['token'] = $token;
            $userResource = new UserResource($user);
        } catch (\Exception $exception) {
            return Response::error($exception);
        }
        return Response::success($userResource, "Login successful");
    }

    /**
     * Logout
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
        } catch (\Exception $exception) {
            return Response::error($exception);
        }
        return Response::success([], "Logout successful");
    }

    /**
     * Get Authenticated User
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        try {
            $user = auth()->user();
            $userResource = new UserResource($user);
        } catch (\Exception $exception) {
            return Response::error($exception);
        }
        return Response::success($userResource, "User fetched successfully");
    }
}
