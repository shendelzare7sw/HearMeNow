@extends('layouts.app')

@section('title', 'Edit Song')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-white">Edit Song</h1>
            <a href="{{ route('library.index') }}" class="text-gray-400 hover:text-white">Cancel</a>
        </div>

        <div class="bg-app-gray/50 rounded-xl p-6 sm:p-8">
            <form action="{{ route('songs.update', $song) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <x-input-label for="title" value="Title" class="text-white" />
                    <x-text-input id="title" name="title" type="text"
                        class="mt-1 block w-full bg-app-darker border-gray-700 text-white focus:border-brand-orange focus:ring-brand-orange"
                        :value="old('title', $song->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Artist -->
                    <div>
                        <x-input-label for="artist" value="Artist" class="text-white" />
                        <x-text-input id="artist" name="artist" type="text"
                            class="mt-1 block w-full bg-app-darker border-gray-700 text-white focus:border-brand-orange focus:ring-brand-orange"
                            :value="old('artist', $song->artist)" />
                        <x-input-error class="mt-2" :messages="$errors->get('artist')" />
                    </div>

                    <!-- Album -->
                    <div>
                        <x-input-label for="album" value="Album" class="text-white" />
                        <x-text-input id="album" name="album" type="text"
                            class="mt-1 block w-full bg-app-darker border-gray-700 text-white focus:border-brand-orange focus:ring-brand-orange"
                            :value="old('album', $song->album)" />
                        <x-input-error class="mt-2" :messages="$errors->get('album')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Genre -->
                    <div>
                        <x-input-label for="genre_id" value="Genre" class="text-white" />
                        <select id="genre_id" name="genre_id"
                            class="mt-1 block w-full bg-app-darker border-gray-700 text-white rounded-md shadow-sm focus:border-brand-orange focus:ring-brand-orange">
                            <option value="">Select genre</option>
                            @foreach(App\Models\Genre::all() as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id', $song->genre_id) == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('genre_id')" />
                    </div>

                    <!-- Year -->
                    <div>
                        <x-input-label for="year" value="Year" class="text-white" />
                        <x-text-input id="year" name="year" type="number"
                            class="mt-1 block w-full bg-app-darker border-gray-700 text-white focus:border-brand-orange focus:ring-brand-orange"
                            :value="old('year', $song->year)" min="1900" max="{{ date('Y') + 1 }}" />
                        <x-input-error class="mt-2" :messages="$errors->get('year')" />
                    </div>
                </div>

                <!-- Cover Art -->
                <div>
                    <x-input-label for="cover" value="Cover Art (Optional)" class="text-white" />
                    <div class="mt-2 flex items-center gap-6" x-data="{ preview: '{{ $song->cover_url }}' }">
                        <div
                            class="w-24 h-24 bg-app-darker rounded-lg border-2 border-dashed border-gray-700 flex items-center justify-center overflow-hidden relative">
                            <template x-if="preview">
                                <img :src="preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!preview">
                                <svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            </template>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="cover" name="cover" accept="image/*" class="hidden"
                                @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = e => preview = e.target.result; reader.readAsDataURL(file); }">
                            <label for="cover" class="btn-secondary cursor-pointer inline-block">
                                Change Image
                            </label>
                            <p class="mt-2 text-xs text-gray-500">JPEG, PNG, WebP (Max 2MB)</p>
                        </div>
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('cover')" />
                </div>

                <!-- Visibility -->
                <div class="pt-4 border-t border-gray-800">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', $song->is_public) ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-600 bg-app-darker text-brand-orange focus:ring-brand-orange focus:ring-offset-app-gray transition-colors">
                        <div>
                            <span class="block text-sm font-medium text-white group-hover:text-brand-orange transition-colors">Make Public</span>
                            <span class="block text-xs text-gray-400">Song will be discoverable by everyone in streaming section</span>
                        </div>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-800">
                    <a href="{{ route('library.index') }}" class="text-gray-400 hover:text-white font-medium">Cancel</a>
                    <button type="submit" class="btn-primary">
                        Update Song
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-800">
                <h3 class="text-lg font-bold text-white mb-2">Delete Song</h3>
                <p class="text-sm text-gray-400 mb-4">Current file: {{ $song->file_path }}
                    ({{ $song->formatted_file_size }})</p>

                <div x-data="{ showDeleteModal: false }">
                    <button @click="showDeleteModal = true" class="text-red-500 hover:text-red-400 text-sm font-medium">
                        Delete this song permanently
                    </button>

                    <!-- Delete Modal -->
                    <div x-show="showDeleteModal" style="display: none;"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4" x-transition.opacity>
                        <div class="bg-app-gray rounded-xl max-w-sm w-full p-6 shadow-2xl"
                            @click.away="showDeleteModal = false">
                            <h3 class="text-xl font-bold text-white mb-2">Delete Song?</h3>
                            <p class="text-gray-400 mb-6">Are you sure you want to delete "{{ $song->title }}"? This action
                                cannot be undone.</p>
                            <div class="flex gap-3">
                                <button @click="showDeleteModal = false" class="flex-1 btn-secondary">Cancel</button>
                                <form action="{{ route('songs.destroy', $song) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-full transition-colors">
                                        Yes, Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection