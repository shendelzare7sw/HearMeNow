@extends('layouts.app')

@section('title', isset($playlist) ? 'Edit Playlist' : 'Create Playlist')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-6">
            {{ isset($playlist) ? 'Edit Playlist' : 'Create Playlist' }}
        </h1>

        <form action="{{ isset($playlist) ? route('playlists.update', $playlist) : route('playlists.store') }}"
            method="POST" enctype="multipart/form-data" class="bg-app-gray rounded-lg p-4 sm:p-6 space-y-6"
            x-data="{ coverPreview: '{{ isset($playlist) && $playlist->cover_url ? $playlist->cover_url : '' }}' }">
            @csrf
            @if(isset($playlist))
                @method('PUT')
            @endif

            <!-- Cover Image -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Cover Image</label>
                <div class="flex items-start gap-4">
                    <div class="w-32 h-32 bg-app-darker border border-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                        <template x-if="coverPreview">
                            <img :src="coverPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!coverPreview">
                            <div class="w-full h-full flex items-center justify-center gradient-bg">
                                <svg class="w-12 h-12 text-black/60" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z" />
                                </svg>
                            </div>
                        </template>
                    </div>
                    <div>
                        <input type="file" name="cover" id="cover" accept="image/*" class="hidden"
                            @change="coverPreview = URL.createObjectURL($event.target.files[0])">
                        <label for="cover" class="btn-secondary text-sm cursor-pointer inline-block">
                            Choose Image
                        </label>
                        <p class="text-xs text-gray-500 mt-2">JPEG, PNG (Max 2MB)</p>
                    </div>
                </div>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Playlist Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $playlist->name ?? '') }}"
                    class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange"
                    placeholder="My Awesome Playlist" required>
                @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange resize-none"
                    placeholder="Add an optional description">{{ old('description', $playlist->description ?? '') }}</textarea>
            </div>

            <!-- Visibility -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Visibility</label>
                <div class="flex gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="is_public" value="0" {{ old('is_public', $playlist->is_public ?? 0) == 0 ? 'checked' : '' }}
                            class="text-brand-orange focus:ring-brand-orange bg-app-darker border-gray-700">
                        <span class="ml-2 text-white">Private</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="is_public" value="1" {{ old('is_public', $playlist->is_public ?? 0) == 1 ? 'checked' : '' }}
                            class="text-brand-orange focus:ring-brand-orange bg-app-darker border-gray-700">
                        <span class="ml-2 text-white">Public</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <a href="{{ route('playlists.index') }}" class="btn-secondary text-center">Cancel</a>
                <button type="submit" class="btn-primary">
                    {{ isset($playlist) ? 'Save Changes' : 'Create Playlist' }}
                </button>
            </div>
        </form>
    </div>
@endsection