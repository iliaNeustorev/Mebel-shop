<template>
    <div class="mb-3">
        <label class="form-label" :for="id"> {{ label }} </label>
        <textarea
            required
            :id="id"
            class="form-control w-50"
            :class="inputClasses"
            :name="name"
            :rows="row"
            v-model.trim="form[name]"
            @change="valid()"
            :placeholder="placeholder"
        ></textarea>
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
        row: { type: Number, default: 3 },
    },
}
</script>
