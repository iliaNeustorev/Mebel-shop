<template>
    <div>
        <div class="d-grid gap-2 d-md-block mb-3 text-center">
            <router-link
                to="/admin/categoriesAdmin"
                class="btn btn-success btn-lg mb-2"
            >
                Посмотреть список категорий
            </router-link>
            <br />
            <router-link
                to="/admin/ProductsAdmin"
                class="btn btn-warning btn-lg mb-2"
            >
                Посмотреть список продуктов
            </router-link>
            <br />
            <router-link
                to="/admin/showRegUsers"
                class="btn btn-info btn-lg mb-2"
            >
                Посмотреть список пользователей
            </router-link>
        </div>
    </div>
</template>

<script>
export default {}
</script>

<style>
.loader {
    width: 30%;
    height: 30%;
}
.avatar {
    height: 200px;
    width: 200px;
}
.slide-enter {
    opacity: 0;
}

.slide-enter-active {
    transition: opacity 0.5s;
}

.slide-leave-active {
    transition: opacity 0.5s;
    opacity: 0;
}
a {
    text-decoration: none;
    color: #526628;
}
</style>
