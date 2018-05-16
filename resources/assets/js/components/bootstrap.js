
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Components
 |--------------------------------------------------------------------------
 |
 | Here we will load the Spark components which makes up the core client
 | application. This is also a convenient spot for you to load all of
 | your components that you write while building your applications.
 */

require('./../spark-components/bootstrap');

require('./home');

import ExampleComponent from './ExampleComponent.vue';

import TaskList from './TaskList.vue';

import TaskForm from './TaskForm.vue';

import TaskModal from './TaskModal.vue';

import TaskZoomModal from './TaskZoomModal.vue';

import CompanyForm from './CompanyForm.vue';

import ContactForm from './ContactForm.vue';

import QuoteComponent from './QuoteComponent.vue';

import NewAddressComponent from './NewAddressComponent.vue';

import CreatePartForm from './CreatePartForm.vue';

import NotesComponent from './NotesComponent.vue';

import CompanyView from './CompanyView.vue';

import ContactsTable from './ContactsTable.vue';

import CreateContactModal from './CreateContactModal.vue';

import ActivityForm from './ActivityForm.vue';

import CreateActivityModal from './CreateActivityModal.vue';

import ActivityList from './ActivityList.vue';

import CompanyList from './CompanyList.vue';

import ImportComponent from './ImportComponent.vue';

import PartsComponent from './PartsComponent.vue';

Vue.component('parts-component', PartsComponent);

Vue.component('import-component', ImportComponent);

Vue.component('company-list',CompanyList);

Vue.component('activity-list', ActivityList);

Vue.component('create-activity-modal', CreateActivityModal);

Vue.component('activity-form', ActivityForm);

Vue.component('contact-modal', CreateContactModal);

Vue.component('contacts-table', ContactsTable);

Vue.component('company-view', CompanyView);

Vue.component('notes-component', NotesComponent);

Vue.component('new-address', NewAddressComponent);

Vue.component('quote-component', QuoteComponent);

Vue.component('example-component', ExampleComponent);

Vue.component('task-list', TaskList);

Vue.component('task-form', TaskForm);

Vue.component('task-modal', TaskModal);

Vue.component('task-zoom-modal', TaskZoomModal);

Vue.component('company-form', CompanyForm);

Vue.component('contact-form', ContactForm);

Vue.component('create-part-form', CreatePartForm);


