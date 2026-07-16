

const guest_url = "/auth/login";

window.guestRedirectTo = guest_url;

export default {
    install: (app, options) => {
        app.config.globalProperties.$guestRedirectTo = guest_url;
    },
};
