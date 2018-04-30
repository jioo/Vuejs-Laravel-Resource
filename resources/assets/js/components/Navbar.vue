<template lang="html">
    <div>
        <v-navigation-drawer
        fixed
        clipped
        v-model="drawer"
        app
        >
            <v-list dense>
                <v-subheader class="mt-3 grey--text text--darken-1">ADMIN NAVIGATION</v-subheader>
                <v-list-tile dark @click.stop="dialog = true" v-if="!user">
                    <v-list-tile-action>
                        <v-icon>supervisor_account</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >
                            Login
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile dark @click="addMovie" v-if="user">
                    <v-list-tile-action>
                        <v-icon>movie</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >
                            Add Movie
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile dark @click="moveTo('/genre')" v-if="user">
                    <v-list-tile-action>
                        <v-icon>list</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >
                            Genres
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile dark @click="logout" v-if="user">
                    <v-list-tile-action>
                        <v-icon>exit_to_app</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title >
                            Logout
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-subheader class="mt-3 grey--text text--darken-1">GENRES</v-subheader>
                <v-list-tile
                    v-for="category in categories"
                    v-bind:key="category.value"
                    v-model="category.active"
                    @click="categoryChange(category)">
                    <v-list-tile-content>
                        <v-list-tile-title>
                            {{ category.text }}
                        </v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

            </v-list>
        </v-navigation-drawer>
        <v-toolbar
        color="blue"
        dense
        fixed
        clipped-left
        app
        >
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
            <v-toolbar-title class="mr-5 align-center">
                <span class="title">Pelikula</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-layout row align-center style="max-width: 350px">
                <v-text-field
                placeholder="Search..."
                single-line
                append-icon="search"
                clearable
                color="white"
                v-model="newFilter.search"
                @input="search"
                hide-details
                ></v-text-field>
            </v-layout>
        </v-toolbar>

        <v-dialog dark v-model="dialog" persistent max-width="500px">
            <v-card>
                <v-card-title>
                    LOGIN PANEL
                    <v-spacer></v-spacer>
                    <v-btn icon @click.native="dialog = false" dark>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-divider></v-divider>
                <v-card-text>
                    <v-form v-model="valid" ref="form">
                        <v-text-field
                        type="text"
                        label="Username"
                        v-model="form.username"
                        required
                        :rules="[required]"
                        ></v-text-field>

                        <v-text-field
                        type="password"
                        label="Password"
                        v-model="form.password"
                        required
                        :rules="[required]"
                        ></v-text-field>

                        <v-btn color="success" @click="loginUser">
                            Login
                        </v-btn>
                    </v-form>
                </v-card-text>
            </v-card>
      </v-dialog>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { EventBus } from '../event-bus/event-bus';
import CategoryService from '../services/CategoryService'
import AuthService from '../services/AuthService'

export default {
    data () {
        return {
            test1: false,
            test2: false,
            drawer: true,
            valid: false,
            dialog: false,
            form: {
                username: '',
                password: ''
            },
            categories: [],
            newFilter: {
                search: '',
                category: 0
            },
            required: (value) => !!value || 'This field is required.'
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
            'login',
            'logout',
            'addFilter',
            'resetFilter'
        ]),
        async getCategories () {
            let categories = ((await CategoryService.get()).data).data

            // Clone array & Create new key/value pair
            this.categories = categories.map((element) => {
                const obj = Object.assign({}, element);
                obj.active = false;
                return obj;
            });
        },
        loginUser () {
            if (this.$refs.form.validate()) {
                this.login(this.form).then((res) => {
                    this.dialog = false;
                });
            }
        },
        addMovie() {
            this.$router.push('/');
            EventBus.$emit('add-movie');
        },
        search () {
            this.addFilter(this.newFilter);
            EventBus.$emit('filter-changed');
        },
        reset () {
            this.resetFilter();
            EventBus.$emit('filter-changed');
        },
        categoryChange (category) {
            // Set other category.active to false if user
            // selects another category
            if (!category.active) {
                this.categories = this.categories.map((element) => {
                    element.active = false;
                    return element
                });
            }

            // Toggle category.active
            category.active = (category.active) ? false: true;

            // Find the selected category then update the category list
            let index = this.categories.findIndex(x => x.value == category.value);
            this.categories.splice(index, 1, category);

            // Trigger the search filter
            this.newFilter.category = (category.active) ? category.value: '0';
            this.addFilter(this.newFilter);
            EventBus.$emit('filter-changed');
            this.$router.push('/')
        },
        moveTo(url) {
            this.$router.push(url);
        }
    },

    async mounted () {
        this.getCategories();
        this.newFilter = this.filter;

        EventBus.$on('category-updated', () => {
            this.getCategories();
        });
    },

    watch: {
        dialog: function(val) {
            if(!val) {
                this.$refs.form.reset()
            }
        }
    }

}
</script>

<style lang="css">
</style>
