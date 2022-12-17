<template>
    <div>
        <label v-if="label" class="form-label" :for="id"> {{ label }} </label>
        <input
            :id="id"
            type="checkbox"
            :class="inputClasses"
            :name="name"
            v-model="form[name]"
            @change="valid()"
        />
        <div v-if="hasError" class="invalid-feedback">
            {{ errorText }}
        </div>
    </div>
</template>

<script>
import baseInput from "../../mixins/base-input.js"
export default {
    mixins: [baseInput],
}
</script>
