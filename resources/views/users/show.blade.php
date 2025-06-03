<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Edit User') }}
                </a>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">ID</p>
                                <p class="mt-1">{{ $user->id }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Name</p>
                                <p class="mt-1">{{ $user->name }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Email</p>
                                <p class="mt-1">{{ $user->email }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Role</p>
                                <p class="mt-1">{{ ucfirst($user->role) }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Created At</p>
                                <p class="mt-1">{{ $user->created_at->format('F d, Y h:i A') }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-500">Last Updated</p>
                                <p class="mt-1">{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Actions</h3>

                            @if (Auth::id() !== $user->id)
                                <div class="mb-4">
                                    <form action="{{ route('users.toggle-active', $user) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <x-primary-button type="submit" class="bg-{{ $user->is_active ? 'red' : 'green' }}-500 hover:bg-{{ $user->is_active ? 'red' : 'green' }}-700">
                                            {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
                                        </x-primary-button>
                                    </form>
                                </div>

                                <div class="mb-4">
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <x-danger-button type="submit">
                                            Delete Account
                                        </x-danger-button>
                                    </form>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">You cannot perform these actions on your own account.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
