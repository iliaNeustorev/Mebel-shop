import Form from "vform"
export default {
    props: {
        form: { type: Form, required: true },
        name: { type: String, required: true },
        label: { type: String, required: false },
        placeholder: { type: String, required: false },
        oldValue: { required: false },
    },
    computed: {
        id() {
            return `field-${this.name}`
        },
        hasError() {
            return this.form.errors.has(this.name)
        },

        inputClasses() {
            return { "is-invalid": this.hasError }
        },
        errorText() {
            return this.hasError ? this.form.errors.get(this.name) : null
        },
        emptyFiled() {
            return this.form[this.name].length > 0
        },
        checkedValuesField() {
            return this.oldValue !== this.form[this.name]
        },
    },
    methods: {
        valid() {
            let res = this.emptyFiled && this.checkedValuesField
            this.$emit("validation-filed", { res: res, name: this.name })
        },
    },
}
