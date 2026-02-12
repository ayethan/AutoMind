<template>
    <nav>
        <ul class="pagination" role="navigation">
            <li v-bind:class="previous_class_page_item" v-if="!is_first" :href="href" @click="paginate(first)">
                <span :class="previous_class_page_link">{{first}}</span>
            </li>
            <li class="page-item disabled" v-if="!is_second_first">
                <span class="page-link">...</span>
            </li>
            <li class="page-item" v-if="!is_second_first" :href="href" @click="paginate(current-1)">
                <a class="page-link">{{current-1}}</a>
            </li>
            <li class="page-item disabled">
                <a class="page-link active bg-secondary text-white" :href="href">{{current}}</a>
            </li>
            <li class="page-item" v-if="!is_second_last" :href="href" @click="paginate(current+1)">
                <a class="page-link">{{current+1}}</a>
            </li>
            <li class="page-item disabled" v-if="!is_second_last" >
                <span class="page-link">...</span>
            </li>
            <li v-bind:class="next_class_page_item" v-if="!is_last" :href="href" @click="paginate(last)">
                <span :class="next_class_page_link">{{last}}</span>
            </li>
        </ul>
        <p>
            Showing {{from}} to {{to}} of {{total}}
        </p>
    </nav>
</template>

<script>
export default {
    name: "Pagination",
    data() {
        return {
            href: "javascript:void(0);"
        };
    },
    props: {
        callback: {
            type: Function,
            required: true
        },
        current: {
            type: Number,
            required: true,
            default: 1
        },
        total: { // total items
            type: Number,
            required: true,
            default: 0
        },
        last: {
            type: Number,
            required: true,
            default: 0
        },
        first: {
            type: Number,
            default: 1
        },
        from: [Number, String],
        to: [Number, String]
    },
    computed: {
        is_first() {
            return this.current == this.first; 
        },
        is_last() {
            return this.current == this.last;
        },
        is_second_first() {
            return this.current <= this.first+1;
        },
        is_second_last() {
            return this.current >= this.last-1;
        },
        previous_class_page_item() {
            if (this.is_first) {
                return "page-item disabled";
            }
            return "page-item";
        },
        next_class_page_item() {
            if (this.is_last) {
                return "page-item disabled";
            }
            return "page-item";
        },
        previous_class_page_link() {
            if (this.is_first) {
                return "page-link ";
            }
            return "page-link text-white bg-primary";
        },
        next_class_page_link() {
            if (this.is_last) {
                return "page-link disabled";
            }
            return "page-link text-white bg-primary";
        },
    },
    methods: {
        paginate(page) {
            this.callback(page);
        } 
    }
}
</script>

