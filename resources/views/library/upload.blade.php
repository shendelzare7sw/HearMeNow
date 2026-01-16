@extends('layouts.app')

@section('title', 'Upload Music')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-0">
        <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Upload Music</h1>
        <p class="text-gray-400 mb-6 sm:mb-8">Add songs to your personal library</p>

        <!-- Storage Info -->
        <div class="bg-app-gray rounded-lg p-4 mb-6 sm:mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-400">Storage Used</span>
                <span class="text-sm text-white">{{ $storage_used ?? '0 MB' }} / {{ $storage_limit ?? '1 GB' }}</span>
            </div>
            <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full gradient-bg rounded-full transition-all" style="width: {{ $storage_percentage ?? 0 }}%">
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data" x-data="uploadForm()"
            @submit="handleSubmit">
            @csrf

            <!-- Dropzone -->
            <div class="dropzone mb-6" :class="{ 'dragover': isDragging }" @dragover.prevent="isDragging = true"
                @dragleave="isDragging = false" @drop.prevent="handleDrop($event)">
                <input type="file" name="audio_file" id="audio_file" accept=".mp3,.wav,.ogg,.flac,.m4a" class="hidden"
                    @change="handleFileSelect($event)" required>

                <div x-show="!selectedFile">
                    <svg class="w-12 sm:w-16 h-12 sm:h-16 mx-auto text-gray-500 mb-4" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z" />
                    </svg>
                    <p class="text-white font-semibold mb-2 text-sm sm:text-base">Drag and drop your audio file here</p>
                    <p class="text-gray-500 text-sm mb-4">or</p>
                    <label for="audio_file" class="btn-primary cursor-pointer text-sm sm:text-base">
                        Browse Files
                    </label>
                    <p class="text-xs text-gray-500 mt-4">
                        Supported: MP3, WAV, OGG, FLAC, M4A (Max 50MB)
                    </p>
                </div>

                <div x-show="selectedFile" class="text-center">
                    <svg class="w-12 h-12 mx-auto text-brand-green mb-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                    <p class="text-white font-semibold" x-text="selectedFile?.name"></p>
                    <p class="text-sm text-gray-400" x-text="formatFileSize(selectedFile?.size)"></p>
                    <button type="button" @click="clearFile()" class="text-sm text-red-400 hover:text-red-300 mt-2">
                        Remove
                    </button>
                </div>
            </div>

            @error('audio_file')
                <p class="text-red-400 text-sm mb-4">{{ $message }}</p>
            @enderror

            <!-- Song Details -->
            <div class="bg-app-gray rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">
                <h2 class="text-lg font-semibold text-white">Song Details</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Title *</label>
                        <input type="text" name="title" id="title" x-model="title" value="{{ old('title') }}"
                            class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange"
                            placeholder="Song title" required>
                        @error('title')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Artist -->
                    <div>
                        <label for="artist" class="block text-sm font-medium text-gray-300 mb-2">Artist</label>
                        <input type="text" name="artist" id="artist" x-model="artist" value="{{ old('artist') }}"
                            class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange"
                            placeholder="Artist name">
                        @error('artist')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Album -->
                    <div>
                        <label for="album" class="block text-sm font-medium text-gray-300 mb-2">Album</label>
                        <input type="text" name="album" id="album" value="{{ old('album') }}"
                            class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange"
                            placeholder="Album name">
                    </div>

                    <!-- Genre -->
                    <div>
                        <label for="genre_id" class="block text-sm font-medium text-gray-300 mb-2">Genre</label>
                        <select name="genre_id" id="genre_id"
                            class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white focus:border-brand-orange focus:ring-brand-orange">
                            <option value="">Select genre</option>
                            @foreach($genres ?? [] as $genre)
                                <option value="{{ $genre->id }}" {{ old('genre_id') == $genre->id ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-300 mb-2">Year</label>
                        <input type="number" name="year" id="year" value="{{ old('year') }}" min="1900"
                            max="{{ date('Y') }}"
                            class="w-full bg-app-darker border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-brand-orange focus:ring-brand-orange"
                            placeholder="Release year">
                    </div>
                </div>

                <!-- Cover Art -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Cover Art (Optional)</label>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-24 sm:w-32 h-24 sm:h-32 bg-app-darker border border-gray-700 rounded-lg overflow-hidden flex-shrink-0">
                            <img x-show="coverPreview" :src="coverPreview" alt="Cover preview"
                                class="w-full h-full object-cover">
                            <div x-show="!coverPreview"
                                class="w-full h-full flex items-center justify-center text-gray-600">
                                <svg class="w-8 sm:w-12 h-8 sm:h-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <input type="file" name="cover_art" id="cover_art" accept="image/*" class="hidden"
                                @change="handleCoverSelect($event)">
                            <label for="cover_art" class="btn-secondary text-sm cursor-pointer inline-block">
                                Choose Image
                            </label>
                            <p class="text-xs text-gray-500 mt-2">
                                JPEG, PNG, WebP (Max 2MB)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Visibility -->
                <div class="pt-2 border-t border-gray-700">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_public" value="1" class="w-5 h-5 rounded border-gray-600 bg-app-darker text-brand-orange focus:ring-brand-orange focus:ring-offset-app-gray transition-colors">
                        <div>
                            <span class="block text-sm font-medium text-white group-hover:text-brand-orange transition-colors">Make Public</span>
                            <span class="block text-xs text-gray-400">Song will be discoverable by everyone in streaming section</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Upload Progress -->
            <div x-show="isUploading" class="mt-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-white">Uploading...</span>
                    <span class="text-sm text-gray-400" x-text="uploadProgress + '%'"></span>
                </div>
                <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full gradient-bg rounded-full transition-all" :style="{ width: uploadProgress + '%' }">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4">
                <a href="{{ route('library.index') }}" class="btn-secondary text-center">
                    Cancel
                </a>
                <button type="submit" class="btn-primary" :disabled="isUploading || !selectedFile">
                    <span x-show="!isUploading">Upload Song</span>
                    <span x-show="isUploading" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Uploading...
                    </span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function uploadForm() {
                return {
                    selectedFile: null,
                    coverPreview: null,
                    title: '{{ old('title') }}',
                    artist: '{{ old('artist') }}',
                    isDragging: false,
                    isUploading: false,
                    uploadProgress: 0,

                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.selectedFile = file;
                            this.extractMetadata(file);
                        }
                    },

                    handleDrop(event) {
                        this.isDragging = false;
                        const file = event.dataTransfer.files[0];
                        if (file && this.isValidAudioFile(file)) {
                            this.selectedFile = file;
                            document.getElementById('audio_file').files = event.dataTransfer.files;
                            this.extractMetadata(file);
                        }
                    },

                    isValidAudioFile(file) {
                        const validTypes = ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/flac', 'audio/mp4'];
                        return validTypes.includes(file.type);
                    },

                    extractMetadata(file) {
                        if (!this.title) {
                            let name = file.name.replace(/\.[^/.]+$/, '');
                            if (name.includes(' - ')) {
                                const parts = name.split(' - ');
                                this.artist = parts[0].trim();
                                this.title = parts.slice(1).join(' - ').trim();
                            } else {
                                this.title = name;
                            }
                        }
                    },

                    handleCoverSelect(event) {
                        const file = event.target.files[0];
                        if (file && file.type.startsWith('image/')) {
                            this.coverPreview = URL.createObjectURL(file);
                        }
                    },

                    clearFile() {
                        this.selectedFile = null;
                        this.title = '';
                        this.artist = '';
                        document.getElementById('audio_file').value = '';
                    },

                    formatFileSize(bytes) {
                        if (!bytes) return '';
                        const units = ['B', 'KB', 'MB', 'GB'];
                        let i = 0;
                        while (bytes >= 1024 && i < units.length - 1) {
                            bytes /= 1024;
                            i++;
                        }
                        return bytes.toFixed(1) + ' ' + units[i];
                    },

                    handleSubmit(event) {
                        if (!this.selectedFile) {
                            event.preventDefault();
                            return;
                        }
                        this.isUploading = true;
                    }
                }
            }
        </script>
    @endpush
@endsection