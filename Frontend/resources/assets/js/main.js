    
var Vue = require('vue');
Vue.use(require('vue-resource'));

import HelloWorld from './components/HelloWorld.vue';


new Vue({
    el: '#app',

    components: {
        HelloWorld,
    },

    ready: function() {
        alert('We are ready to go!');
    }
});