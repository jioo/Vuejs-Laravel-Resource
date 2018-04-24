import Api from './Api'

export default {
    login(user) {
        user.client_id = '2';
        user.client_secret = 'X1FvyCRde5NZNGIy0bFritUR11C9udeDcMWOZcCc';
        user.grant_type = "password";
        return Api().post('oauth/token', user)
    }
}
