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
                        <a :href="`/admin/enterAsUser/${user.id}`"> Войти </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["users"],
}
</script>

<style scoped>
a {
    text-decoration: none;
    color: rgb(27, 119, 39);
}
</style>
