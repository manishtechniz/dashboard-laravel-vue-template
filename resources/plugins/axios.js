/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import axios from "axios";

window.axios = axios; 

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.axios.defaults.headers.common['Accept'] = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

let token = document.head.querySelector('meta[name="csrf-token"]');

console.log('token', token.content);
if (token) {
    // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

// Request interceptor (auto attach token)
axios.interceptors.request.use((config) => {
    return config;
});

// Response interceptor (handle 401)
// axios.interceptors.response.use(
//     response => response,
//     error => {
//         if (error.response?.status === 401
//             && ! window.location.pathname.includes(window.guestRedirectTo)
//         ) {
//             localStorage.removeItem('access_token');

//             window.location.href = window.guestRedirectTo;
//         }
         
//         return Promise.reject(error);
//     }
// );

export default {
    install(app) {
        app.config.globalProperties.$axios = axios;
    },
};
