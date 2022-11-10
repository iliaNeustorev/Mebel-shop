export default {
    computed: {
        validationForm() {
            return Object.values(this.validation).every((val) => val)
        },
    },
    methods: {
        fieldValid(elem) {
            this.validation[elem.name] = elem.res
        },
    },
}
