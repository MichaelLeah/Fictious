<template>
    <main-navigation></main-navigation>

    <h1>Fictitious Ltd CRM - Clients Page</h1>

    <ul>
        <li v-for="client in store.getClientList()">
            <a v-link="{ path: '/clients/' + client.id }">{{ client.name }}</a>
        </li>
    </ul>

</template>

<style scoped></style>

<script>   
    var AppStore   = require('../store/AppStore');
    var ApiService = require('../services/ApiService');

    var populateClientList = function() {
        ApiService.getClientList(
            function(success) {
                AppStore.setClientList(success.data.clientList);
            },
            function(failure) {
                console.error('Could not load client list');
            });
    }

    import MainNavigation from './MainNavigation.vue';
    
    export default {
        data: function() {
            return {
                store: AppStore,
            }
        },

        components: {
            MainNavigation
        },

        ready: function() {
            populateClientList();
        }

    }
</script>