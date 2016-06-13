const AppStore = {
    _clientList: undefined,
    _clientContactList: undefined,

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
    }
}

module.exports = AppStore;