<template>
    <div class="mb-2">
        <label v-if="label" class="form-label" :for="id"> {{ label }} </label>
        <input
            :id="id"
            :type="type"
            ref="firstInp"
            class="form-control w-50"
            :class="inputClasses"
            :name="name"
            v-model.trim="form[name]"
            @change="valid()"
            :placeholder="placeholder"
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
    props: {
        type: { type: String, default: "text" },
    },
}
</script>
