<template>
    <div class="loading-wrapper">
        <b-spinner style="width: 5rem; height: 5rem;" v-if="size === 'large'"/>

        <b-spinner small v-else-if="size === 'small'"/>

        <b-spinner v-else />
    </div>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { BSpinner } from 'bootstrap-vue'

    @Component({
        props: {
            size: String
        },
        components: {
            BSpinner
        }
    })
    export default class LoaderSpinner extends Vue {}
</script>

<style>
    .loading-wrapper {
        display: flex;
        width: 100%;
        justify-content: center;
    }
</style>
