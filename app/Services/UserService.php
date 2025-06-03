<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function getPaginatedUsers(int $perPage = 2): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function getAllRoles(): Collection
    {
        return Role::all();
    }

    public function createUser(array $data): User
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $data['role_id'],
            'is_active' => true,
        ]);
    }


    public function updateUser(User $user, array $data, bool $isAdmin = false): User
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // Only admin can change roles and active status
        if ($isAdmin) {
            $rules['role_id'] = 'required|exists:roles,id';
            $rules['is_active'] = 'required|boolean';
        }


        if (!empty($data['password'])) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }


        if ($isAdmin) {
            $userData['role_id'] = $data['role_id'];
            $userData['is_active'] = $data['is_active'];
        }

        $user->update($userData);

        return $user->fresh();
    }


    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }


    public function toggleUserActiveStatus(User $user): User
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return $user->fresh();
    }


    public function searchUsers(array $criteria, int $perPage = 2): LengthAwarePaginator
    {
        $query = User::query();

        // Filter by name
        if (!empty($criteria['name'])) {
            $query->where('name', 'like', '%' . $criteria['name'] . '%');
        }

        // Filter by email
        if (!empty($criteria['email'])) {
            $query->where('email', 'like', '%' . $criteria['email'] . '%');
        }

        // Filter by role
        if (!empty($criteria['role'])) {
            $query->whereHas('role', function($q) use ($criteria) {
                $q->where('name', $criteria['role']);
            });
        }

        // Filter by status
        if (!empty($criteria['status'])) {
            $isActive = $criteria['status'] === 'active';
            $query->where('is_active', $isActive);
        }

        return $query->paginate($perPage)->withQueryString();
    }


    public function shouldLogoutAfterUpdate(User $user, array $updatedData): bool
    {
        return Auth::id() === $user->id && (
            (isset($updatedData['is_active']) && !$updatedData['is_active']) ||
            (isset($updatedData['role_id']) && $updatedData['role_id'] !== Auth::user()->role_id)
        );
    }
}
