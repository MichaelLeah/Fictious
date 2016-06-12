<template>
    <main-navigation></main-navigation>

    <h1>Fictitious Ltd CRM</h1>
    <p>Welcome Employee, here is the prototype of the CRM system. Here you will be able to view, edit and update your contacts and clients as well as seeing all conversations you have held with specific contacts.</p>
</template>

<style scoped>
    div {
        background-color: lightgreen;
    }
</style>

<script>   
    import MainNavigation from './MainNavigation.vue';
    
    export default {
        data: function() {
            return {
                message: 'Hello World!'
            }
        },

        components: {
            MainNavigation
        }
    }
</script>