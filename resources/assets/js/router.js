import VueRouter from 'vue-router'

export default new VueRouter({
    routes: [
        { path: '/', component: require('./components/Movies') },
        { path: '/genre', component: require('./components/Genre') }
    ]
})
