    
var Vue = require('vue');
var VueRouter = require('vue-router');

Vue.use(require('vue-resource'));
Vue.use(VueRouter);

var router = new VueRouter({
   history: false,
   root: '/'
});

/*
 * Import List
 */


import HomePage from './components/HomePage.vue';
import ClientsPage from './components/ClientsPage.vue';
import ClientDetailsPage from './components/ClientDetailsPage.vue';

router.map({
   '/': {
      component: HomePage,
   },

   '/clients': {
        component: ClientsPage
   },

   '/clients/:id': {
        component: ClientDetailsPage
   },

});

var App = Vue.extend({
    components: {
        HomePage,
        ClientsPage
    }
});

router.start(App, '#app');


