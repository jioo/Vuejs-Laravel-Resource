import Api from './Api'

export default {
    login(user) {
        return Api().post('login', user)
    }
}
