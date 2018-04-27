import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import AuthService from './services/AuthService'

Vue.use(Vuex)

export default new Vuex.Store({
    plugins: [
        createPersistedState()
    ],

    state: {
        user: null,
        filter: {
            search: '',
            category: ''
        }
    },

    mutations: {
        login(state, user) {
            state.user = user
        },
        logout(state) {
            state.user = null
        },
        addFilter(state, filter) {
            state.filter.search = filter.search
            state.filter.category = filter.category
        },
        resetFilter(state) {
            state.filter.search = ''
            state.filter.category = ''
        }
    },

    actions: {
        login ({commit}, form) {
            AuthService.login(form).then((response) => {
                let token = response.data.access_token
                localStorage.setItem('access_token', token)

                commit('login', response.data)
            })
        },
        logout ({commit}) {
            localStorage.removeItem('access_token')
            commit('logout')
        },
        addFilter ({commit}, filter) {
            commit('addFilter', filter)
        },
        resetFilter ({commit}) {
            commit('resetFilter')
        }
    }
})
