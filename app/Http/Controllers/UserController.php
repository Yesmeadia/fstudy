<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Notifications\AccountCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Guard all actions to Super Admin only.
     */
    private function authorizeAdmin(): void
    {
        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Access denied. Super Admin only.');
    }

    /**
     * List all users with search and role filter.
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Inspector', 'guard_name' => 'web']);

        $query = User::with('roles')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            if ($role === 'unassigned' || $role === 'none') {
                $query->doesntHave('roles');
            } else {
                $query->whereHas('roles', fn ($q) => $q->where('name', $role));
            }
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::whereIn('name', ['Super Admin', 'Inspector'])->orderBy('name')->pluck('name');

        $totalUsersCount = User::count();
        $superAdminCount = User::role('Super Admin')->count();
        $inspectorCount = User::role('Inspector')->count();
        $unassignedCount = User::doesntHave('roles')->count();

        return view('users.index', compact(
            'users',
            'roles',
            'totalUsersCount',
            'superAdminCount',
            'inspectorCount',
            'unassignedCount'
        ));
    }

    /**
     * Show the create user form.
     */
    public function create()
    {
        $this->authorizeAdmin();

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Inspector', 'guard_name' => 'web']);

        $roles = Role::whereIn('name', ['Super Admin', 'Inspector'])->orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a new user.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'designation' => $data['designation'] ?? null,
            'password' => $data['password'],
        ]);

        $user->assignRole($data['role']);

        try {
            $user->notify(new AccountCreatedNotification($user, $data['role']));
        } catch (\Throwable $e) {
            Log::warning("Failed to send welcome email to {$user->email}: ".$e->getMessage());
        }

        AuditLog::log('user_created', $user, null, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $data['role'],
        ]);

        return redirect()->route('users.show', $user)
            ->with('success', "User '{$user->name}' created successfully and assigned the {$data['role']} role.");
    }

    /**
     * Show a single user profile.
     */
    public function show(User $user)
    {
        $this->authorizeAdmin();

        $user->load(['roles.permissions', 'assignedApplications.school']);
        $user->loadCount(['assignedApplications', 'reviews', 'fieldVisits']);

        $recentLogs = AuditLog::where('user_id', $user->id)
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('users.show', compact('user', 'recentLogs'));
    }

    /**
     * Show the edit user form.
     */
    public function edit(User $user)
    {
        $this->authorizeAdmin();

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Inspector', 'guard_name' => 'web']);

        $roles = Role::whereIn('name', ['Super Admin', 'Inspector'])->orderBy('name')->get();
        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update an existing user.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $oldData = $user->toArray();

        $updateFields = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'designation' => $data['designation'] ?? null,
        ];

        if (! empty($data['password'])) {
            $updateFields['password'] = $data['password'];
        }

        $user->update($updateFields);
        $user->syncRoles([$data['role']]);

        AuditLog::log('user_updated', $user, $oldData, $updateFields + ['role' => $data['role']]);

        return redirect()->route('users.show', $user)
            ->with('success', "User '{$user->name}' updated successfully.");
    }

    /**
     * Delete a user account.
     */
    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        AuditLog::log('user_deleted', $user, $user->toArray(), null);

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "User '{$name}' has been deleted.");
    }
}
