<template>
    <div>
        <h1 class="text-center mb-3">Список зарегистрированых пользователей</h1>
        <table class="table table-bordered text-center">
            <thead>
                <th>#</th>
                <th>Nickname</th>
                <th>E-mail</th>
                <th>Вход под пользователем</th>
            </thead>
            <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                    <td>{{ index }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <a :href="`/api/admin/enterAsUser/${user.id}`"> Войти </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <button 
            :disabled="currentPage == 1"
            class="btn btn-link"
            @click="getUsers(currentPage - 1)"
            >
                назад
        </button>
        <button 
            v-for="(link,idx) in links" 
            :key="idx" 
            class="btn btn-link"
            :class="getCurrentPageClass(link.label)"
            @click="getUsers(link.label)"
            :disabled="link.label == currentPage"
            >
                {{ link.label }}
        </button>
        <button 
            class="btn btn-link"
            @click="getUsers(currentPage + 1)"
            :disabled="currentPage == links.length"
            >
               вперед
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
           usersPerPage:[3, 5, 10, 50],
           length:[],
           users:[],
           links:[],
           currentPage: null,
        }
    },
    computed: {
       
    },
    methods: {
        getUsers (page = 1) {
            if (page == this.currentPage) {
                return false
            }
            const newLink = `/admin/showRegUsers?page=${page}`
            if(this.$route.fullPath != newLink) { 
                this.$router.push(newLink)
            }
            const params = {
                page
            }
            axios
            .get('/api/admin/showRegUsers', {params})
                .then((response) => {
                this.users = response.data.data
                const links = response.data.links.splice(1,response.data.links.length-2)
                this.links = links
                this.currentPage = response.data.current_page
                })
        },
        getCurrentPageClass(page) {
            return page == this.currentPage ? 'current-page' : ''
        }
    },
    mounted () {
        const page = this.$route.query.page
        this.getUsers(page)
    }
}
</script>

<style scoped>
a {
    text-decoration: none;
    color: rgb(27, 119, 39);
}
.current-page {
    color:rgb(42, 104, 42);
     text-decoration: none;
     background-color:rgba(165, 135, 135, 0.582)
}
</style>
