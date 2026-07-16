/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize, setLocale } from "@vee-validate/i18n";
import en from "@vee-validate/i18n/dist/locale/en.json";
import { all } from '@vee-validate/rules';

window.defineRule = defineRule;

export default {
    install: (app) => {
        /**
         * Global components registration;
         */
        app.component("VForm", Form);
        app.component("VField", Field);
        app.component("VErrorMessage", ErrorMessage);

        window.addEventListener("load", () => setLocale(document.documentElement.attributes.lang.value));

        /**
         * Registration of all global validators.
         */
        Object.entries(all).forEach(([name, rule]) => {
            defineRule(name, (value, params, ctx) => {
                const processedValue = typeof value === 'string' ? value.trim() : value;

                return rule(processedValue, params, ctx);
            });
        });

        /**
         * This regular expression allows phone numbers with the following conditions:
         * - The phone number can start with an optional "+" sign.
         * - After the "+" sign, there should be one or more digits.
         *
         * This validation is sufficient for global-level phone number validation. If
         * someone wants to customize it, they can override this rule.
         */
        defineRule("phone", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (! /^\+?\d+$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("address", (value) => {
            if (!value || !value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (
                !/^[a-zA-Z0-9\s.\/*'\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\u0590-\u05FF\u3040-\u309F\u30A0-\u30FF\u0400-\u04FF\u0D80-\u0DFF\u3400-\u4DBF\u2000-\u2A6D\u00C0-\u017F\u0980-\u09FF\u0900-\u097F\u4E00-\u9FFF,\(\)-]{1,60}$/iu.test(
                    trimmedValue
                )
            ) {
                return false;
            }

            return true;
        });

        defineRule("postcode", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (! /^[a-zA-Z0-9][a-zA-Z0-9\s-]*[a-zA-Z0-9]$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("decimal", (value, { decimals = '*', separator = '.' } = {}) => {
            if (value === null || value === undefined || value === '') {
                return true;
            }

            const trimmedValue = value.trim();

            if (Number(decimals) === 0) {
                return /^-?\d*$/.test(trimmedValue);
            }

            const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;

            const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

            return regex.test(trimmedValue);
        });

        defineRule("date_format", (value, params) => {
            if (! value || ! value.length) {
                return true;
            }

            const format = Array.isArray(params) ? params[0] : params;

            if (format === 'H:i') {
                return /^([01]?\d|2[0-3]):[0-5]\d$/.test(value.trim());
            }

            return true;
        });

        defineRule("required_if", (value, { condition = true } = {}) => {
            if (condition) {
                if (value === null || value === undefined || value === '') {
                    return false;
                }
            }

            return true;
        });

        defineRule("", () => true);

        configure({
            /**
             * Built-in error messages and custom error messages are available. Multiple
             * locales can be added in the same way.
             */
            generateMessage: localize({
                en: {
                    ...en,
                    messages: {
                        ...en.messages,
                        date_format: "The {field} must be in a valid time format (e.g.: 23:59).",
                        decimal: "This {field} must be a valid decimal number.",
                        phone: "This {field} must be a valid phone number",
                    },
                }
            }),

            validateOnBlur: true,
            validateOnInput: true,
            validateOnChange: true,
        });
    },
};
