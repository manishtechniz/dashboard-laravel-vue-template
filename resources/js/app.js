/**
 * This will track all the images and fonts for publishing.
 */
// import.meta.glob(["../images/**", "../fonts/**"]);

/**
 * Main vue bundler.
 */
import { createApp } from "vue/dist/vue.esm-bundler";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import Lara from '@primeuix/themes/lara';
import Nora from '@primeuix/themes/nora';
import Material from '@primeuix/themes/material';
// import 'primeicons/primeicons.css'

// import 'primeicons/primeicons.css';
// import './assets/main.css';

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
        preset: Aura,
        // options: {
        //     darkModeSelector: '.dark-app',
        // }
    }
});

/**
 * PrimeVue Components
 */
import Button from "primevue/button";
import Tag from "primevue/tag";
import Checkbox from "primevue/checkbox";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Textarea from "primevue/textarea";
import Select from "primevue/select";
import Password from 'primevue/password';
import TabView from 'primevue/tab';
import TabViews from 'primevue/tabs';
import TabPanel from "primevue/tabpanel";
import TabPanels from 'primevue/tabpanels';
import TabList from 'primevue/tablist';
import ToggleSwitch from "primevue/toggleswitch";
import Timeline from "primevue/timeline";
import FloatLabel from 'primevue/floatlabel';
import DatePicker from 'primevue/datepicker';
import MultiSelect from 'primevue/multiselect';
import CheckboxGroup from 'primevue/checkboxgroup';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Slider from 'primevue/slider';
import RadioButton from 'primevue/radiobutton';
import RadioButtonGroup from 'primevue/radiobuttongroup';
import Toast from 'primevue/toast';
import { Form } from '@primevue/forms';
import Paginator from 'primevue/paginator';

/**
 * Boneyard AUTO SLELETON
 */
import Skeleton from 'boneyard-js/vue'
// import './bones/registry.js'


/**
 * Register globally PrimeVue Components
 */
[
    ["DataTable", DataTable],
    ["Column", Column],
    ["Row", Row],
    ["ColumnGroup", ColumnGroup],
    ["Button", Button],
    ["Tag", Tag],
    ["Dialog", Dialog],
    ["InputText", InputText],
    ["Textarea", Textarea],
    ["Select", Select],
    ["Password", Password],
    ["TabView", TabView],
    ["TabViews", TabViews],
    ["TabPanel", TabPanel],
    ["TabPanels", TabPanels],
    ["TabList", TabList],
    ["Tab", TabView],
    ["Tabs", TabViews],
    ["ToggleSwitch", ToggleSwitch],
    ["Timeline", Timeline],
    ["FloatLabel", FloatLabel],
    ["DatePicker", DatePicker],
    ["MultiSelect", MultiSelect],
    ["Slider", Slider],
    ["Checkbox", Checkbox],
    ["CheckboxGroup", CheckboxGroup],
    ["RadioButton", RadioButton],
    ["RadioButtonGroup", RadioButtonGroup],
    ["Toast", Toast],
    ["Form", Form],
    ["Paginator", Paginator],

    ["Skeleton", Skeleton]
].forEach(([name, component]) => {
    adminVueApp.component(name, component);
});

/**
 * Global plugins registration.
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
