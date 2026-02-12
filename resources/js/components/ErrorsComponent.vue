<template>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            <li v-for="(item, index) in errors" :key="index">
                <strong> {{item}} </strong>
            </li>
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
export default {
    name: "ErrorsComponent",
    props: {
        errors: Object
    }
}
</script>
