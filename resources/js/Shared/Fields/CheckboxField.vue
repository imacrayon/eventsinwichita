<template>
    <BaseField :name="name" :helpText="helpText" :errors="errors">
        <label class="inline-flex items-center">
            <input
                :id="name"
                :name="name"
                :checked="checked"
                type="checkbox"
                class="form-checkbox"
                v-bind="inputAttributes"
            />
            <span class="ml-2">{{ label }}</span>
        </label>
    </BaseField>
</template>

<script>
import BaseField from './BaseField'
import FormField from '@/mixins/FormField'

export default {
    components: {
        BaseField,
    },

    mixins: [FormField],

    computed: {
        checked() {
            return Boolean(this.value)
        },
    },
}
</script>
