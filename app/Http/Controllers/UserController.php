<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * The user service instance.
     *
     * @var \App\Services\UserService
     */
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @param \App\Services\UserService $userService
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Users can only view their own profile, admin can view any
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

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Users can only edit their own profile, admin can edit any
        if (!Auth::user()->hasRole('admin') && Auth::id() !== $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $roles = $this->userService->getAllRoles();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Toggle user active status.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Search users by name, email, or role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        // Only admin can search users
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have permission to access this page.');
        }

        $users = $this->userService->searchUsers($request->all());
        $roles = $this->userService->getAllRoles();

        return view('users.index', compact('users', 'roles'));
    }
}
