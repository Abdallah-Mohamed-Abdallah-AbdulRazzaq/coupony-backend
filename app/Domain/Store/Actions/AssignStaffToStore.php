<?php

namespace App\Domain\Store\Actions;

use App\Domain\Store\Events\StaffAssignedToStore;
use App\Domain\Store\Models\Store;
use App\Domain\User\Models\User;
use DateTime;
use DB;
use Spatie\Permission\Models\Role;

class AssignStaffToStore
{

    public function execute(Store $store, User $staff, string $roleName = 'store_staff', DateTime $exprires_at = null, User $assignedBy = null): void
    {
        $role = Role::firstOrCreate(['name' => $roleName]);

        if (!$staff->hasRole($roleName)) {
            $staff->assignRole($roleName);
        }

        $staff->userRoles()->updateOrCreate([
            'store_id' => $store->id,
            'role_id' => $role->id,
            'user_id' => $staff->id,
        ], [
            'granted_at' => new DateTime(),
            'granted_by_user_id' => $assignedBy ? $assignedBy->id : null,
            'expires_at' => $exprires_at,
        ]);

        event(new StaffAssignedToStore($store, $staff, $roleName, $assignedBy));
    }
}
