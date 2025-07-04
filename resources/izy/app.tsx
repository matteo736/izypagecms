
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import { store } from './Redux/store';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Definisci il tipo per i moduli importati
type PageModule = {
    default: {
        layout?: (page: JSX.Element) => JSX.Element;
    };
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pageComponent = `./Pages/${name}.tsx`;
        const pages = import.meta.glob(['./Pages/**/*.tsx', './Pages/Themes/**/*.tsx']);
        if (pages[pageComponent]) {
            let page = resolvePageComponent(pageComponent, { [pageComponent]: pages[pageComponent] });
            return page;
        } else {
            console.error(`Page component not found: ${pageComponent}`);
            throw new Error(`Page component not found: ${pageComponent}`);
        }
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(
            <Provider store={store}>
                <App {...props} />
            </Provider>
        );
    },
    progress: {
        color: '#4B5563',
    },
});
