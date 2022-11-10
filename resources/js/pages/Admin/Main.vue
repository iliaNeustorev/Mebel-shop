<template>
    <div>
        <div class="d-grid gap-2 d-md-block text-center">
            <router-link
                :to="{ name: 'AdminCategories' }"
                class="btn btn-secondary btn-lg mb-2"
            >
                Посмотреть список категорий
            </router-link>
            <br />
            <router-link
                :to="{ name: 'AdminProducts' }"
                class="btn btn-warning btn-lg mb-2"
            >
                Посмотреть список продуктов
            </router-link>
            <br />
            <router-link
                :to="{ name: 'ShowRegUsers' }"
                class="btn btn-info btn-lg mb-2"
            >
                Посмотреть список пользователей
            </router-link>
        </div>
        <div class="text-center">
            <button-back class="btn-success btn-lg" />
        </div>
    </div>
</template>

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
