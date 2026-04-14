<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\UserLoginDto;
use App\Exceptions\Auth\InvalidLoginDetailsException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

readonly class AuthService
{
    /**
     * @throws InvalidLoginDetailsException
     */
    public function login(UserLoginDto $userLoginDto): void
    {
        User::query()
            ->where('email', $userLoginDto->email)
            ->firstOrFail();

        if (! Auth::attempt(UserLoginDto::toArray($userLoginDto))) {
            throw new InvalidLoginDetailsException();
        }
    }
}
