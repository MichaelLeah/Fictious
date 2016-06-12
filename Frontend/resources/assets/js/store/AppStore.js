const AppStore = {
    _clientList: undefined,

    setClientList(clientList) {
        this._clientList = clientList;
    },

    getClientList: function() {
        return this._clientList;
    }
}

module.exports = AppStore;