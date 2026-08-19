function number_format(amount, decimal = 2, code = 'INR') {
    if (!amount) return '₹0.00';

    return parseFloat(amount).toLocaleString('en-IN', {
        style: 'currency',
        currency: code,
        minimumFractionDigits: decimal,
        maximumFractionDigits: decimal,
    });
}

function formatRupee(val, decimals = 1) {
    const num = Math.abs(Number(val)) || 0;
    const sign = Number(val) < 0 ? '-' : '';
    if (num >= 1000000000) {
        const b = (num / 1000000000).toFixed(decimals);
        return `${sign}₹${b.endsWith('.0') ? b.slice(0, -2) : b}B`;
    }
    if (num >= 1000000) {
        const m = (num / 1000000).toFixed(decimals);
        return `${sign}₹${m.endsWith('.0') ? m.slice(0, -2) : m}M`;
    }
    if (num >= 1000) {
        const k = (num / 1000).toFixed(decimals);
        return `${sign}₹${k.endsWith('.0') ? k.slice(0, -2) : k}K`;
    }
    return `${sign}₹${num.toLocaleString('en-IN')}`;
}

function ucwords(str) {
    str = str?.trim() ?? '';

    if (!str) {
        return '';
    }

    return str.charAt(0).toUpperCase() + (str.length != 1 ? str.slice(1) : '');
}

function errorControl(error, setErrors = null) {
    if (!!!error?.response?.status) {
        console.error('Unexpected error.', error);
        return;
    }

    if (error.response.status === 422) {
        if (error.response.data.errors?.general) {
            window.emitter.emit('add-flash', {
                type: 'error',
                message: error.response.data.errors?.general[0]
            });
        }

        if (setErrors) {
            setErrors(error.response.data.errors);
        }

        return;
    }

    window.emitter.emit('add-flash', {
        type: 'error',
        message: error.response.data.message
    });
}

function supabase() {
    const supabaseUrl = import.meta.env.VITE_SUPABASE_URL
    const supabaseKey = import.meta.env.VITE_SUPABASE_ANON_KEY

    return {
        'url': import.meta.env.VITE_SUPABASE_URL,
        'key': import.meta.env.VITE_SUPABASE_ANON_KEY,
    }
}

const originHelpers = {
    number_format, ucwords, errorControl, formatRupee, supabase
}

/**
 * Add another helper here.
 */
const helpers = {
    ...originHelpers,
};

// make it global (important)
window.helpers = helpers;

export default {
    install(app) {
        app.config.globalProperties.$helpers = helpers;
    },
};
