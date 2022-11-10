export default {
    data() {
        return {
            categories: {},
        }
    },
    mounted() {
        axios.get("/api/categories/get").then((response) => {
            let m = {}
            for (let elem in response.data) {
                let key = response.data[elem].id
                let value = response.data[elem].name
                m[key] = value
            }
            this.categories = m
        })
    },
}
