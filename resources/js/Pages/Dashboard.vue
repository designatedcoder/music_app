<template>
    <div id="bg" class="animateHue">
        <img :src="'/storage/'+currentSong.cover" class="opacity-25 w-full h-full" :alt="currentSong.title">
        <div class="absolute inset-0">
            <div class="flex h-full md:items-center md:mx-auto md:w-3/4 lg:w-1/2">
                <div class="hidden md:flex md:flex-col md:flex-1 md:space-y-3 md:relative md:z-10">
                    <h1 class="text-xl text-white font-semibold md:text-2xl">All Songs</h1>
                    <div class="border-2 h-64 overflow-y-auto">
                        <button class="flex flex-col border-b text-white px-2 py-2 w-full transition hover:text-blue-700" v-for="(song, index) in songs" :key="index" @click="play(song)">
                            <span class="font-semibold italic">{{ song.title }}</span>
                            <span>{{ song.artist }}</span>
                        </button>
                    </div>
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" @click.prevent="isFormOpen = !isFormOpen">Add new song</button>
                    <div class="bg-white px-3 py-3" v-if="isFormOpen">
                        <form @submit.prevent="submit">
                            <div class="flex flex-col">
                                <label>Title</label>
                                <input type="text" v-model="form.title">
                            </div>
                            <div class="flex flex-col mt-2">
                                <label>Artist</label>
                                <input type="text" v-model="form.artist">
                            </div>
                            <div class="flex flex-col mt-2">
                                <label>Song</label>
                                <input name="src" type="file" accept="audio/*" @change="uploadSong">
                            </div>
                            <div class="flex flex-col mt-2">
                                <label>Cover Image</label>
                                <input name="cover" type="file" accept="image/*" @change="uploadCover">
                            </div>
                            <div class="text-right mt-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center text-white space-y-3 px-6 mx-auto h-full md:flex-1 md:px-0">
                    <h1 class="text-xl font-semibold md:text-2xl">My Music</h1>
                    <span class="text-xl font-semibold italic">{{ currentSong.title }} - {{ currentSong.artist }} </span>
                    <img :src="'/storage/'+currentSong.cover" class="rounded-lg w-72 h-72" alt="">
                    <div class="flex flex-col items-center">
                        <canvas class="absolute top-0 left-0 w-full h-full" id="canvas"></canvas>
                        <div class="relative">
                            <div class="flex justify-around mt-4">
                                <button type="button" class="transition" @click="prev">
                                    <icon name="prev" class="w-4 h-4 fill-current hover:text-blue-600"></icon>
                                </button>
                                <button type="button" class="transition" @click="play" v-if="!isPlaying">
                                    <icon name="play" class="w-4 h-4 fill-current hover:text-blue-600"></icon>
                                </button>
                                <button type="button" class="transition"  @click="pause" v-else>
                                    <icon name="pause" class="w-4 h-4 fill-current hover:text-blue-600"></icon>
                                </button>
                                <button type="button" class="transition" @click="next">
                                    <icon name="next" class="w-4 h-4 fill-current hover:text-blue-600"></icon>
                                </button>
                            </div>
                            <div class="flex flex-col mt-4 w-full">
                                <div class="flex flex-1 items-center space-x-1">
                                    <icon name="volume-mute" class="w-4 h-4 fill-current cursor-pointer hover:text-blue-600" :class="isMuted ? 'text-blue-600' : ''" @click="mute"></icon>
                                    <input type="range" min="0" max="100" step="1" v-model="volume" class="cursor-pointer" @input="updateVolume">
                                    <icon name="volume-up" class="w-4 h-4 fill-current cursor-pointer hover:text-blue-600" :class="isMaxed ? 'text-blue-600' : ''" @click="max"></icon>
                                </div>
                                <div class="flex flex-1 flex-col items-center space-x-1 mt-4">
                                    <input type="range" max="100" id="progressBar" v-model="audio.currentTime" class="cursor-pointer">
                                    <div class="flex justify-between">
                                        <span>{{ currentTimeTxt }} / {{ currentDurationTxt }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['songs'],
        data() {
            return {
                audio: new Audio(),
                currentSong: {},
                progressBar: {},
                currentDuration: 0,
                currentTime: 0,
                currentTimeTxt: '0:00',
                currentDurationTxt: '0:00',
                index: 0,
                volume: 20,
                isMuted: false,
                isMaxed: false,
                isPlaying: false,
                isFormOpen: false,
                form: this.$inertia.form({
                    title: '',
                    artist: '',
                    src: '',
                    cover: '',
                })
            }
        },
        mounted() {
            this.currentSong = this.songs[this.index]
            this.audio.src = '/storage/'+this.currentSong.src
            this.audio.volume = this.volume / 100
            this.progressBar = document.querySelector('#progressBar')
            this.progressBar.value = 0
            this.initProgress()
        },
        methods: {
            uploadSong(e) {
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.onloadend = () => {
                    this.form.src = reader.result
                }
                reader.readAsDataURL(file)
            },
            uploadCover(e) {
                let file = e.target.files[0]
                let reader = new FileReader()
                reader.onloadend = () => {
                    this.form.cover = reader.result
                }
                reader.readAsDataURL(file)
            },
            submit() {
                this.form.post(this.route('song.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.reset()
                        this.isFormOpen = false
                    }
                })
            },
            initProgress() {
                this.audio.addEventListener('loadedmetadata', () => {
                    this.currentDuration = this.audio.duration
                    let secs = `${parseInt(`${this.currentDuration % 60}`, 10)}`.padStart(2,'0')
                    let mins = parseInt(`${(this.currentDuration/60) % 60}`, 10)
                    this.currentDurationTxt = `${mins}:${secs}`
                    this.progressBar.max = this.currentDuration
                })
                this.audio.addEventListener('timeupdate', () => {
                    this.updateProgress(this.audio.currentTime)
                })
            },
            updateProgress(time) {
                this.progressBar.value = time
                let secs = `${parseInt(`${time % 60}`, 10)}`.padStart(2,'0')
                let mins = parseInt(`${(time/60) % 60}`, 10)
                this.currentTimeTxt = `${mins}:${secs}`
            },
            updateVolume() {
                this.audio.volume = this.volume / 100
                this.isMuted = false
                if(this.volume >= 100) {
                    this.isMaxed = true
                    this.isMuted = false
                } else if(this.volume <= 0) {
                    this.isMaxed = false
                    this.isMuted = true
                }
            },
            mute() {
                this.isMuted = true
                this.isMaxed = false
                this.volume = 0
                this.audio.volume = 0
            },
            max() {
                this.isMaxed = true
                this.isMuted = false
                this.volume = 100
                this.audio.volume = this.volume / 100
            },
            play(song) {
                if(typeof song.src != 'undefined') {
                    this.currentSong = song
                    this.audio.src = '/storage/'+this.currentSong.src
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
