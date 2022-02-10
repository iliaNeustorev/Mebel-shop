<template>
<div>
   <div class="d-grid gap-2 d-md-block mb-3 text-center">
            <button @click="showCategories()" class="btn btn-success btn-lg  mb-2">Посмотреть список
                    категорий</button>
            <br>
            <button class="btn btn-warning btn-lg  mb-2">Посмотреть список
                    продуктов</button>
            <br>
            <button @click="showUsers()" class="btn btn-info btn-lg  mb-2">Посмотреть список
                    пользователей</button>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        showUsers () { 
            this.$router.push('/admin/showRegUsers')
        },
        showCategories() { 
             this.$router.push('/admin/categoriesAdmin')
        }
    }

}
</script>

<style>

</style>