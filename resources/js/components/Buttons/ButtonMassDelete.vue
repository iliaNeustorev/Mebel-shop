<template>
    <div>
        <button-send-form
            :validation-form="validationForm"
            name-button-accepted="Удалить выбраное"
            name-button-denied="Выберите необходимое для удаления"
            class-button-denied="btn-warning "
            class-button-accepted="btn-danger position-fixed top-50 start-0"
            @acceptedForm="confirmDelete()"
            ><template v-if="countItems" v-slot:textModalBtn
                ><span>({{ countItems }})</span></template
            >

            <template v-slot:mainModal
                ><p class="container">
                    Удалить в количестве <span>{{ countItems }}</span>
                </p>
            </template>
        </button-send-form>
        <button
            v-if="countItems"
            @click="$emit('clearDeleted')"
            class="btn btn-warning position-fixed bottom-0 start-0"
        >
            Очистить выбраное
        </button>
    </div>
</template>

<script>
export default {
    props: {
        validationForm: { type: Boolean, required: true },
        countItems: { type: Number, required: false },
    },
    methods: {
        confirmDelete() {
            this.$emit("acceptedDelete")
        },
    },
}
</script>

<style scoped>
span {
    color: aqua;
}
</style>
