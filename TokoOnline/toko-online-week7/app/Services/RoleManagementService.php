<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoleManagementService
{
    public function changeRole(
        User $actor,
        User $target,
        string $newRole
    ): void {
        if ($actor->role !== 'admin') {
            throw new AuthorizationException(
                'Hanya admin yang boleh mengubah role.'
            );
        }

        if ($actor->is($target) || $target->role === 'admin') {
            throw new AuthorizationException(
                'Role akun admin tidak dapat diubah.'
            );
        }

        if (! in_array($newRole, ['customer', 'staff'], true)) {
            throw ValidationException::withMessages([
                'role' => 'Role hanya boleh customer atau staff.',
            ]);
        }

        if ($target->role === $newRole) {
            return;
        }

        DB::transaction(function () use ($target, $newRole): void {
            $target->role = $newRole;
            $target->save();
        });
    }
}