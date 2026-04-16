<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Video;

class VideoPolicy
{
    public function update(User $user, Video $video): bool
    {
        if ($user->id === $video->created_by_id) {
            return true;
        }

        return $user->role === UserRole::Admin;
    }

    public function delete(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }
}
