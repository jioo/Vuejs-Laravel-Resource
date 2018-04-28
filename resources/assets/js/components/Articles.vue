<template lang="html">
    <div class="content" >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <form id="form" @submit.prevent="storeMovie" class="mb-2" v-if="user">
                        <div class="form-group">
                            <input type="text" class="form-control mb-2" placeholder="Title" v-model="movie.title" required>
                            <input type="number" class="form-control mb-2" placeholder="Year" v-model="movie.year" required>
                            <input type="text" class="form-control mb-2" placeholder="Youtube Id" v-model="movie.youtubeId">
                            <select class="custom-select mr-2" v-model="movie.category_id" required >
                                <option value="" hidden> Choose One </option>
                                <option v-for="category in categories" v-bind:key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            <input type="file" id="file" ref="file" @change="previewImage"/>
                            <div class="row">
                                <div class="col-md-6 image-preview" v-if="preview_image.length > 0">
                                    <img class="img-fluid" :src="preview_image" style="max-height:200px;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-light btn-block">Save</button>
                    </form>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 mb-2" v-for="movie in movies" v-bind:key="movies.id">
                    <div class="card card-body">
                        <img class="img-fluid" v-bind:src="image_path + movie.image" style="max-height: 400px;">
                        <h3>{{ movie.title }}</h3>
                        <small>Category: {{ movie.category_name }}</small>
                        <hr v-if="user">
                        <button type="button" name="button" class="btn btn-warning mb-2" @click="editMovie(movie)" v-if="user">Edit</button>
                        <button type="button" name="button" class="btn btn-danger" @click="deleteMovie(movie.id)" v-if="user">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <infinite-loading @infinite="infiniteHandler" ref="infiniteLoading">
            <span slot="no-more">
            </span>
        </infinite-loading>
    </div>
</template>

<script>
import { mapState } from 'vuex'
import { EventBus } from '../event-bus/event-bus';
import MovieService from '../services/MovieService'
import CategoryService from '../services/CategoryService'
import InfiniteLoading from 'vue-infinite-loading';
import Nprogress from 'nprogress'
import 'nprogress/nprogress.css'

export default {
    data () {
        return {
            image_path: APP_URL + '/vue-laravel/public/files/',
            movies: [],
            movie: {
                id: '',
                category_id: '',
                title: '',
                year: '',
                youtubeId: ''
            },
            categories: [],
            file: '',
            preview_image: '',
            edit: false
        };
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
            console.log(filter);
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
            Nprogress.start();
            let formData = new FormData();
            formData.append('title', this.movie.title);
            formData.append('category_id', this.movie.category_id);
            formData.append('year', this.movie.year);
            formData.append('youtubeId', this.movie.youtubeId);
            formData.append('file', this.file);

            if(!this.edit) {
                // Insert movie

                MovieService.post(formData)
                    .then((response) => {
                        const movie = (response.data).data
                        Nprogress.done();
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
            $('html, body').animate({scrollTop : 0},500);

            this.movie.id = movie.id;
            this.movie.category_id = movie.category_id;
            this.movie.title = movie.title;
            this.movie.year = movie.year;
            this.movie.youtubeId = movie.youtubeId;
            this.preview_image = this.image_path + movie.image;
            this.edit = true;
        },
        deleteMovie(id) {
            Nprogress.start();
            if(confirm('Are you sure you want to delete this?')) {
                MovieService.delete(id)
                    .then((response) => {
                        const movie = (response.data).data
                        Nprogress.done();
                    });
            }
        },
        resetForm() {
            this.movie.id = '';
            this.movie.title = '';
            this.movie.year = '';
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
        }
    },

    async mounted () {
        Echo.channel('movie-channel')
            .listen('MovieEvent', (movie) => {
                console.log(movie);
                this.updateList(movie);
                if (this.user) {
                    this.resetForm();
                }
                this.$notify({type: movie.type, title: movie.message});
            });

        EventBus.$on('filter-changed', () => {
            this.resetInfiniteLoading();
        });

        this.categories = ((await CategoryService.get()).data).data
    }
}
</script>

<style>

.vue-notification {
  padding: 15px;
  margin: 0 5px 5px;

  font-size: 16px;

  color: #ffffff;
  background: #44A4FC;
  border-left: 5px solid #187FE7;

  &.warn {
    background: #ffb648;
    border-left-color: #f48a06;
  }

  &.error {
    background: #E54D42;
    border-left-color: #B82E24;
  }

  &.success {
    background: #68CD86;
    border-left-color: #42A85F;
  }
}

.content {
    padding-top: 90px;
}
</style>
