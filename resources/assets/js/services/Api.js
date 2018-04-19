import axios from 'axios'

export default () => {
    return axios.create({
        baseURL: 'http://localhost/vue-laravel/public/api/'
    })
}
