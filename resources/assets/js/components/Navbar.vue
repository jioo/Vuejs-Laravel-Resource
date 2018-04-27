<template lang="html">
    <div>
        <nav class="navbar fixed-top navbar-expand-sm navbar-dark bg-info mb-2">
            <div class="container-fluid">
                <router-link to="/" class="navbar-brand">Articles</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <!-- <li class="nav-item">
                            <router-link to="/" active-class="active" class="nav-link">Categories</router-link>
                        </li> -->

                        <li class="nav-item" v-if="!user">
                            <router-link to="/login" active-class="active" class="nav-link">Login</router-link>
                        </li>

                        <li class="nav-item" v-if="!user">
                            <router-link to="/register" active-class="active" class="nav-link">Register</router-link>
                        </li>

                        <li class="nav-item" v-if="user">
                            <a href="#" class="nav-link" @click="logout">Logout</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0" @submit.prevent="search">
                        <select class="custom-select mr-2" >
                            <option value="0" selected>All Categories</option>
                            <option v-for="category in categories" v-bind:key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                        <input class="form-control mr-sm-2 " type="search" placeholder="Search here.." v-model="newFilter.search" >
                        <button class="btn btn-success my-2 my-sm-0 mr-2" type="submit">Search</button>
                        <button class="btn btn-warning my-2 my-sm-0" type="button" @click="reset">Reset</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import CategoryService from '../services/CategoryService'
import { mapState, mapActions } from 'vuex'
import { EventBus } from '../event-bus/event-bus';

export default {
    data () {
        return {
            categories: [],
            newFilter: {
                search: null,
                category: null
            }
        }
    },

    computed: {
        ...mapState([
            'user',
            'filter'
        ])
    },

    methods: {
        ...mapActions([
            'logout',
            'addFilter',
            'resetFilter'
        ]),
        search () {
            this.addFilter(this.newFilter);
            EventBus.$emit('filter-changed');
        },
        reset () {
            this.resetFilter();
            EventBus.$emit('filter-changed');
        }
    },

    async mounted () {
        this.categories = ((await CategoryService.get()).data).data
        this.newFilter = this.filter
    }
}
</script>

<style lang="css">
</style>
