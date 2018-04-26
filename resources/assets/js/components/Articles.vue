<template lang="html">
    <div class="" >

        <h2>List of Articles</h2>
        <form id="form" @submit.prevent="storeArticle" class="mb-2">
            <div class="form-group">
                <input type="text" class="form-control mb-2" placeholder="Title" v-model="article.title" required>
                <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea required>
                <input type="file" id="file" ref="file" @change="previewImage"/>
                <div class="row">
                    <div class="col-md-6 image-preview" v-if="preview_image.length > 0">
                        <img class="img-fluid" :src="preview_image" style="max-height:200px;">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-light btn-block">Save</button>
        </form>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]">
                    <a class="page-link" href="#" @click="getArticles(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item">
                    <a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
                </li>
                <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
                    <a class="page-link" href="#" @click="getArticles(pagination.next_page_url)">Next</a>
                </li>
            </ul>
        </nav>
        <div class="card card-body mb-2" v-for="article in articles" v-bind:key="articles.id">
            <h3>{{ article.title }}</h3>
            <p>{{ article.body }}</p>
            <img class="img-fluid" v-bind:src="image_path + article.image" style="max-height: 400px;">
            <hr>
            <button type="button" name="button" class="btn btn-warning mb-2" @click="editArticle(article)">Edit</button>
            <button type="button" name="button" class="btn btn-danger" @click="deleteArticle(article.id)">Delete</button>
        </div>

    </div>
</template>

<script>
import ArticlesService from '../services/ArticlesService'
import Nprogress from 'nprogress'
import 'nprogress/nprogress.css'

export default {
    data () {
        return {
            preview_image: '',
            file: '',
            image_path: APP_URL + '/vue-laravel/public/files/',
            articles: [],
            article: {
                id: '',
                title: '',
                body: ''
            },
            pagination: {},
            edit: false
        };
    },

    methods: {
        async getArticles(url) {
            let response = (await ArticlesService.get(url)).data;
            this.articles = (response).data;
            this.makePagination(await response.links, await response.meta);
            this.resetForm();
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
        storeArticle() {
            Nprogress.start();
            let formData = new FormData();
            formData.append('title', this.article.title);
            formData.append('body', this.article.body);
            formData.append('file', this.file);

            if(!this.edit) {
                // Insert article
                ArticlesService.post(formData)
                    .then((response) => {
                        const article = (response.data).data
                        // this.$notify({type: 'success', title: "Article: " + article.title + " has been added"});
                        // this.getArticles();
                        Nprogress.done();
                    });
            } else {
                // Update Article
                formData.append('id', this.article.id);

                // Laravel BUG: Input from PUT requests sent as multipart/form-data:
                // solution: You should send POST and set _method to
                // PUT (same as sending forms) to make your files visible
                // https://github.com/laravel/framework/issues/13457
                formData.append('_method', 'PUT');

                ArticlesService.post(formData)
                    .then((response) => {
                        const article = (response.data).data
                        // this.$notify({type: 'success', title: "Article: " + article.title + " has been updated"});
                        // this.getArticles();
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
        editArticle(article) {
            $('html, body').animate({scrollTop : 0},500);

            this.article.id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
            this.preview_image = this.image_path + article.image;
            this.edit = true;
        },
        deleteArticle(id) {
            Nprogress.start();
            if(confirm('Are you sure you want to delete this?')) {
                ArticlesService.delete(id)
                    .then((response) => {
                        const article = (response.data).data
                        // this.$notify({type: 'error', title: "Article: " + article.title + " has been removed"});
                        // this.getArticles();
                        Nprogress.done();
                    });
            }
        },
        resetForm() {
            this.article.id = '';
            this.article.title = '';
            this.article.body = '';
            this.file = '';
            this.preview_image = '';
            this.$refs.file.value = null;
            this.edit = false;
        }
    },

    created() {
        Nprogress.start()
    },

    mounted () {
        this.getArticles();
        Nprogress.done();

        Echo.channel('article-channel')
            .listen('ArticleEvent', (article) => {
                this.getArticles();
                this.$notify({type: article.type, title: article.message});
            });
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
</style>
