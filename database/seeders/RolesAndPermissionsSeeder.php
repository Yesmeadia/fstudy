<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions list
        $permissions = [
            // Applications
            'applications.view_any',
            'applications.view_assigned',
            'applications.view_own',
            'applications.create',
            'applications.edit_own',
            'applications.edit_any',
            'applications.submit',
            'applications.assign',
            'applications.change_status',
            'applications.delete',

            // Reviews & Evaluations
            'reviews.academic',
            'reviews.financial',
            'reviews.infrastructure',
            'reviews.conduct',
            'evaluation.score',
            'field_visit.conduct',
            'management.approve',

            // Documents
            'documents.upload',
            'documents.verify',
            'documents.download',

            // Reports
            'reports.generate',
            'reports.export',

            // Administration
            'schools.manage',
            'users.manage',
            'roles.manage',
            'settings.manage',
            'audit_logs.view',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Roles: only Super Admin and Inspector
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $inspector = Role::firstOrCreate(['name' => 'Inspector', 'guard_name' => 'web']);

        // Give Super Admin all permissions
        $superAdmin->givePermissionTo(Permission::all());

        // Inspector permissions
        $inspector->givePermissionTo([
            'applications.view_any',
            'applications.view_assigned',
            'applications.view_own',
            'applications.create',
            'applications.edit_own',
            'applications.edit_any',
            'applications.submit',
            'reviews.conduct',
            'field_visit.conduct',
            'documents.upload',
            'documents.verify',
            'documents.download',
            'reports.generate',
            'schools.manage',
        ]);

        // Remove legacy roles if present
        $legacyRoles = [
            'Administrator',
            'Feasibility Officer',
            'Zonal/Regional Officer',
            'Field Verification Officer',
            'Financial Reviewer',
            'Academic Reviewer',
            'Management/Approver',
            'School Applicant',
        ];

        foreach ($legacyRoles as $roleName) {
            $oldRole = Role::where('name', $roleName)->first();
            if ($oldRole) {
                // Transfer existing users of this role to Inspector unless they have Super Admin
                foreach ($oldRole->users as $u) {
                    if (! $u->hasRole('Super Admin')) {
                        $u->assignRole('Inspector');
                    }
                }
                $oldRole->delete();
            }
        }
    }
}
