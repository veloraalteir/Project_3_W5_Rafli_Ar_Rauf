<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeRoleRequest;
use App\Models\User;
use App\Services\RoleManagementService;
use Illuminate\Http\RedirectResponse;

class RoleController extends Controller
{
    public function update(
        ChangeRoleRequest $request,
        User $user,
        RoleManagementService $service
    ): RedirectResponse {
        $service->changeRole(
            $request->user(),
            $user,
            $request->validated('role')
        );

        return back()->with('status', 'Role berhasil diperbarui.');
    }
}