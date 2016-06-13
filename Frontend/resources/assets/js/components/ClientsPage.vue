<template>
    <h1>Fictitious Ltd CRM - Clients Page</h1>

    <ul>
        <li v-for="client in store.getClientList()">
            <a v-link="{ path: '/clients/' + client.id }">{{ client.name }}</a>  <span @click="deleteClient(client.id)">X</span>
        </li>
    </ul>

    <section>
        <div class="row">
            <div class="col-md-4">
                <h2>Add new client</h2>
                <p v-if="error">{{ error }}</p>
                <input class="form-control" type="text" v-model="name" placeholder="Client Name...">
                <textarea class="form-control" v-model="address" placeholder="Addresss"></textarea>
                <input class="form-control" type="email" v-model="email" placeholder="Client Email...">
                <input class="form-control" type="text" v-model="number" placeholder="Client Number...">
                <button class="btn btn-default" @click="createNewClient">Add new client</button>
            </div>
        </div>
    </section>

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

    export default {
        data: function() {
            return {
                store: AppStore,
                name: '',
                address: '',
                number: '',
                email: ''
            }
        },

        methods: {
            createNewClient: function() {
                var postData = {
                    'name': this.name,
                    'address': this.address,
                    'number': this.number,
                    'email': this.email
                };

                var _self = this;

                ApiService.createNewClient(postData, 
                    function(success) {
                        _self.name    = '';
                        _self.address = '';
                        _self.number  = '';
                        _self.email   = '';

                        populateClientList();
                    }, function(failure) {
                        _self.error = 'Unable to create new client.';
                    });
            },

            deleteClient: function(id) {
                ApiService.deleteClient(id, 
                    function(success) {
                        populateClientList();
                    }, 
                    function(failure) {
                        console.error('Failed to delete client');
                    });
            }
        },

        components: {
        },

        ready: function() {
            populateClientList();
        }

    }
</script>