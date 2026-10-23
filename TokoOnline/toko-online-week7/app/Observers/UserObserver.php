<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function updated(User $user): void
    {
        if (! $user->wasChanged('role')) {
            return;
        }

        Log::info('User role changed', [
            'target_user_id' => $user->id_user,
            'old_role' => $user->getOriginal('role'),
            'new_role' => $user->role,
            'actor_user_id' => Auth::user()?->id_user,
        ]);
    }
}
