<template>
    <h1>Fictitious Ltd CRM - Client Details Page</h1>

    <div class="row">
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="client.name" type="text" value="{{ client.name }}" />
        </div>
        <div class="col-xs-12 col-md-4 col-lg-3">
            <textarea class="form-control" v-model="client.address">{{ client.address }}</textarea>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="client.number" type="text" value="{{ client.number }}" />
        </div>
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="client.email" type="text" value="{{ client.email }}" />
        </div>
        <div class="col-xs-12 col-md-6 col-lg-3">
            <button class="btn btn-default" @click="updateClient(client.id)">Save</button>
        </div>
    </div>

    <ul>
        <li v-for="contact in store.getClientContactList()">
            <a v-link="{ path: '/clients/' + contact.id }">{{ contact.name }}</a>
        </li>
    </ul>
</template>

<style></style>

<script>
    var AppStore   = require('../store/AppStore');
    var ApiService = require('../services/ApiService');

    var populateClientContactList = function(clientId) {
        ApiService.getContactListForClient(clientId, 
            function(success) {
                AppStore.setClientContactList(success.data.contactList);
            }, function(failure) {
                console.error(failure);
            });    
    }

    var populateClientDetails = function(clientId) {
        ApiService.getClientDetails(clientId, 
            function(success) {
                return success.client;
            },
            function(failure) {
                console.error(failure);
            });
    }

    export default {
        data: function() {
            return {
                store: AppStore,
                client: 'Test',
            }
        },

        methods: {
            updateClient: function(id) {
                var postData = {
                    name: this.client.name, 
                    address: this.client.address, 
                    email: this.client.email, 
                    number: this.client.number
                };
                
                ApiService.updateClient(id, postData, 
                    function(success) {
                        console.info(success);
                    },
                    function(failure) {
                        console.error(failure);
                    });
            }
        },

        ready: function() {
            populateClientContactList(this.$route.params.id);
            this.client = this.store.getClient(this.$route.params.id);
        }

    }
</script>