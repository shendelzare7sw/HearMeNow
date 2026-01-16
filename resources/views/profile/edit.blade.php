@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
            <p class="text-gray-400">Manage your account settings and profile photo</p>
        </div>

        <!-- Profile Photo Section -->
        <div class="bg-app-gray rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Profile Photo</h2>
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                x-data="{ previewUrl: null }">
                @csrf
                @method('PATCH')

                <div class="flex items-center gap-6">
                    <!-- Current Avatar -->
                    <div class="flex-shrink-0">
                        <template x-if="previewUrl">
                            <img :src="previewUrl" class="w-24 h-24 rounded-full object-cover">
                        </template>
                        <template x-if="!previewUrl">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                    class="w-24 h-24 rounded-full object-cover">
                            @else
                                <div
                                    class="w-24 h-24 rounded-full gradient-bg flex items-center justify-center text-black text-3xl font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </template>
                    </div>

                    <!-- Upload Button -->
                    <div class="flex-1">
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden" @change="
                                   const file = $event.target.files[0];
                                   if (file) {
                                       previewUrl = URL.createObjectURL(file);
                                   }
                               ">
                        <label for="avatar"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-app-light-gray hover:bg-gray-600 text-white rounded-lg cursor-pointer transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z" />
                            </svg>
                            Choose Photo
                        </label>
                        <p class="mt-2 text-sm text-gray-500">JPG, PNG or GIF. Max 2MB.</p>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div x-show="previewUrl" class="mt-4">
                    <button type="submit" class="btn-primary">
                        Save Photo
                    </button>
                </div>
            </form>
        </div>

        <!-- Profile Information -->
        <div class="bg-app-gray rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Profile Information</h2>
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-brand-orange focus:ring-brand-orange"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-brand-orange focus:ring-brand-orange"
                        required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <p class="mt-2 text-sm text-yellow-400">
                            Your email address is unverified.
                        </p>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary">
                        Save Changes
                    </button>
                </div>

                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-brand-green">Profile updated successfully.</p>
                @endif
            </form>
        </div>

        <!-- Update Password -->
        <div class="bg-app-gray rounded-lg p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Update Password</h2>
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4" x-data="{ showPasswords: false }">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-300 mb-1">Current
                        Password</label>
                    <div class="relative">
                        <input :type="showPasswords ? 'text' : 'password'" name="current_password" id="current_password"
                            class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 pr-10 focus:border-brand-orange focus:ring-brand-orange">
                        <button type="button" @click="showPasswords = !showPasswords"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-white">
                            <svg x-show="!showPasswords" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPasswords" class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1">New Password</label>
                    <input :type="showPasswords ? 'text' : 'password'" name="password" id="password"
                        class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-brand-orange focus:ring-brand-orange">
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirm
                        Password</label>
                    <input :type="showPasswords ? 'text' : 'password'" name="password_confirmation"
                        id="password_confirmation"
                        class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-brand-orange focus:ring-brand-orange">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary">
                        Update Password
                    </button>
                </div>

                @if (session('status') === 'password-updated')
                    <p class="text-sm text-brand-green">Password updated successfully.</p>
                @endif
            </form>
        </div>

        <!-- Delete Account -->
        <div class="bg-app-gray rounded-lg p-6" x-data="{ showDeleteModal: false }">
            <h2 class="text-lg font-semibold text-white mb-2">Delete Account</h2>
            <p class="text-gray-400 text-sm mb-4">
                Once your account is deleted, all of your data will be permanently removed.
            </p>
            <button @click="showDeleteModal = true"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                Delete Account
            </button>

            <!-- Delete Modal -->
            <div x-show="showDeleteModal" x-transition
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/70" style="display: none;">
                <div class="bg-app-gray rounded-lg shadow-2xl max-w-md w-full mx-4 p-6"
                    @click.away="showDeleteModal = false">
                    <h3 class="text-xl font-bold text-white mb-2">Delete Account</h3>
                    <p class="text-gray-400 mb-4">
                        Are you sure you want to delete your account? This action cannot be undone.
                    </p>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-4">
                            <label for="delete_password"
                                class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                            <input type="password" name="password" id="delete_password"
                                placeholder="Enter your password to confirm"
                                class="w-full bg-app-darker border border-gray-700 text-white rounded-lg py-2 px-4 focus:border-red-500 focus:ring-red-500">
                        </div>

                        <div class="flex gap-3">
                            <button type="button" @click="showDeleteModal = false"
                                class="flex-1 py-2 px-4 bg-gray-700 hover:bg-gray-600 text-white rounded-full">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded-full">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection