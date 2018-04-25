import axios from 'axios'
import Nprogress from 'nprogress'

export default () => {
    const instance = axios.create({
        baseURL: 'http://localhost/vue-laravel/public/',
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': '*',
            'Access-Control-AllowHeaders': '*'
        }
    })

    // Authorization header
    instance.interceptors.request.use(function (config) {
        config['headers'] = {
            'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
            // 'Access-Control-Allow-Origin': '*'
            // 'Access-Control-Request-Method': 'GET, PUT, POST, PATCH, DELETE, OPTIONS',
            // 'Access-Control-AllowHeaders': '*',
            // 'X-Requested-With': 'XMLHttpRequest'
        };
        // NProgress.start();
        // console.log('starting..')
        return config
    }, error => Promise.reject(error))

    // Show toast with message for non OK responses
    instance.interceptors.response.use(response => {
        // console.log('done..')

        // console.log(response.request.headers);
        return response
    }, error => {
      // store.dispatch('addToastMessage', {
      //   text: error.response.data.message || 'Request error status: ' + error.response.status,
      //   type: 'danger'
      // })
      return Promise.reject(error)
    })

    return instance
}
