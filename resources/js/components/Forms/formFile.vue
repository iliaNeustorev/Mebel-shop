<template>
    <div class="mb-3">
        <label class="form-label" :for="id"> {{ label }} </label>
        <div class="input-group mb-3 w-50">
            <input
                :id="id"
                type="file"
                ref="firstInp"
                class="form-control"
                :class="inputClasses"
                :name="name"
                @change="download()"
            />
            <button
                @click.prevent="clearPicture()"
                class="btn btn-danger"
                type="button"
                id="button-addon2"
                title="очистить картинку"
            >
                X
            </button>
            <div v-if="hasError" class="invalid-feedback">
                {{ errorText }}
            </div>
        </div>
    </div>
</template>

<script>
import baseInput from "../../mixins/base-input.js"
export default {
    mixins: [baseInput],
    props: { checkFile: { type: Boolean, required: false } },
    methods: {
        download() {
            let file = this.$refs.firstInp.files[0]
            this.form[this.name] = file
            this.check(this.form[this.name])
        },
        clearPicture() {
            this.form[this.name] = null
            this.$refs.firstInp.value = null
            this.check(this.form[this.name])
        },
        check(element) {
            let result
            result = element != null ? true : false
            this.$emit("changeCheckFile", { checkFile: result })
        },
    },
}
</script>

<style></style>
