import Api from './Api'

export default {
    get () {
        return Api().get('api/category')
    },
    post (category) {
        return Api().post('api/category', category)
    },
    put (category) {
        return Api().put('api/category', category)
    },
    delete (id) {
        return Api().delete(`api/category/${id}`)
    }
}
