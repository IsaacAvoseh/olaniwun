<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        // Only admin can view all users
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $users = $this->userService->getPaginatedUsers();
        $roles = $this->userService->getAllRoles();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        // Only admin can create users
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $roles = $this->userService->getAllRoles();
        return view('users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        // Only admin can store users
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        try {
            $this->userService->createUser($request->all());
            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function show(User $user)
    {
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        // Return JSON if request is AJAX
        if (request()->ajax()) {
            return response()->json($user);
        }

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {

        if (!Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $roles = $this->userService->getAllRoles();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        // Users can only update their own profile, admin can update any
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        try {
            $isAdmin = Auth::user()->hasRole('admin');
            $updatedUser = $this->userService->updateUser($user, $request->all(), $isAdmin);

            // Check if user should be logged out
            if ($this->userService->shouldLogoutAfterUpdate($user, $request->all())) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('status', 'Your account has been updated. Please log in again.');
            }

            return redirect()->route($isAdmin ? 'users.index' : 'dashboard')
                ->with('success', 'User updated successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        // Only admin can delete users and cannot delete themselves
        if (!Auth::user()->hasRole('admin') || Auth::id() === $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $this->userService->deleteUser($user);

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
    public function toggleActive(User $user)
    {
        // Only admin can toggle active status and cannot deactivate themselves
        if (!Auth::user()->hasRole('admin') || (Auth::id() === $user->id && $user->is_active)) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to perform this action.');
        }

        $updatedUser = $this->userService->toggleUserActiveStatus($user);

        return redirect()->route('users.index')
            ->with('success', 'User ' . ($updatedUser->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function search(Request $request)
    {

        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $users = $this->userService->searchUsers($request->all());
        $roles = $this->userService->getAllRoles();

        return view('users.index', compact('users', 'roles'));
    }
}
