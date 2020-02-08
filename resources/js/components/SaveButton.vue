<template>
    <b-button variant="success" @click="$emit('click')" :disabled="disabled">
        <LoaderSpinner v-if="isLoading" size="small"/>
        <span v-else>Save</span>
    </b-button>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { BButton } from 'bootstrap-vue'
    import LoaderSpinner from './LoaderSpinner.vue'

    @Component({
        components: {
            BButton,
            LoaderSpinner
        },
        props: {
            disabled: Boolean,
            isLoading: Boolean
        }
    })
    export default class SaveButton extends Vue {}
</script>
