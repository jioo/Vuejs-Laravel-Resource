import Api from './Api'

export default {
    login(user) {
        user.client_id = '2';
        user.client_secret = 'wMwOgkto1bMRrYfg1B1OrMqS6k8olx1eS6i3sDEe';
        user.grant_type = "password";
        return Api().post('oauth/token', user)
    }
}
