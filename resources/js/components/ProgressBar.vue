<template>
    <div class="progress">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            :style="`width:${percent}%`"
            :aria-valuenow="percent"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            {{ percent }}%
        </div>
    </div>
</template>

<script>
export default {
    props: ["channel", "event"],
    data() {
        return {
            percent: 0,
        }
    },
    mounted() {
        Echo.channel(this.channel).listen(`.${this.event}`, (percent) => {
            this.percent = percent
        })
    },
    destroyed() {
        Echo.channel(this.channel).stopListening(`.${this.event}`)
    },
}
</script>

<style></style>
