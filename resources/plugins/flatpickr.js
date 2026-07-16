import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { English } from "flatpickr/dist/l10n/default.js";

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;

        const setLocaleFromLang = () => {
            const lang = document.documentElement.lang || "en";

            const localeMap = {
                en: English
            };

            const locale = localeMap[lang] || null;

            if (locale) {
                window.Flatpickr.localize(locale);
            }
        };

        setLocaleFromLang();

        const changeTheme = (theme) => {
            const existingTheme = document.getElementById("flatpickr");

            if (existingTheme) {
                existingTheme.remove();
            }

            if (theme === "light") {
                return;
            }

            const linkElement = document.createElement("link");
            linkElement.rel = "stylesheet";
            linkElement.type = "text/css";
            linkElement.href = `https://npmcdn.com/flatpickr/dist/themes/${theme}.css`;
            linkElement.id = "flatpickr";

            document.head.appendChild(linkElement);
        };

        const currentTheme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";

        changeTheme(currentTheme);

        window.emitter.on("change-theme", (theme) => changeTheme(theme));
    },
};
