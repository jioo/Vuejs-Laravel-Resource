import Api from './Api'

export default {
    login(user) {
        user.client_id = '2';
        user.client_secret = 'ZndQMaRlWP8gCyTXvNlNfo5XacBP1jmnKHN0sFq0';
        user.grant_type = "password";
        return Api().post('oauth/token', user)

        // return new Promise(function(resolve, reject) {
        //     Api().post('oauth/token', user).then(() => {
        //         return resolve()
        //     }).catch((err) => {
        //         return reject()
        //     })
        // });
    }
}
