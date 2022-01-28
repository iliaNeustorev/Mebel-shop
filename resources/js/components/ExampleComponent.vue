<template>
    <div>
        <h1>Список категорий</h1>
        <button @click="clickCounter" class="btn btn-success">
            CLICK {{ counter }}
        </button>
        <span v-if="showText || counter > 3" @click="showText = false">
            text
        </span>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>наименование</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(category, idx) in categories" :key="idx">
                    <td>{{ category.id }}</td>
                    <td>{{ category.name }}</td>
                </tr>
            </tbody>
        </table>
        <input
            type="checkbox"
            id="checkbox"
            v-model="toggle"
            true-value="да"
            false-value="нет"
        />
        <label for="checkbox">Набрать не переключая раскалдку</label>
        <div v-if="toggle == 'нет'">
            <input v-model="name" class="form-control" />
            <input v-model="lastname" class="form-control" />
        </div>
        <div v-if="toggle == 'да'">
            <input v-model="name" @input="magic" class="form-control" />
            <input v-model="lastname" @input="magic" class="form-control" />
        </div>
        {{ name }}<br />
        {{ name.split("").reverse("").join("") }}<br />
        <button @click="sayMyName" class="btn btn-success btn-lg">
            Кнопка
        </button>
        <br />
        {{ showTranslited() }}
        <br />
        {{ translited }}
        <br />
    </div>
</template>

<script>
export default {
    data() {
        return {
            categories: [
                {
                    id: 1,
                    name: "Процессоры",
                },
                {
                    id: 2,
                    name: "Видеокарты",
                },
                {
                    id: 3,
                    name: "Жесткие диски",
                },
            ],
            counter: 0,
            showText: false,
            name: "",
            translitedName: "",
            firstName: "ILia",
            lastName: "Neustroev",
            toggle: "нет",
            lastname: "",
        }
    },
    computed: {
        translited() {
            return this.translit(this.name)
        },
        fullName() {
            return this.firstName + " " + this.lastName
        },
    },
    methods: {
        clickCounter() {
            this.counter++
        },
        sayMyName() {
            console.log(`My name is ${this.name}!`)
        },
        magic() {
            this.name = this.translit(this.name)
            this.lastname = this.translit(this.lastname)
            //this.name = answer
        },
        translit(word) {
            let converter = {
                " ": "",
                q: "й",
                w: "ц",
                e: "у",
                r: "к",
                t: "е",
                y: "н",
                u: "г",
                i: "ш",
                o: "щ",
                p: "з",
                "[": "х",
                "]": "ъ",
                a: "ф",
                s: "ы",
                d: "в",
                f: "а",
                g: "п",
                h: "р",
                j: "о",
                k: "л",
                l: "д",
                ";": "ж",
                "'": "э",
                z: "я",
                x: "ч",
                c: "с",
                v: "м",
                b: "и",
                n: "т",
                m: "ь",
                ",": "б",
                ".": "ю",
                Q: "Й",
                W: "Ц",
                E: "У",
                R: "К",
                T: "Е",
                Y: "Н",
                U: "Г",
                I: "Ш",
                O: "Щ",
                P: "З",
                "{": "Х",
                "}": "Ъ",
                A: "Ф",
                S: "Ы",
                D: "В",
                F: "А",
                G: "П",
                H: "Р",
                J: "О",
                K: "Л",
                L: "Д",
                ":": "Ж",
                '"': "Э",
                Z: "Я",
                X: "ч",
                C: "С",
                V: "М",
                B: "И",
                N: "Т",
                M: "Ь",
                "<": "Б",
                ">": "Ю",
            }
            let answer = ""
            for (let i = 0; i < word.length; ++i) {
                if (converter[word[i]] == undefined) {
                    answer += word[i]
                } else {
                    answer += converter[word[i]]
                }
            }
            return answer
        },
        showTranslited() {
            return this.translit(this.name)
        },
    },
    mounted() {
        console.log("Component mounted.")
    },
}
</script>

<style scoped>
a {
    text-decoration: none;
    color: rgb(17, 117, 25);
}

a:hover {
    color: rgb(153, 161, 29);
}

a:after {
    color: rgb(153, 161, 29);
}

.card:hover {
    box-shadow: 0.4em 0.4em 5px rgb(122 122 122 / 50%);
}
</style>
