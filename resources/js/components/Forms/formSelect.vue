<template>
    <div class="gx-3 gy-2 mb-3 row align-items-center">
        <div class="col-sm-3">
            <label :for="id" class="form-label"> {{ label }} </label>
            <select
                :disabled="form[name] != '' && !changeCategoryFlag"
                class="form-select"
                :class="inputClasses"
                :id="id"
                :name="name"
                v-model="form[name]"
                @change="valid()"
            >
                <option value="" disabled>---Не выбрано---</option>
                <option
                    v-for="(text, value) in options"
                    :key="value"
                    :value="value"
                >
                    {{ text }}
                </option>
            </select>
        </div>
        <div class="col-auto mt-5">
            <div class="form-check">
                <input
                    class="form-check-input"
                    id="checkbox"
                    type="checkbox"
                    v-model="changeCategoryFlag"
                />
                <label class="form-check-label" for="checkbox"
                    >Изменить категорию</label
                >
            </div>
        </div>
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
        options: { type: Object, required: true },
    },
    data() {
        return { changeCategoryFlag: false }
    },
}
</script>

<style></style>
