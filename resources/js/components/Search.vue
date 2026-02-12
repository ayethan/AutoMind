<template>
    <div>
        <form id="search-form">
            <input type="text" class="form-control" :query="query" :placeholder="placeholder" @input="$emit('input', $event.target.value)">
        </form>
        <span class="close" @click="cancelClicked">&times;</span>
        <ul class="suggestion" v-if="items.length > 0">
            <li v-for="(item, key) in items" :key="key" @click="onClickItem(item.id)">{{item.name}}</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Search",
    props: {
        onSelect: {
            type: Function,
            required: true
        },
        onCancel: {
            type: Function,
            required: true
        },
        query: {
            type: String
        },
        items: {
            type: Array,
            default: function() {
                return [];
            }
        },
        placeholder: {
            type: String,
            default: "Search"
        }
    },
    watch: {
        query: function(new_value) 
        {
            var vm = this;
            vm.onSearch(new_value);
        }
    },
    methods: {
        onClickItem(id) {
            this.onSelect(id);
        },
        cancelClicked() {
            document.getElementById("search-form").reset();
            this.onCancel();
        }
    }
}
</script>

<style scoped>
    .suggestion {
        list-style-type: none !important;
        border-top-style: hidden !important;
        z-index:  1000 !important;
        background-color: white !important;
        top: 2rem !important;
        width: 100% !important;
        position: absolute !important;
        margin: 0 !important;
        border: 1px solid #ced4da !important;
        padding-inline-start: 0 !important;
        margin-block-start: 0 !important;
        margin-block-end: 0 !important;
        border-bottom-left-radius: 0.25rem !important;
        border-bottom-right-radius: 0.25rem !important; 
        font-size: 0.9rem !important;
    }

    .suggestion li {
        margin: 0;
        padding: 0.375rem 0.75rem !important;
    }

    .suggestion li:hover {
        cursor: pointer !important;
        background-color: #343a40 !important;
        color: white !important;
    }

    .close {
        z-index: 100!important;
        padding-right: 30px !important;
        display:block !important;
        position: absolute !important;
        top : calc(calc(1.4rem - 5px) / 2) !important;
        right: 0px !important;
    }

</style>
