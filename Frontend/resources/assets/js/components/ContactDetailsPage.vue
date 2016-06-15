<template>
    <h1>Contact Details for: {{ contact.name }}</h1>
    <div class="row">
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="contact.name" type="text" value="{{ contact.name }}" />
        </div>
        <div class="col-xs-12 col-md-4 col-lg-3">
            <textarea class="form-control" v-model="contact.job_role">{{ contact.job_role }}</textarea>
        </div>
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="contact.number" type="text" value="{{ contact.number }}" />
        </div>
        <div class="col-xs-12 col-md-4 col-lg-2">
            <input class="form-control" v-model="contact.email" type="text" value="{{ contact.email }}" />
        </div>
        <div class="col-xs-12 col-md-6 col-lg-3">
            <button class="btn btn-default" @click="updateContact(contact.id)">Save</button>
        </div>
    </div>

    <h1>Conversations</h1>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <textarea class="form-control" v-model="newMessage"></textarea>
        </div>
        <div class="col-xs-12 col-md-6">
            <button class="btn btn-default" @click="saveMessage()">Save Message</button>
        </div>
    </div>

    <hr/>
    
    <ul>
        <li v-for="message in store.getContactConversations()">
            {{ message.details }}
        </li>
    </ul>

</template>

<style>
</style> 

<script>
    var AppStore   = require('../store/AppStore');
    var ApiService = require('../services/ApiService');

    var populateContactDetails = function(contactId) {
        ApiService.getContactDetails(contactId, 
            function(success) {
                return success.contact;
            },
            function(failure) {
                console.error(failure);
            });
    }

    var populateContactConversations = function(contactId) {
        ApiService.getContactConversations(contactId,
            function(success) {
                AppStore.setContactConversations(success.data.conversations);
            },
            function(failure) {
                console.error(failure);
            });
    }

    export default {
        data: function() {
            return {
                store: AppStore,
                contact: '',
                conversations: '',
                newMessage: '',
            }
        },

        methods: {
            updateContact: function(id) {
                var postData = {
                    name: this.contact.name, 
                    job_role: this.contact.job_role, 
                    email: this.contact.email, 
                    number: this.contact.number
                };

                ApiService.updateContact(id, postData, 
                    function(success) {
                        console.info(success);
                    },
                    function(failure) {
                        console.error(failure);
                    });
            },

            saveMessage: function() {
                var postData = {
                    details: this.newMessage,
                    employee_id: 1,
                    contact_id: this.contact.id,
                    client_id: this.contact.client_id
                };

                var _self = this;
                ApiService.createNewMessage(postData, 
                    function(success) {
                        _self.newMessage = '';
                        populateContactConversations(_self.contact.id);
                    },
                    function(failure) {
                        console.error(failure);
                    });
            }
        },

        ready: function() {
            this.contact = this.store.getContact(this.$route.params.id);
            populateContactConversations(this.$route.params.id);
            this.clientId = this.$route.params.id;
        }
    }

</script>