<template lang="html">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <div class="card-text">
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label for="email" class="control-label">E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" v-model="email" required autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password" class="control-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" v-model="password" required>
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
export default {
    data () {
        return {
            email: '',
            password: ''
        }
    },

    methods: {
        login () {
            let test = {
                username: this.email,
                password: this.password,
                client_id: "2",
                client_secret: "aae8JmaGT1FqoWAsxvbDfnciOI0hkIOfxhv9IBM3",
                grant_type: "password"
            }
        }

    }
}
</script>

<style lang="css">
</style>
