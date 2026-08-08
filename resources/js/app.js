/**
 * This will track all the images and fonts for publishing.
 */
// import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Main vue bundler.
 * ADDED: defineAsyncComponent for lazy loading
 */
import { createApp, defineAsyncComponent } from "vue/dist/vue.esm-bundler";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Lara from '@primeuix/themes/lara';
import Nora from '@primeuix/themes/nora';
import Material from '@primeuix/themes/material';
// import 'primeicons/primeicons.css';

/**
 * Main root application registry.
 */
window.adminVueApp = createApp({
    data() {
        return {
            dynamicModelFields: {},
            areHideFields: {},
            dynamicFields: {},
            dynamicValidations: {}
        };
    },

    methods: {
        onSubmit() { },

        onInvalidSubmit({ values, errors, results }) {
            setTimeout(() => {
                const errorKeys = Object.entries(errors)
                    .map(([key, value]) => ({ key, value }))
                    .filter(error => error["value"].length);

                if (errorKeys.length > 0) {
                    const errorKey = errorKeys[0]["key"];

                    let scrollTarget = null;

                    // Try to find the input element with the exact name first.
                    let firstErrorElement = document.querySelector('[name="' + errorKey + '"]');

                    // If not found and the key doesn't end with [], try with the [] suffix (for array fields like categories[], channels[]).
                    if (
                        !firstErrorElement
                        && !errorKey.endsWith('[]')
                    ) {
                        firstErrorElement = document.querySelector('[name="' + errorKey + '[]"]');
                    }

                    // If still not found, try to find any element that starts with this name (for nested fields).
                    if (!firstErrorElement) {
                        firstErrorElement = document.querySelector('[name^="' + errorKey + '"]');
                    }

                    // If we found the input element.
                    if (firstErrorElement) {
                        // Check if this is a TinyMCE textarea (hidden by TinyMCE).
                        if (firstErrorElement.tagName === 'TEXTAREA' && firstErrorElement.style.display === 'none') {
                            // Find the TinyMCE editor container.
                            const editorId = firstErrorElement.id;

                            const tinyMCEContainer = document.querySelector('#' + editorId + '_parent');

                            if (tinyMCEContainer) {
                                scrollTarget = tinyMCEContainer;
                            } else {
                                scrollTarget = firstErrorElement;
                            }
                        } else {
                            scrollTarget = firstErrorElement;
                        }
                    } else {
                        // If the input is not found, try to find the error message element itself.
                        // VeeValidate renders error messages with a v-error-message component having a name attribute.
                        const errorMessageElement = document.querySelector('[name="' + errorKey + '"] p, [name="' + errorKey + '[]"] p');

                        if (errorMessageElement) {
                            // Scroll to the parent container of the error message.
                            scrollTarget = errorMessageElement.closest('.box-shadow') || errorMessageElement.closest('div[class*="bg-white"]') || errorMessageElement;
                        }
                    }

                    if (scrollTarget) {
                        scrollTarget.scrollIntoView({
                            behavior: "smooth",
                            block: "center"
                        });

                        // Try to focus the element: for TinyMCE, focus the editor; for regular inputs, focus the input.
                        if (firstErrorElement) {
                            if (firstErrorElement.tagName === 'TEXTAREA' && firstErrorElement.style.display === 'none') {
                                // Focus the TinyMCE editor if available.
                                const editorId = firstErrorElement.id;

                                if (window.tinymce && tinymce.get(editorId)) {
                                    tinymce.get(editorId).focus();
                                }
                            } else if (firstErrorElement.focus) {
                                firstErrorElement.focus();
                            }
                        }
                    } else {
                        // If the scroll target is not found, show a flash message with all errors.
                        const allErrors = errorKeys
                            .map(error => {
                                if (Array.isArray(error.value)) {
                                    return error.value.join(', ');
                                }

                                return error.value;
                            })
                            .filter(msg => msg).join(' ');

                        this.$emitter.emit('add-flash', {
                            type: 'error',
                            message: allErrors
                        });
                    }
                }
            }, 100);
        },
    },
});

adminVueApp.use(PrimeVue, {
    theme: {
        preset: Aura
    }
});

/**
 * ------------------------------------------------------------------------
 * ASYNC COMPONENT REGISTRATION (CODE SPLITTING FIX)
 * ------------------------------------------------------------------------
 * Instead of eager imports, we map components to dynamic imports.
 * Vite will automatically split these into smaller, separate JS files.
 */
const asyncComponents = {
    "DataTable": () => import('primevue/datatable'),
    "Column": () => import('primevue/column'),
    "Row": () => import('primevue/row'),
    "ColumnGroup": () => import('primevue/columngroup'),
    "Button": () => import('primevue/button'),
    "Tag": () => import('primevue/tag'),
    "Dialog": () => import('primevue/dialog'),
    "InputText": () => import('primevue/inputtext'),
    "Textarea": () => import('primevue/textarea'),
    "Select": () => import('primevue/select'),
    "Password": () => import('primevue/password'),
    "TabView": () => import('primevue/tab'),
    "TabViews": () => import('primevue/tabs'),
    "TabPanel": () => import('primevue/tabpanel'),
    "TabPanels": () => import('primevue/tabpanels'),
    "TabList": () => import('primevue/tablist'),
    "Tab": () => import('primevue/tab'),
    "Tabs": () => import('primevue/tabs'),
    "ToggleSwitch": () => import('primevue/toggleswitch'),
    "Timeline": () => import('primevue/timeline'),
    "FloatLabel": () => import('primevue/floatlabel'),
    "DatePicker": () => import('primevue/datepicker'),
    "MultiSelect": () => import('primevue/multiselect'),
    "Slider": () => import('primevue/slider'),
    "Checkbox": () => import('primevue/checkbox'),
    "CheckboxGroup": () => import('primevue/checkboxgroup'),
    "RadioButton": () => import('primevue/radiobutton'),
    "RadioButtonGroup": () => import('primevue/radiobuttongroup'),
    "Toast": () => import('primevue/toast'),
    "Paginator": () => import('primevue/paginator'),
    "ProgressBar": () => import('primevue/progressbar'),
    "Badge": () => import('primevue/badge'),
    "Message": () => import('primevue/message'),
    "Card": () => import('primevue/card'),
    "Image": () => import('primevue/image'),
    "Accordion": () => import('primevue/accordion'),
    "AccordionPanel": () => import('primevue/accordionpanel'),
    "AccordionHeader": () => import('primevue/accordionheader'),
    "AccordionContent": () => import('primevue/accordioncontent'),
    "Drawer": () => import('primevue/drawer'),

    // Note: Form requires named export handling
    "Form": () => import('@primevue/forms').then(m => m.Form),

    // Boneyard Skeleton
    "Skeleton": () => import('boneyard-js/vue')
};

// Register them all globally, but lazily
Object.entries(asyncComponents).forEach(([name, importFn]) => {
    adminVueApp.component(name, defineAsyncComponent(importFn));
});


/**
 * Global plugins registration.
 * (Plugins must remain synchronously loaded as they setup the core app instance)
 */
import GuestRedirectTo from "../plugins/guest-redirect-to";
import Emitter from "../plugins/emitter";
import Flatpickr from "../plugins/flatpickr";
import VeeValidate from "../plugins/vee-validate";
import Axios from "../plugins/axios";
import ToastService from 'primevue/toastservice';

[
    Axios,
    Emitter,
    Flatpickr,
    VeeValidate,
    GuestRedirectTo,
    ToastService,
].forEach((plugin) => adminVueApp.use(plugin));

export default adminVueApp;