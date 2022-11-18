<template>
    <div class="text-center" v-if="loading">
        <loading-component />
    </div>
    <div v-else>
        <search-component class="w-50" @search="search" />
        Результат поиска:
        <ul v-if="result.length > 0">
            <li v-for="elem in result" :key="elem.id">
                <router-link
                    :to="{
                        name: 'ShowProduct',
                        params: { id: elem.id, name: elem.name },
                    }"
                    ><h2>{{ elem.name }}</h2></router-link
                >
                <div>
                    <p>{{ elem.description }}</p>
                    <p>{{ elem.price }}</p>
                </div>
            </li>
        </ul>
        <span v-else>Ничего не найдено</span>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            result: [],
        }
    },
    mounted() {
        if (this.$route.params.start == true) {
            this.search()
            this.$route.params.start = false
            this.$route.params.fieldSearch = null
        }
        Echo.channel("general").listen(`.search`, (e) => {
            this.result = e
            this.loading = false
        })
    },
    destroyed() {
        Echo.channel("general").stopListening(`.search`)
    },
    methods: {
        search(field) {
            this.loading = true
            axios.get("/api/search", {
                params: {
                    search: field ?? this.$route.params.fieldSearch,
                },
            })
        },
    },
}
</script>
