import Api from './Api'

export default {
    login(user) {
        user.client_id = '2';
        user.client_secret = 'Yg0n3WGyiCQuYvYEGlj7ARvOQUJNTB7rh1F8Bel8';
        user.grant_type = "password";
        return Api().post('oauth/token', user)
    }
}
