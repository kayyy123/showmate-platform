import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { showSuccess, showError, showWarning, showInfo } from './Composables/useToast';

const appName = import.meta.env.VITE_APP_NAME || 'EtalaseKu';

let lastFlashKey = '';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        app.mixin({
            updated() {
                const flash = this.$page?.props?.flash || {};
                const errors = this.$page?.props?.errors || {};
                const key = JSON.stringify({ flash, errors });
                if (key === lastFlashKey) return;
                lastFlashKey = key;

                if (Object.keys(errors).length > 0) {
                    const first = Object.values(errors).find(v => typeof v === 'string');
                    if (first) showWarning(first);
                }

                if (flash.error) { showError(flash.error); return; }
                if (flash.success) { showSuccess(flash.success); return; }
                if (flash.warning) { showWarning(flash.warning); return; }
                if (flash.info) { showInfo(flash.info); return; }
            },
        });

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
