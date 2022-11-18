export default {
    data() {
        return {
            categories: {},
        }
    },
    mounted() {
        axios.get("/api/categories/shortCategories").then((response) => {
            this.categories = response.data
        })
    },
}
