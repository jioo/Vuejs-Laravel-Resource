import Api from './Api'

export default {
    get () {
        return Api().get('api/category')
    }
}
