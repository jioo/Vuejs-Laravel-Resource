<template lang="html">
    <div class="">
        <h2>List of Articles</h2>
        <form @submit.prevent="storeArticle" class="mb-2">
            <div class="form-group">
                <input type="text" class="form-control mb-2" placeholder="Title" v-model="article.title">
                <textarea class="form-control" placeholder="Body" v-model="article.body"></textarea>
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
            <hr>
            <button type="button" name="button" class="btn btn-warning mb-2" @click="editArticle(article)">Edit</button>
            <button type="button" name="button" class="btn btn-danger" @click="deleteArticle(article.id)">Delete</button>
        </div>
    </div>
</template>

<script>
import ArticlesService from '../services/ArticlesService'

export default {
    data () {
        return {
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
            if(this.edit) {
                // Update Article
                ArticlesService.put(this.article)
                    .then((response) => {
                        alert('An article has been updated');

                        this.getArticles();
                        this.resetForm();
                    });
            } else {
                // Insert article
                ArticlesService.post(this.article)
                    .then((response) => {
                        alert('New article has been added');

                        this.getArticles();
                        this.resetForm();
                    });
            }
        },
        editArticle(article) {
            this.article.id = article.id;
            this.article.title = article.title;
            this.article.body = article.body;
            this.edit = true;
        },
        deleteArticle(id) {
            if(confirm('Are you sure you want to delete this?')) {
                ArticlesService.delete(id)
                    .then((response) => {
                        alert('Article has been removed');
                        this.getArticles();
                    });
            }
        },
        resetForm() {
            this.article.id = '';
            this.article.title = '';
            this.article.body = '';
            this.edit = false;
        }
    },

    mounted () {
        this.getArticles();
    }
}
</script>

<style lang="css">
</style>
