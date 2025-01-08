import { Application } from 'stimulus';
import { definitionsFromContext } from 'stimulus/webpack-helpers';

// Initialiser Stimulus
const app = Application.start();
const context = require.context('./controllers', true, /\.js$/);
app.load(definitionsFromContext(context));

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
