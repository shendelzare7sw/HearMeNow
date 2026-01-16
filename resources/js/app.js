import './bootstrap';

import * as Turbo from '@hotwired/turbo';
import Alpine from 'alpinejs';
import { Howl, Howler } from 'howler';

// Make globally available
window.Alpine = Alpine;
window.Howl = Howl;
window.Howler = Howler;

// Music Player Store
Alpine.store('player', {
    currentSong: null,
    isPlaying: false,
    volume: parseFloat(localStorage.getItem('player_volume') || 0.7),
    isMuted: false,
    progress: 0,
    duration: 0,
    currentTime: 0,

    queue: JSON.parse(localStorage.getItem('player_queue') || '[]'),
    queueIndex: parseInt(localStorage.getItem('player_queue_index') || 0),
    history: [],

    shuffle: localStorage.getItem('player_shuffle') === 'true',
    repeat: localStorage.getItem('player_repeat') || 'none',

    sound: null,
    progressInterval: null,

    init() {
        Howler.volume(this.volume);
    },

    play(song) {
        if (this.sound) {
            this.sound.unload();
            clearInterval(this.progressInterval);
        }

        this.currentSong = song;

        this.sound = new Howl({
            src: [song.stream_url || `/songs/${song.id}/stream`],
            html5: true,
            volume: this.volume,
            onplay: () => {
                this.isPlaying = true;
                this.duration = this.sound.duration();
                this.startProgressTracker();
                this.logPlay(song.id);
            },
            onpause: () => {
                this.isPlaying = false;
            },
            onend: () => {
                this.isPlaying = false;
                this.handleSongEnd();
            },
            onloaderror: (id, error) => {
                console.error('Error loading audio:', error);
                this.isPlaying = false;
            }
        });

        this.sound.play();
    },

    toggle() {
        if (!this.sound) return;
        if (this.isPlaying) {
            this.sound.pause();
        } else {
            this.sound.play();
        }
    },

    pause() {
        if (this.sound) this.sound.pause();
    },

    resume() {
        if (this.sound) this.sound.play();
    },

    next() {
        if (this.queue.length === 0) return;
        if (this.currentSong) this.history.push(this.queueIndex);

        if (this.shuffle) {
            let newIndex;
            do {
                newIndex = Math.floor(Math.random() * this.queue.length);
            } while (newIndex === this.queueIndex && this.queue.length > 1);
            this.queueIndex = newIndex;
        } else {
            this.queueIndex++;
            if (this.queueIndex >= this.queue.length) {
                if (this.repeat === 'all') {
                    this.queueIndex = 0;
                } else {
                    this.queueIndex = this.queue.length - 1;
                    this.saveQueueIndex();
                    return;
                }
            }
        }
        this.saveQueueIndex();
        this.play(this.queue[this.queueIndex]);
    },

    prev() {
        if (this.currentTime > 3) {
            this.seek(0);
            return;
        }
        if (this.history.length > 0) {
            this.queueIndex = this.history.pop();
            this.play(this.queue[this.queueIndex]);
        } else if (this.queueIndex > 0) {
            this.queueIndex--;
            this.play(this.queue[this.queueIndex]);
        }
        this.saveQueueIndex();
    },

    handleSongEnd() {
        if (this.repeat === 'one') {
            this.seek(0);
            this.resume();
        } else {
            this.next();
        }
    },

    seek(position) {
        if (this.sound) {
            const seekTime = position * this.duration;
            this.sound.seek(seekTime);
            this.currentTime = seekTime;
            this.progress = position * 100;
        }
    },

    setVolume(value) {
        this.volume = value;
        this.isMuted = value === 0;
        Howler.volume(value);
        localStorage.setItem('player_volume', value);
    },

    toggleMute() {
        if (this.isMuted) {
            Howler.volume(this.volume || 0.7);
            this.isMuted = false;
        } else {
            Howler.volume(0);
            this.isMuted = true;
        }
    },

    toggleShuffle() {
        this.shuffle = !this.shuffle;
        localStorage.setItem('player_shuffle', this.shuffle);
    },

    cycleRepeat() {
        const modes = ['none', 'all', 'one'];
        const currentIndex = modes.indexOf(this.repeat);
        this.repeat = modes[(currentIndex + 1) % modes.length];
        localStorage.setItem('player_repeat', this.repeat);
    },

    addToQueue(song) {
        this.queue.push(song);
        this.saveQueue();
    },

    playQueue(songs, startIndex = 0) {
        this.queue = songs;
        this.queueIndex = startIndex;
        this.saveQueue();
        this.saveQueueIndex();
        if (songs.length > 0) this.play(songs[startIndex]);
    },

    removeFromQueue(index) {
        this.queue.splice(index, 1);
        if (index < this.queueIndex) this.queueIndex--;
        this.saveQueue();
    },

    clearQueue() {
        this.queue = [];
        this.queueIndex = 0;
        this.saveQueue();
    },

    saveQueue() {
        localStorage.setItem('player_queue', JSON.stringify(this.queue));
    },

    saveQueueIndex() {
        localStorage.setItem('player_queue_index', this.queueIndex);
    },

    startProgressTracker() {
        clearInterval(this.progressInterval);
        this.progressInterval = setInterval(() => {
            if (this.sound && this.isPlaying) {
                this.currentTime = this.sound.seek() || 0;
                this.duration = this.sound.duration() || 0;
                this.progress = this.duration > 0 ? (this.currentTime / this.duration) * 100 : 0;
            }
        }, 100);
    },

    async logPlay(songId) {
        try {
            await window.axios.post(`/player/${songId}/play`);
        } catch (error) {
            console.error('Failed to log play:', error);
        }
    },

    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    }
});

Alpine.start();
