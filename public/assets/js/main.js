import { loadPageModule } from './core/pageLoader.js';

const page = document.querySelector('main')?.dataset.page;
if (page) {
    loadPageModule(page);
}
