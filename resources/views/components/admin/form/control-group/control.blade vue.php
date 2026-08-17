@props([
    'type' => 'text',
])

@switch($type)
    @case('text')
    @case('email')
    @case('password')
    @case('number')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules']) }}
        >
            <FloatLabel variant="on"
            >
                <InputText
                    v-bind="field"
                    fluid
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>
        </v-field>

        <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        @break
    @case('hidden')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules']) }}
        >
            <input
                type="{{ $type }}"
                {{ $attributes->only(['name', ':name']) }}
                v-bind="field"
            />
        </v-field>

        @break
    @case('file')
        <v-field
            v-slot="{ field, errors, handleChange, handleBlur }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                v-bind="{ name: field.name }"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full rounded-lg! border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 ']) }}
                @change="handleChange"
                @blur="handleBlur"
            />
        </v-field>

        @break

    @case('textarea')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules']) }}
        >
            <FloatLabel variant="on"
            >
                <Textarea
                    v-bind="field"
                    fluid
                    rows="5" cols="30"
                    style="resize: none"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

            @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
                <x-admin::tinymce
                    :selector="'textarea#' . $attributes->get('id')"
                    :prompt="stripcslashes($attributes->get('prompt', ''))"
                    ::field="field"
                >
                </x-admin::tinymce>
            @endif

            <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>

        @break

    @case('date')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <FloatLabel variant="on"
            >
                <DatePicker
                    v-bind="field"
                   {{-- :modelValue="field.value"
                    @update:modelValue="field.onChange($event)" --}}
                    showIcon
                    fluid
                    dateFormat="yy/mm/dd"

                    iconDisplay="input"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

            <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>

        @break

    @case('datetime')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <FloatLabel variant="on"
            >
                <DatePicker
                    v-bind="field"
                    showIcon
                    showTime
                    hourFormat="12"
                    fluid
                    iconDisplay="input"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

           <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>
        @break

    @case('time')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <FloatLabel variant="on"
            >
                <DatePicker
                    v-bind="field"
                    showIcon
                    timeOnly
                    fluid
                    hourFormat="12"
                    iconDisplay="input"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

            <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>
        @break

    @case('select')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules' ]) }}
        >
            <FloatLabel variant="on"
            >
                <Select
                    :modelValue="field.value"
                    @update:modelValue="field.onChange($event)"
                    fluid
                    class="text-(--text-muted)"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

            <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>

        @break

    @case('multiselect')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <FloatLabel variant="on"
            >
                <MultiSelect
                    :modelValue="field.value"
                    @update:modelValue="field.onChange($event)"
                    fluid
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
                />

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>

            <x-admin::form.control-group.error  {{ $attributes->only(['name', ':name']) }} />
        </v-field>

        @break

    @case('checkbox')
        <v-field
            v-slot="{ field }"
            type="checkbox"
            class="hidden"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
            <div class="flex items-center gap-2">
                <Checkbox
                    :modelValue="field.checked"
                    @update:modelValue="field.onChange($event)"
                    binary
                    {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
                />

                <v-checked-handler
                    :field="field"
                    {{ $attributes->only(['checked', ':checked']) }}
                >
                </v-checked-handler>

                @if ($attributes->hasAny([':label', 'label', ':for', 'for']))
                    <x-admin::form.control-group.label {{ $attributes->only([':label', 'label', ':for', 'for']) }} />
                @endif
            </div>
        </v-field>

        @break

    @case('radio')
        <v-field
            v-slot="{ field }"
            type="radio"
            class="hidden"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
            <div class="flex items-center gap-2">
                <RadioButton
                    {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
                />

                <v-checked-handler
                    :field="field"
                    {{ $attributes->only(['checked', ':checked']) }}
                >
                </v-checked-handler>

                @if ($attributes->hasAny([':label', 'label', ':for', 'for']))
                    <x-admin::form.control-group.label {{ $attributes->only([':label', 'label', ':for', 'for']) }} />
                @endif
            </div>

        </v-field>

        @break
    @case('switch')
        <v-field
            v-slot="{ field }"
            type="checkbox"
            class="hidden"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
            <div class="flex items-center gap-2">
                <ToggleSwitch
                    :modelValue="field.checked"
                    @update:modelValue="field.onChange($event)"
                    {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
                />

                <v-checked-handler
                    :field="field"
                    {{ $attributes->only(['checked', ':checked']) }}
                >
                </v-checked-handler>
            </div>

        </v-field>

        @break
    @case('custom')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules']) }}
            class="rounded-lg!"
        >
            <FloatLabel variant="on"
                {{ $attributes->only(['class'])->merge(['class' => '']) }}
            >
                {{ $slot }}

                <x-admin::form.control-group.label {{ $attributes->only(['label', ':label']) }} />
            </FloatLabel>
        </v-field>
@endswitch

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-checked-handler-template"
    >
    </script>

    <script type="module">
        adminVueApp.component('v-checked-handler', {
            template: '#v-checked-handler-template',

            props: ['field', 'checked'],

            mounted() {
                if (this.checked == '' || this.checked === undefined || ! this.checked || this.checked === null) {
                    this.field.checked = false;
                } else {
                    this.field.checked = true;
                }

                this.field.onChange();
            },
        });
    </script>
@endpushOnce
