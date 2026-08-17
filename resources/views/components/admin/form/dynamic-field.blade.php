<div :class="(f?.is_full_width ?? false) ? 'md:col-span-2' : ''" {{ $attributes->only('class') }}  >
    <!-- Text -->
    <template v-if="f && f?.type == 'text' && ($root.areHideFields[f.code] ?? false) !== true">
        <x-admin::form.control-group>
            <x-admin::form.control-group.control type="text" id="f?.id" ::name="f.code"
                ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules" ::placeholder="f?.placeholder"
                v-model="$root.dynamicModelFields[f.code]" ::label="f?.label" />
        </x-admin::form.control-group>
    </template>

    <!-- Date -->
    <template v-if="f && f?.type == 'date' && ($root.areHideFields[f.code] ?? false) !== true">
        <x-admin::form.control-group>
            <x-admin::form.control-group.control type="date" id="f?.id" ::name="f.code"
                ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules" ::placeholder="f?.placeholder"
                v-model="$root.dynamicModelFields[f.code]" ::label="f?.label" />
        </x-admin::form.control-group>
    </template>

    <!-- Select -->
    <template v-if="f && f?.type == 'select' && ($root.areHideFields[f.code] ?? false) !== true">
        <x-admin::form.control-group>
            <x-admin::form.control-group.control
                type="select"
                ::id="f?.id"
                ::name="f.code"
                filter
                ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules"
                ::placeholder="f?.placeholder"
                v-model="$root.dynamicModelFields[f.code]"
                ::label="f?.label"
                ::optionLabel="f?.option_label_key ?? 'label'"
                ::optionValue="f?.option_value_key ?? 'value'"
                ::options="f?.option_type == 'static' ? (f?.options ?? []) : (responseData?.[f?.dynamic_option_key] ?? [])"
            />
        </x-admin::form.control-group>
    </template>

    <!-- Textarea -->
    <template v-if="f && f?.type == 'textarea' && ($root.areHideFields[f.code] ?? false) !== true">
        <x-admin::form.control-group class="-mb-0!" >
            <x-admin::form.control-group.control type="textarea" id="f?.id" ::name="f.code"
                ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules" ::placeholder="f?.placeholder"
                v-model="$root.dynamicModelFields[f.code]" ::label="f?.label" />
        </x-admin::form.control-group>
    </template>

    <!-- File -->
    <template v-if="f && f?.type == 'file' && ($root.areHideFields[f.code] ?? false) !== true">
        <div class="flex gap-2 flex-col ">
            <x-admin::form.control-group.label ::label="f?.label" />

           <x-admin::form.control-group class="-mb-0!" >
                <x-admin::form.control-group.control type="file" id="f?.id" ::name="f.code"
                    ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules" ::placeholder="f?.placeholder"
                    v-model="$root.dynamicModelFields[f.code]"  />
            </x-admin::form.control-group>
        </div>

        <x-admin::form.control-group.error ::name="f.code" />
    </template>

    <!-- Radio -->
    <template v-if="f && f?.type == 'radio' && ($root.areHideFields[f.code] ?? false) !== true">
        <!-- Radio -->
        <x-admin::form.control-group>
            <div class="flex gap-1 flex-col">
                <x-admin::form.control-group.label ::label="f?.label" class="" />

                <div class="flex gap-4 ">
                    <template v-for="(radio_option, radio_idx) in (
                        f.option_type == 'static' ? (f?.options ?? []) : (
                            responseData?.[f?.dynamic_option_key] ?? []
                        )
                    )" :key="f.code">
                        <x-admin::form.control-group.control type="radio" inputId="f?.id" ::name="f.code"
                            ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules" ::placeholder="f?.placeholder"
                            v-model="$root.dynamicModelFields[f.code]" ::label="radio_option.label"
                            ::value="radio_option.value" checked="" />
                    </template>
                </div>

            </div>
            <x-admin::form.control-group.error ::name="f.code" />
        </x-admin::form.control-group>
    </template>

    <!-- Switch -->
    <template v-if="f && f?.type == 'switch' && ($root.areHideFields[f.code] ?? false) !== true">
        <div
            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-100 dark:border-gray-800/80 mt-2">
            <div>
                <span class="block text-sm font-semibold text-gray-900 dark:text-white">@{{ f?.label }}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">@{{ f?.short_description }}</span>
            </div>
            <div class="flex items-center gap-2">
                {{-- <ToggleSwitch v-model="$root.dynamicModelFields[f.code]" ::name="f.code" /> --}}
                {{-- @{{ $root.dynamicModelFields[f.code] ? true : false }} --}}
               <x-admin::form.control-group.control type="switch" id="f?.id" ::name="f.code" ::checked="$root.dynamicModelFields[f.code] ? true : false"
                ::rules="$root.dynamicValidations[f.code] ?? f?.default_rules"
                v-model="$root.dynamicModelFields[f.code]" />

                <span class="text-xs font-semibold"
                    :class="$root.dynamicModelFields[f.code] ? 'text-green-500' : 'text-gray-400'">
                    @{{ $root.runDynamicHandler(f, $root.dynamicModelFields[f.code]) }}
                </span>
            </div>
        </div>
    </template>
</div>
