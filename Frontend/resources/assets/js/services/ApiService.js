var Vue = require('vue');
var AppStore = require('../store/AppStore');

Vue.use(require('vue-resource'));

const ApiService = {
    API_DOMAIN: 'http://api.fictitious.local',

    // Get Client list from the API
    getClientList: function(onSuccess, onFailure) {
        var endpoint = this.API_DOMAIN + '/client';
        Vue.http.get(endpoint).then(onSuccess).catch(onFailure);
    }
    
    


};

module.exports = ApiService;