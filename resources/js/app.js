import './bootstrap';
import { createApp, h } from 'vue';
import { app, plugin } from '@inertiajs/inertia-vue3';
import Home from './Pages/Home.vue';

const el = document.getElementById('app');

createApp({
  render: () => h(app, {
    initialPage: JSON.parse(el.dataset.page),
    resolveComponent: (name) => import(`./Pages/${name}.vue`).then(module => module.default),
  }),
}).use(plugin).mount(el);
