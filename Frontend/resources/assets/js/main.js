    
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
import MainNavigation from './components/MainNavigation.vue';
import HomePage from './components/HomePage.vue';
import ClientsPage from './components/ClientsPage.vue';
import ClientDetailsPage from './components/ClientDetailsPage.vue';
import ContactDetailsPage from './components/ContactDetailsPage.vue';


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

   '/contact/:id': {
        component: ContactDetailsPage 
   }

});

var App = Vue.extend({
    components: {
        HomePage,
        ClientsPage,
        ClientDetailsPage,
        ContactDetailsPage,
        MainNavigation
    }
});

router.start(App, '#app');


