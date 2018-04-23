<template lang="html">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <div class="card-text">
                        <form class="form-horizontal" @submit.prevent="login">
                            <div class="form-group">
                                <label for="email" class="control-label">E-Mail Address</label>
                                <input type="email" class="form-control" v-model="email" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input type="password" class="form-control" v-model="password" required>
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
// import {client_id, client_secret} from './env'

export default {
    data () {
        return {
            email: '',
            password: ''
        }
    },

    methods: {
        login() {
            let credentials = {
                username: this.email,
                password: this.password,
                client_id: '2',
                client_secret: 'X1FvyCRde5NZNGIy0bFritUR11C9udeDcMWOZcCc',
                grant_type: "password"
            }

            AuthService.login(credentials).then((response) => {
                let token = response.data.access_token
                localStorage.setItem('access_token', token)
            })
        }

    }
}
</script>

<style lang="css">
</style>
