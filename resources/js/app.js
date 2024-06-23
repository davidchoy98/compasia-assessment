import './bootstrap';

import { createApp } from 'vue';
import App from './layouts/default.vue';
import vuetify from './vuetify';

import 'sweetalert2/dist/sweetalert2.min.css';

createApp(App)
    .use(vuetify)
    .mount('#app');
