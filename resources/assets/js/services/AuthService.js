import Api from './Api'

export default {
    login(user) {
        return Api().post('oauth/token', user)
    },
    register (user) {

    }
}
