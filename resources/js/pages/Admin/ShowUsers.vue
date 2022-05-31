<template>
    <div>
        <h1 class="text-center mb-3">Список зарегистрированых пользователей</h1>
        <table
            class="table table-bordered text-center"
            :class="{ updating: updating }"
        >
            <thead>
                <tr>
                    <th class="sort" @click="sort('id')">
                        id <span v-html="getSortArrow('id')"></span>
                    </th>
                    <th class="sort" @click="sort('name')">
                        nickname <span v-html="getSortArrow('name')"></span>
                    </th>
                    <th class="sort" @click="sort('email')">
                        email <span v-html="getSortArrow('email')"></span>
                    </th>
                    <th>Вход под пользователем</th>
                </tr>
                <th>
                    <input
                        v-model="filters.id.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder="поиск по id"
                    />
                </th>
                <th>
                    <input
                        v-model="filters.name.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder=" поиск по Nickname"
                    />
                </th>
                <th>
                    <input
                        v-model="filters.email.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder="поиск по E-mail"
                    />
                </th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <a :href="`/api/admin/enterAsUser/${user.id}`">
                            Войти
                        </a>
                    </td>
                </tr>
                <tr>
                    <td
                        v-if="!users.length"
                        colspan="4"
                        class="text-muted text-center"
                    >
                        {{ textLoad }}
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
            v-for="(link, idx) in links"
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
        <select
            v-model="length"
            @change="getUsers()"
            class="form-control userPerPage"
        >
            <option v-for="(length, idx) in usersPerPage" :key="idx">
                {{ length }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    data() {
        return {
            usersPerPage: [2, 3, 10, 50],
            length: 3,
            users: [],
            links: [],
            currentPage: null,
            updating: false,
            filters: {
                id: {
                    value: null,
                    type: "=",
                },
                name: {
                    value: null,
                    type: "like",
                },
                email: {
                    value: null,
                    type: "like",
                },
            },
            delay: null,
            sortColumn: {
                column: "id",
                direction: "asc",
            },
            currentDirection: "asc",
            textLoad: "Загрузка",
        }
    },
    computed: {},
    methods: {
        getSortArrow(column) {
            if (column != this.sortColumn.column) {
                return "&udarr;"
            } else {
                return this.currentDirection == "asc" ? "&#8639;" : " &#8642;"
            }
        },
        sort(column) {
            if (column == this.sortColumn.column) {
                this.currentDirection =
                    this.currentDirection == "asc" ? "desc" : "asc"
                this.sortColumn.direction = this.currentDirection
            } else {
                this.currentDirection = "asc"
                this.sortColumn.direction = this.currentDirection
                this.sortColumn.column = column
            }
            this.getUsers()
        },
        getUsers(page = 1) {
            this.updating = true
            // if (page == this.currentPage) {
            //     return false
            // }
            const newLink = `/admin/showRegUsers?page=${page}`
            if (this.$route.fullPath != newLink) {
                this.$router.push(newLink)
            }
            const params = {
                page,
                length: this.length,
                filters: this.filters,
                sortColumn: this.sortColumn,
            }
            axios
                .post("/api/admin/showRegUsers", params)
                .then((response) => {
                    this.users = response.data.data
                    const links = response.data.links.splice(
                        1,
                        response.data.links.length - 2
                    )
                    this.links = links
                    this.currentPage = response.data.current_page
                })
                .finally(() => {
                    this.updating = false
                    this.textLoad = this.users == "" ? "Ничего не найдено" : ""
                })
        },
        getCurrentPageClass(page) {
            return page == this.currentPage ? "current-page" : ""
        },
        filter() {
            clearTimeout(this.delay)
            this.delay = setTimeout(() => {
                this.getUsers()
            }, 1000)
        },
    },
    mounted() {
        const page = this.$route.query.page
        this.getUsers(page)
    },
}
</script>

<style scoped>
a {
    text-decoration: none;
    color: rgb(27, 119, 39);
}
.current-page {
    color: rgb(42, 104, 42);
    text-decoration: none;
    background-color: rgba(165, 135, 135, 0.582);
}
.userPerPage {
    width: 50px;
    float: right;
    display: inline;
}

table.updating tbody {
    position: relative;
    color: rgba(0, 0, 0, 0.404);
}

table.updating a {
    color: rgba(0, 0, 0, 0.404);
}

table.updating tbody:after {
    content: "";
    width: 100%;
    height: 100%;
    top: 0;
    background-color: rgba(2, 2, 2, 0.05);
}
.sort {
    cursor: pointer;
}
</style>
