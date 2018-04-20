<template lang="html">
    <div>
        <nav class="navbar navbar-expand-sm navbar-dark bg-info mb-2">
            <div class="container">
                <router-link to="/" class="navbar-brand">Articles</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <router-link to="/login" active-class="active" class="nav-link">Login</router-link>
                        </li>

                        <li class="nav-item">
                            <router-link to="/register" active-class="active" class="nav-link">Register</router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
export default {
}
</script>

<style lang="css">
</style>
