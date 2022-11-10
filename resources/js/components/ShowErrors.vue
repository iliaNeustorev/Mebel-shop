<template>
    <div class="alert alert-danger">
        <ul>
            <li v-for="(error, idx) in errors" :key="idx">
                {{ error[0] }}
            </li>
            <slot></slot>
        </ul>
    </div>
</template>

<script>
export default {
    props: { errors: { type: Object, required: true } },
}
</script>
