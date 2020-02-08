<template>
    <b-alert
        :show="dismissCountDown"
        :max="countDown"
        dismissible
        :variant="variant"
        @dismissed="hideAlert"
        @dismiss-count-down="countDownChanged"
    >
        <h4>{{ title }}</h4>

        <p>{{ message }}</p>

        <b-progress
            v-if="countDown"
            variant="warning"
            :max="countDown"
            :value="dismissCountDown"
            height="4px"
        />
    </b-alert>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import { BAlert, BProgress } from 'bootstrap-vue'

    @Component({
        components: {
            BAlert,
            BProgress
        },
        props: {
            variant: {
                type: String,
                default: 'danger'
            },
            countDown: Number,
            title: {
                type: String,
                default: 'Something went wrong:'
            },
            message: String
        }
    })
    export default class Alert extends Vue {
        private dismissCountDown: number = 0

        created () {
            this.dismissCountDown = this.$props.countDown || true
        }

        countDownChanged (dismissCountDown: number) {
            this.dismissCountDown = dismissCountDown
        }

        hideAlert () {
            this.$emit('hide')
            this.dismissCountDown = 0
        }
    }
</script>

<style>
    .alert {
        margin-top: 15px;
    }
</style>
