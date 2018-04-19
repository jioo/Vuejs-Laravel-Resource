import Api from './Api'

export default {
    get (url) {
        url = url || 'articles';
        return Api().get(url)
    },
    show (id) {
        return Api().get(`article/${id}`)
    },
    post (article) {
        return Api().post('article', article)
    },
    put (article) {
        return Api().put(`article`, article)
    },
    delete (id) {
        return Api().delete(`article/${id}`)
    }
}
