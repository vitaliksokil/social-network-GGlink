/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');
require('./main');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('chat', require('./components/Chat.vue').default);
Vue.component('conversation', require('./components/Conversation.vue').default);

import Notifications from 'vue-notification'
import Vuex from 'vuex';

Vue.use(Notifications);
Vue.use(Vuex);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    created(){
        let authUserId = $('meta[name="auth-user-id"]').attr('content');
        Echo.private(`new.message.notification.${authUserId}`)
            .notification((newMessage) => {
                this.newMessageNotification(newMessage);
                this.$emit('changeMessagesCount',{'newMessage':newMessage})
            });
    },
    mounted(){
        this.$on('changeMessagesCount',(data)=>{
            $('#messagesCount').text("+"+data.newMessage.newMessagesCount)
        })
    },
    methods:{
        // todo show it if user are not on the conversation page
        newMessageNotification(newMessage){
            this.$notify({
                group: 'messages',
                duration: 10000,
                speed: 1000,
                max:4,
                data:{
                    message: newMessage.message,
                    from_user: newMessage.from_user,
                }
            });
        }
    }
});


