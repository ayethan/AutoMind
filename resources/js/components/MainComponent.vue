<template>
    <div id="main">
        <TopNav v-if="template != 'blank'"/>
        <div id="wrapper">
            <SideBar  v-if="template != 'blank'"/>
            <div id="content-wrapper">
                <router-view></router-view>
            </div>
        </div>
        <ScrollToTop  v-if="template != 'blank'"/>
    </div>
</template>

<script>
    import TopNav from "./TopNav";
    import SideBar from './SideBar';
    import ScrollToTop from './ScrollToTop';
    export default {
        name: 'MainComponent',
        components: {
            TopNav,
            SideBar,
            ScrollToTop
        },
        computed: {
            template: function() {
                return this.$route.meta.template;
            }
        }
    }
</script>
