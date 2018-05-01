import Api from './Api'

export default {
    get (filter) {
        return Api().get('movies', {
            params: {
                page: filter.page,
                search: filter.search,
                category: filter.category
            }
        })
    },
    show (id) {
        return Api().get(`movie/${id}`)
    },
    post (movie) {
        return Api().post('movie', movie, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    put (movie) {
        return Api().put('movie', movie, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    delete (id) {
        return Api().delete(`movie/${id}`)
    }
}
