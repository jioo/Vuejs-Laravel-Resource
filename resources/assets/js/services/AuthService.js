import Api from './Api'

export default {
    login(user) {
        user.client_id = '2';
        user.client_secret = 'plI9LQnfqb6dlFmmfraKgt3iHrBzlJtjyDMshwQo';
        user.grant_type = "password";
        return Api().post('oauth/token', user)
    }
}
