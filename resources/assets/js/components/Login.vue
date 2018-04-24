<template lang="html">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <div class="card-text">
                        <form class="form-horizontal" @submit.prevent="onSubmit">
                            <div class="form-group">
                                <label for="email" class="control-label">E-Mail Address</label>
                                <input type="email" class="form-control" v-model="form.username" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" class="form-control" v-model="form.password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthService from '../services/AuthService'
import { mapActions } from 'vuex'

export default {
    data () {
        return {
            form: {
                username: '',
                password: ''
            }
        }
    },

    methods: {
        ...mapActions([
          'login'
        ]),

        onSubmit() {
            this.login(this.form).then(() => {
                this.$router.push('/')
            })
            // AuthService.login(credentials).then((response) => {
            //     let token = response.data.access_token
            //     localStorage.setItem('access_token', token)
            // })
        }
    }
}
</script>

<style lang="css">
</style>
