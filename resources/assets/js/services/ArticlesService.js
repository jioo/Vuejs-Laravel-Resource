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
        return Api().post('article', article, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    put (article) {
        return Api().put('article', article, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    delete (id) {
        return Api().delete(`article/${id}`)
    }
}
