<x-admin::layouts>
<v-test></v-test>
@pushOnce('scripts')

<script
    type="text/x-template"
    id="v-test-template"
>
    <div>
        <button @click="show = !show">
            Toggle
        </button>

        <p v-if="show">Hello Test VUE</p>
    </div>
</script>

<script type="module">
    adminVueApp.component('v-test', {
        template: '#v-test-template',

        data() {
            return {
                show: false
            };
        },

    });
</script>

@endPushOnce
</x-admin::layouts>
