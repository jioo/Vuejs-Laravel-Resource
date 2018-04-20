<template lang="html">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">Register</div>
                <div class="card-body">
                    <div class="card-text">
                        <form class="form-horizontal" @submit.prevent="register">
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input id="name" type="text" class="form-control" name="name" v-model="name" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="email" class="control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" v-model="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" v-model="password" required>
                            </div>
                            <div class="form-group">
                                <label for="password-confirm" class="control-label">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="confirm_password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Register
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
export default {
    data () {
        return {
            name: '',
            email: '',
            password: '',
            confirm_password: ''
        }
    },

    methods: {
        register () {
            //
        }
    }
}
</script>

<style lang="css">
</style>
