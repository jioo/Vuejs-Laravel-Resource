import Api from './Api'

export default {
    get () {
        return Api().get('category')
    },
    post (category) {
        return Api().post('category', category)
    },
    put (category) {
        return Api().put('category', category)
    },
    delete (id) {
        return Api().delete(`category/${id}`)
    }
}
