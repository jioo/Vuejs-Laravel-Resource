import Api from './Api'

export default {
    get (filter) {
        return Api().get('api/movies', {
            params: {
                page: filter.page,
                search: filter.search,
                category: filter.category
            }
        })
    },
    show (id) {
        return Api().get(`api/movie/${id}`)
    },
    post (movie) {
        return Api().post('api/movie', movie, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    put (movie) {
        return Api().put('api/movie', movie, { headers: {'Content-Type': 'multipart/form-data'} })
    },
    delete (id) {
        return Api().delete(`api/movie/${id}`)
    }
}
