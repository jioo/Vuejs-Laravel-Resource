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
        user: null
    },

    mutations: {
        login(state, user) {
            state.user = user
        },
        logout(state) {
            state.user = null
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
        }
    }
})
