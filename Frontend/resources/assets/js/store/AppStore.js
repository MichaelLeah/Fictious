const AppStore = {
    _clientList: undefined,
    _clientContactList: undefined,
    _contactConversations: undefined,

    setClientList(clientList) {
        this._clientList = clientList;
    },

    getClientList: function() {
        return this._clientList;
    },

    setClientContactList(contactList) {
        this._clientContactList = contactList;
    },

    getClientContactList() {
        return this._clientContactList;
    },

    getClient(clientId) {
        var _client = {};
        this._clientList.forEach(function(client) {
            if (client.id == clientId) {
                _client = client;
            }
        });

        return _client;
    },

    getContact(contactId) {
        var _contact = {};
        this._clientContactList.forEach(function(contact) {
            if (contact.id == contactId) {
                _contact = contact;
            }
        });

        return _contact;
    },

    setContactConversations(conversations) {
        this._contactConversations = conversations;
    },

    getContactConversations() {
        return this._contactConversations;
    }
}

module.exports = AppStore;