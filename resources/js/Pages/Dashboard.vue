<template>
    <div id="bg" class="animateHue">
        <img :src="currentSong.cover" class="opacity-25 w-full h-full" :alt="currentSong.title">
        <div>
            <button type="button" @click="prev">Prev</button>
            <button type="button" @click="pause" v-if="isPlaying">Pause</button>
            <button type="button" @click="play" v-else>Play</button>
            <button type="button" @click="next">Next</button>
        </div>
        <div class="flex flex-col">
            <button type="button" v-for="(song, index) in songs" :key="index" @click="play(song)">
                <span>{{ song.title }}</span>
                <span>{{ song.artist }}</span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                audio: new Audio(),
                currentSong: {},
                index: 0,
                isPlaying: false,
                songs: [
                    {
                        title: 'Bella Bella Beat',
                        artist: 'Nana Kwabena',
                        src: '/storage/audio/Bella Bella Beat.mp3',
                        cover: '/storage/images/dj2.jpg',
                    },
                    {
                        title: 'Breatha',
                        artist: 'Josh Pan',
                        src: '/storage/audio/Breatha.mp3',
                        cover: '/storage/images/dj3.jpg',
                    },
                    {
                        title: 'Forever',
                        artist: 'Anno Domini Beats',
                        src: '/storage/audio/Forever.mp3',
                        cover: '/storage/images/dj4.jpg',
                    },
                    {
                        title: 'Yah Yah',
                        artist: 'Josh Pan',
                        src: '/storage/audio/Yah Yah.mp3',
                        cover: '/storage/images/dj.jpg',
                    },
                ],
            }
        },
        mounted() {
            this.currentSong = this.songs[this.index]
            this.audio.src = this.currentSong.src
        },
        methods: {
            play(song) {
                if(typeof song.src != 'undefined') {
                    this.currentSong = song
                    this.audio.src = this.currentSong.src
                }
                this.audio.play()
                this.isPlaying = true
            },
            pause() {
                this.audio.pause()
                this.isPlaying = false
            },
            prev() {
                this.index--
                if(this.index < 0) {
                    this.index = this.songs.length - 1
                }
                this.currentSong = this.songs[this.index]
                this.play(this.currentSong)
            },
            next() {
                this.index++
                if(this.index > this.songs.length - 1) {
                    this.index = 0
                }
                this.currentSong = this.songs[this.index]
                this.play(this.currentSong)
            }
        }
    }
</script>
