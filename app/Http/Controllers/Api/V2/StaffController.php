<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff members for the current tenant.
     */
    public function index(Request $request)
    {
        $viewer = $request->user();
        $query = User::with(['roles', 'department', 'designation'])
            ->where('clinic_id', $viewer->clinic_id);

        if (! $viewer->hasRole('Super Admin') && ! $viewer->hasRole('Clinic Admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin', 'Clinic Admin']);
            });
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleId = $request->integer('role')) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        if ($request->string('status')->toString() === 'trashed') {
            $query->onlyTrashed();
        }

        if ($from = $request->date('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->date('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $perPage = (int) $request->input('per_page', 10);
        $staff = $query->latest()->paginate($perPage);

        $statsQuery = User::query()
            ->where('clinic_id', $viewer->clinic_id);

        if (! $viewer->hasRole('Super Admin') && ! $viewer->hasRole('Clinic Admin')) {
            $statsQuery->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['Super Admin', 'Clinic Admin']);
            });
        }

        $totalStaff = (clone $statsQuery)->count();
        $doctorCount = (clone $statsQuery)->whereHas('roles', function ($q) {
            $q->where('name', 'Doctor');
        })->count();
        $nurseCount = (clone $statsQuery)->whereHas('roles', function ($q) {
            $q->where('name', 'Nurse');
        })->count();
        $onDutyCount = (clone $statsQuery)->whereNull('deleted_at')->where('status', 'active')->count();

        return response()->json([
            'status' => 'success',
            'data' => $staff,
            'meta' => [
                'stats' => [
                    'total_staff' => $totalStaff,
                    'doctors' => $doctorCount,
                    'nurses' => $nurseCount,
                    'on_duty' => $onDutyCount,
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $actor = $request->user();
        if (! $actor->can('create_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'department_id' => 'nullable|integer|exists:departments,id',
            'designation_id' => 'nullable|integer|exists:designations,id',
            'join_date' => 'nullable|date',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->clinic_id = $actor->clinic_id;
        $user->department_id = $validated['department_id'] ?? null;
        $user->designation_id = $validated['designation_id'] ?? null;
        $user->join_date = $validated['join_date'] ?? null;
        $user->status = 'active';
        $user->save();

        $role = \App\Models\Role::find($validated['role_id']);
        if (! $actor->hasRole('Super Admin') && ! $actor->hasRole('Clinic Admin') && ! $actor->hasRole('Admin')) {
            if (in_array($role->name, ['Super Admin', 'Clinic Admin', 'Admin'], true)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }
        $user->assignRole($role);

        $user->load(['roles', 'department', 'designation']);

        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }

    public function update(Request $request, User $staff)
    {
        $actor = $request->user();
        if (! $actor->can('edit_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (! $actor->hasRole('Super Admin') && ! $actor->hasRole('Clinic Admin') && ! $actor->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_id' => 'required|integer|exists:roles,id',
            'department_id' => 'nullable|integer|exists:departments,id',
            'designation_id' => 'nullable|integer|exists:designations,id',
            'join_date' => 'nullable|date',
        ]);

        $staff->name = $validated['name'];
        if (array_key_exists('department_id', $validated)) {
            $staff->department_id = $validated['department_id'];
        }
        if (array_key_exists('designation_id', $validated)) {
            $staff->designation_id = $validated['designation_id'];
        }
        if (array_key_exists('join_date', $validated)) {
            $staff->join_date = $validated['join_date'];
        }
        $staff->save();

        $role = \App\Models\Role::find($validated['role_id']);
        if (! $actor->hasRole('Super Admin') && ! $actor->hasRole('Clinic Admin') && ! $actor->hasRole('Admin')) {
            if (in_array($role->name, ['Super Admin', 'Clinic Admin', 'Admin'], true)) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }
        $staff->roles()->sync([$role->id]);

        $staff->load(['roles', 'department', 'designation']);

        return response()->json([
            'status' => 'success',
            'data' => $staff
        ]);
    }

    public function destroy(Request $request, User $staff)
    {
        $actor = $request->user();
        if (! $actor->can('delete_staff')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if (! $actor->hasRole('Super Admin') && ! $actor->hasRole('Clinic Admin') && ! $actor->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $staff->status = 'inactive';
        $staff->save();
        $staff->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Staff member deleted'
        ]);
    }

    public function show(Request $request, User $staff)
    {
        $viewer = $request->user();

        if ($staff->clinic_id !== $viewer->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $viewer->hasRole('Super Admin') && ! $viewer->hasRole('Clinic Admin') && ! $viewer->hasRole('Admin')) {
            if ($staff->roles()->whereIn('name', ['Super Admin', 'Clinic Admin', 'Admin'])->exists()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        $staff->load(['roles', 'department', 'designation', 'clinic']);

        return response()->json([
            'status' => 'success',
            'data' => $staff,
        ]);
    }

    /**
     * Get the authenticated user's details.
     */
    public function me()
    {
        $user = Auth::user();
        $user->load(['roles.permissions', 'clinic']);

        $abilities = $user->roles
            ->flatMap(fn ($role) => $role->permissions->pluck('name'))
            ->unique()
            ->values()
            ->toArray();

        $payload = array_merge($user->toArray(), ['abilities' => $abilities]);

        return response()->json([
            'status' => 'success',
            'data' => $payload
        ]);
    }
}
