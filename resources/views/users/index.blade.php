@extends('layouts.adminlte')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('User Management') }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>
    </div>

@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">User List</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create-user">
                    <i class="fas fa-plus"></i> {{ __('Add New User') }}
                </button>
            </div>
        </div>
        <div class="card-header bg-light">
            <form action="{{ route('users.search') }}" method="GET" class="form-inline">
                <div class="row w-100">
                    <div class="col-md-3 mb-2">
                        <input type="text" name="name" class="form-control form-control-sm w-100" placeholder="Search by name" value="{{ request('name') }}">
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="email" class="form-control form-control-sm w-100" placeholder="Search by email" value="{{ request('email') }}">
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="role" class="form-control form-control-sm w-100">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="status" class="form-control form-control-sm w-100">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-primary mr-1">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->display_name }}</td>
                                <td>
                                    <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-warning btn-sm edit-user-btn" data-toggle="modal" data-target="#modal-edit-user-{{ $user->id }}" data-user-id="{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if (Auth::id() !== $user->id)
                                            <form action="{{ route('users.toggle-active', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-{{ $user->is_active ? 'danger' : 'success' }} btn-sm">
                                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    <!-- Create User Modal -->
    <div class="modal fade" id="modal-create-user">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ __('Create New User') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role_id">{{ __('Role') }}</label>
                            <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Create User') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modals -->
    @foreach($users as $user)
        <div class="modal fade" id="modal-edit-user-{{ $user->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Edit User') }}: {{ $user->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name-{{ $user->id }}">{{ __('Name') }}</label>
                                <input id="name-{{ $user->id }}" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="form-group">
                                <label for="email-{{ $user->id }}">{{ __('Email') }}</label>
                                <input id="email-{{ $user->id }}" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="form-group">
                                <label for="password-{{ $user->id }}">{{ __('Password (leave blank to keep current)') }}</label>
                                <input id="password-{{ $user->id }}" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group">
                                <label for="password_confirmation-{{ $user->id }}">{{ __('Confirm Password') }}</label>
                                <input id="password_confirmation-{{ $user->id }}" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>

                            <!-- Role -->
                            <div class="form-group">
                                <label for="role_id-{{ $user->id }}">{{ __('Role') }}</label>
                                <select id="role_id-{{ $user->id }}" name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ (old('role_id', $user->role_id) == $role->id) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="form-group">
                                <label for="is_active-{{ $user->id }}">{{ __('Status') }}</label>
                                <select id="is_active-{{ $user->id }}" name="is_active" class="form-control @error('is_active') is-invalid @enderror" required>
                                    <option value="1" {{ (old('is_active', $user->is_active) == 1) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (old('is_active', $user->is_active) == 0) ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('is_active')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Update User') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
<script>
    $(function() {
        // Handle edit user button click
        $('.edit-user-btn').on('click', function() {
            const userId = $(this).data('user-id');
            
            // No need to update form action as it's already set in the form
            
            // Fetch user data and populate the form
            $.ajax({
                url: `/users/${userId}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $(`#name-${userId}`).val(data.name);
                    $(`#email-${userId}`).val(data.email);
                    $(`#role_id-${userId}`).val(data.role_id);
                    $(`#is_active-${userId}`).val(data.is_active ? 1 : 0);
                },
                error: function(xhr) {
                    console.error('Error fetching user data:', xhr);
                    alert('Error loading user data. Please try again.');
                }
            });
        });
    });
</script>
@endpush

@push('scripts')
<script>
    @if($errors->any())
        @if(old('_method') == 'PUT')
            // For edit form, open the correct modal
            $(document).ready(function() {
                $('#modal-edit-user-{{ old('user_id', 0) }}').modal('show');
            });
        @else
            // For create form
            $(document).ready(function() {
                $('#modal-create-user').modal('show');
            });
        @endif
    @endif
</script>
@endpush
