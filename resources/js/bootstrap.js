window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/* window.location.origin drops the path, so on a sub folder install every
   request lost the folder and went to the host root: /mtop/getdata instead of
   /mtfru/mtop/getdata, and every call answered 404. the meta tag carries what
   Laravel itself thinks the base is, which is right on localhost, on artisan
   serve and in a sub folder alike. */
const baseUrlMeta = document.head.querySelector('meta[name="base-url"]');

window.axios.defaults.baseURL = ((baseUrlMeta && baseUrlMeta.content) || window.location.origin)
    .replace(/\/*$/, '/');


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.toastr = require('toastr');



// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
