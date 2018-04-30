<template lang="html">
    <v-content>
        <v-container grid-list-md>
            <v-flex xs6 offset-xs3>
                <v-dialog
                  v-model="dialog"
                  persistent
                  max-width="500px"
                  transition="dialog-transition"
                >
                    <v-card>
                        <v-card-title>
                            GENRE PANEL
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
                                label="Genre"
                                v-model="genre.name"
                                required
                                :rules="[required]"
                                ></v-text-field>

                                <v-btn color="success" @click="storeItem">
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
                            <v-btn color="error" @click="deleteItem">
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

                <v-btn color="primary" dark @click.stop="dialog = true" class="mb-2">Add Genre</v-btn>
                <v-data-table
                :headers="headers"
                :items="items"
                hide-actions
                class="elevation-1"
                >
                    <template slot="items" slot-scope="props">
                        <td class="">{{ props.item.text }}</td>
                        <td class="justify-center">
                            <v-btn icon class="mx-0" @click="editItem(props.item)">
                                <v-icon color="teal">edit</v-icon>
                            </v-btn>
                            <v-btn icon class="mx-0" @click="confirmDelete(props.item.value)">
                                <v-icon color="pink">delete</v-icon>
                            </v-btn>
                        </td>
                    </template>
                </v-data-table>
            </v-flex>
        </v-container>
    </v-content>
</template>

<script>
import { EventBus } from '../event-bus/event-bus';
import CategoryService from '../services/CategoryService'
import Nprogress from 'nprogress'
import 'nprogress/nprogress.css'

export default {
    data () {
        return {
            dialog: false,
            confirmDialog: false,
            valid: false,
            edit: false,
            items: [],
            headers: [
                { text: 'Genre', sortable: false },
                { text: 'Action', sortable: false, width: '150' }
            ],
            genre: {
                id: '',
                name: ''
            },
            required: (value) => !!value || 'This field is required.',
        }
    },

    methods: {
        storeItem() {
            if(!this.$refs.form.validate()) {
                return false;
            }

            Nprogress.start();
            if(!this.edit) {
                // Insert genre
                CategoryService.post(this.genre)
                    .then((response) => {
                        const genre = (response.data).data
                        this.updateList(genre, 'create')
                    });
            } else {
                // Update genre
                CategoryService.put(this.genre)
                    .then((response) => {
                        const genre = (response.data).data
                        this.updateList(genre, 'update')
                    });
            }
        },
        editItem(genre) {
            this.dialog = true;
            this.edit = true;

            this.genre.id = genre.value;
            this.genre.name = genre.text;
        },
        confirmDelete(id) {
            console.log(id);
            this.genre.id = id
            this.confirmDialog = true;
        },
        deleteItem() {
            Nprogress.start();
            CategoryService.delete(this.genre.id)
                .then((response) => {
                    this.genre.id = '';
                    const genre = (response.data).data
                    this.updateList(genre, 'delete')
                });
        },
        updateList(genre, action) {
            this.confirmDialog = false;
            this.dialog = false;
            Nprogress.done();
            EventBus.$emit('category-updated');
            let index = this.items.findIndex(x => x.value == genre.id);
            let new_genre = {
                value: genre.value,
                text: genre.text
            }

            switch(action) {
                case 'create':
                    this.items.push(new_genre);
                    this.$notify({type: 'success', title: 'You have successfully added new Genre'});
                    break;

                case 'update':
                    this.items.splice(index, 1, new_genre);
                    this.$notify({type: 'success', title: 'You have successfully updated a Genre'});
                    break;

                case 'delete':
                    this.items.splice(index, 1);
                    this.$notify({type: 'error', title: 'You have successfully deleted a Genre'});
                    break;

                default:
                    break;
            }
        }
    },

    async mounted () {
        this.items = ((await CategoryService.get()).data).data
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
