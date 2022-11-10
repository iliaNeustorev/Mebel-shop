export default {
    data() {
        return {
            checkFile: false,
        }
    },
    computed: {
        validationForm() {
            return (
                Object.values(this.validation).some((val) => val) ||
                this.checkFile
            )
        },
    },
    methods: {
        changedPicture(event) {
            this.checkFile = event.checkFile
        },
        fieldValid(elem) {
            this.validation[elem.name] = elem.res
        },
    },
}
