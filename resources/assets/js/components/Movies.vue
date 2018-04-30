<template lang="html">
    <v-content>
        <v-container grid-list-md>
            <v-dialog dark v-model="movieDialog" persistent max-width="800px">
                <v-card>
                    <v-card-title>
                        MOVIE PANEL
                        <v-spacer></v-spacer>
                        <v-btn icon @click.native="movieDialog = false" dark>
                            <v-icon>close</v-icon>
                        </v-btn>
                    </v-card-title>

                    <v-divider></v-divider>

                    <v-card-text>
                        <v-form v-model="valid" ref="form">
                            <v-text-field
                            type="text"
                            label="Title"
                            v-model="movie.title"
                            required
                            :rules="[required]"
                            ></v-text-field>

                            <v-text-field
                            type="number"
                            label="Year"
                            v-model="movie.year"
                            required
                            :rules="[required]"
                            ></v-text-field>

                            <v-text-field
                            type="text"
                            label="Url"
                            v-model="movie.url"
                            required
                            :rules="[required]"
                            ></v-text-field>

                            <v-text-field
                            type="text"
                            label="Youtube Id"
                            v-model="movie.youtubeId"
                            ></v-text-field>

                            <v-select
                            label="Select"
                            :items="categories"
                            v-model="movie.category_id"
                            required
                            :rules="[required]"
                            ></v-select>

                            <input type="file" id="file" ref="file" @change="previewImage"/>
                            <div class="row">
                                <div class="col-md-6 image-preview" v-if="preview_image.length > 0">
                                    <img class="img-fluid" :src="preview_image" style="max-height:200px;">
                                </div>
                            </div>

                            <v-btn color="success" @click="storeMovie">
                                Submit
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-dialog
              v-model="confirmDialog"
              max-width="500px"
              transition="dialog-transition">
                <v-card>
                    <v-card-text primary class="title">
                        Are you sure you want to delete this?
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="error" @click="deleteMovie">
                            Delete
                            <v-icon>delete</v-icon>
                        </v-btn>
                        <v-btn light @click.native="confirmDialog = false">
                            Cancel
                            <v-icon>remove_circle</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-dialog
              v-model="viewDialog"
              max-width="670px"
              transition="dialog-transition"
            >
                <v-card>
                    <v-card-text>
                        <youtube :video-id="movie.youtubeId" @ready="ready"></youtube>
                    </v-card-text>
                </v-card>
            </v-dialog>

            <v-layout row wrap>
                <v-flex d-flex xs12 sm6 md3 v-for="movie in movies" v-bind:key="movies.id">
                    <v-card color="grey darken-3" dark>
                        <v-card-text>
                            <div class="title mb-1">{{ movie.title }}</div>
                            <div class="subheading mb-1">{{ movie.category_name }}</div>
                            <div class="subheading mb-2">{{ movie.year }}</div>
                            <img v-bind:src="image_path + movie.image" style="max-height: 600px; width:100%;">
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn icon @click="downloadMovie(movie.url)">
                                <v-icon>file_download</v-icon>
                            </v-btn>
                            <v-btn icon @click="viewMovie(movie.youtubeId)" v-if="movie.youtubeId">
                                <v-icon>theaters</v-icon>
                            </v-btn>
                            <v-btn icon @click="editMovie(movie)" v-if="user">
                                <v-icon color="teal">mode_edit</v-icon>
                            </v-btn>
                            <v-btn icon @click="confirmDelete(movie.id)" v-if="user">
                                <v-icon color="pink">delete</v-icon>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>

            </v-layout>
        </v-container>
        <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
            <span slot="no-more">
            </span>
        </infinite-loading>
    </v-content>
</template>

<script>
import { mapState } from 'vuex'
import { EventBus } from '../event-bus/event-bus'
import MovieService from '../services/MovieService'
import CategoryService from '../services/CategoryService'
import InfiniteLoading from 'vue-infinite-loading'
import Nprogress from 'nprogress'
import 'nprogress/nprogress.css'

export default {
    data () {
        return {
            movieDialog: false,
            viewDialog: false,
            confirmDialog: false,
            valid: false,
            edit: false,
            image_path: APP_URL + '/vue-laravel/public/files/',
            movies: [],
            movie: {
                id: '',
                category_id: '',
                title: '',
                year: '',
                url: '',
                youtubeId: ''
            },
            categories: [],
            file: '',
            preview_image: '',
            youtubePlayer: null,
            required: (value) => !!value || 'This field is required.'
        }
    },

    props: {
        source: String
    },

    computed: {
        ...mapState(['user', 'filter'])
    },

    components: {
        InfiniteLoading,
    },

    methods: {
        infiniteHandler($state) {
            let current_page = parseInt(this.movies.length / 8) + 1;
            let filter = {
                page: current_page,
                search: this.filter.search,
                category: this.filter.category
            };
            MovieService.get(filter)
                .then(({ data }) => {
                    if (data.data.length) {
                        this.movies = this.movies.concat(data.data);
                        $state.loaded();
                        if (data.meta.last_page === current_page) {
                            $state.complete();
                        }
                    } else {
                        $state.complete();
                    }
                });
        },
        makePagination(links, meta) {
            let pagination = {
                current_page: meta.current_page,
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
            }
            this.pagination = pagination;
        },
        storeMovie() {
            if(!this.$refs.form.validate()) {
                return false;
            }

            Nprogress.start();
            let formData = new FormData();
            formData.append('title', this.movie.title);
            formData.append('category_id', this.movie.category_id);
            formData.append('year', this.movie.year);
            formData.append('url', this.movie.url);
            formData.append('youtubeId', this.movie.youtubeId);
            formData.append('file', this.file);

            if(!this.edit) {
                // Insert movie
                MovieService.post(formData)
                    .then((response) => {
                        const movie = (response.data).data
                        Nprogress.done();
                        this.movieDialog = false;
                    });
            } else {
                // Update movie
                formData.append('id', this.movie.id);

                // Laravel BUG: Input from PUT requests sent as multipart/form-data:
                // solution: You should send POST and set _method to
                // PUT (same as sending forms) to make your files visible
                // https://github.com/laravel/framework/issues/13457
                formData.append('_method', 'PUT');

                MovieService.post(formData)
                    .then((response) => {
                        const movie = (response.data).data
                        Nprogress.done();
                        this.movieDialog = false;
                    });
            }
        },
        previewImage(event) {
            var input = event.target;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = (e) => {
                    this.preview_image = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                this.preview_image = '';
            }

            this.file = this.$refs.file.files[0];
        },
        removeImage: function (item) {
            item.image = false;
        },
        editMovie(movie) {
            this.movieDialog = true;

            this.movie.id = movie.id;
            this.movie.category_id = movie.category_id;
            this.movie.title = movie.title;
            this.movie.year = movie.year;
            this.movie.url = movie.url;
            this.movie.youtubeId = movie.youtubeId;
            this.preview_image = this.image_path + movie.image;
            this.edit = true;
        },
        confirmDelete(id) {
            this.confirmDialog = true;
            this.movie.id = id;
        },
        viewMovie(youtubeId) {
            this.viewDialog = true;
            this.movie.youtubeId = youtubeId;
        },
        downloadMovie(url) {
            window.location.href = APP_FILE + url
            // window.open(APP_FILE + url, '_blank')
            // axios({
            //     url: APP_FILE + url,
            //     method: 'GET',
            //     responseType: 'blob', // important
            // }).then((response) => {
            //     const url = window.URL.createObjectURL(new Blob([response.data]));
            //     const link = document.createElement('a');
            //     link.href = url;
            //     link.setAttribute('download');
            //     document.body.appendChild(link);
            //     link.click();
            // });
        },
        deleteMovie() {
            Nprogress.start();
            MovieService.delete(this.movie.id)
                .then((response) => {
                    const movie = (response.data).data
                    this.movie.id = '';
                    Nprogress.done();
                    this.confirmDialog = false;
                });
        },
        resetForm() {
            this.movie.id = '';
            this.movie.title = '';
            this.movie.year = '';
            this.movie.url = '';
            this.movie.youtubeId = '';
            this.file = '';
            this.preview_image = '';
            this.$refs.file.value = null;
            this.edit = false;
        },
        resetInfiniteLoading() {
            this.movies = [];
            this.$nextTick(() => {
                this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
            });
        },
        updateList(movie) {
            let index = this.movies.findIndex(x => x.id == movie.id);
            let new_movie = {
                id: movie.id,
                category_id: movie.category_id,
                category_name: movie.category_name,
                title: movie.title,
                year: movie.year,
                url: movie.url,
                youtubeId: movie.youtubeId,
                image: movie.image
            };

            switch (movie.action) {
                case 'create':
                    this.movies.unshift(new_movie);
                    break;

                case 'update':
                    this.movies.splice(index, 1, new_movie);
                    break;

                case 'delete':
                    this.movies.splice(index, 1);
                    break;

                default:
                    break;
            }
        },
        ready(youtubePlayer) {
            this.youtubePlayer = youtubePlayer;
        }
    },

    async mounted () {
        Echo.channel('movie-channel')
            .listen('MovieEvent', (movie) => {
                this.updateList(movie);
                if (this.user) {
                    this.resetForm();
                }
                this.$notify({type: movie.type, title: movie.message});
            });

        EventBus.$on('filter-changed', () => {
            this.resetInfiniteLoading();
        });

        EventBus.$on('add-movie', () => {
            this.movieDialog = true;
        });

        this.categories = ((await CategoryService.get()).data).data
    },

    watch: {
        movieDialog: function(val) {
            if(!val) {
                this.$refs.form.reset()
            }
        },
        viewDialog: function(val) {
            if(!val) {
                this.youtubePlayer.pauseVideo()
            }
        }
    }
}
</script>

<style lang="css">
</style>
