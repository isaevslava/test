<template>
    <b-card class="list-wrapper">
        <b-button-group class="control-panel">
            <b-button variant="info" @click="toggleMode">
                {{viewMode ? 'Edit' : 'View'}}
            </b-button>
            <SaveButton @click="onSave" :disabled="saveBtnLoading" :is-loading="saveBtnLoading"/>
        </b-button-group>

        <LoaderSpinner v-if="loading" size="large"/>

        <b-list-group v-else>
            <b-list-group-item v-for="(item, index) in items" :key="item.id">
                <b-form-input
                    v-model="item.title"
                    v-if="!viewMode"
                    @change="() => onChange(index)"
                    @keyup.backspace="e => !e.target.value && deleteItem(index)"
                />
                <span v-else>{{item.title}}</span>
            </b-list-group-item>

            <b-list-group-item v-if="!viewMode">
                <b-form-input v-model="addItemField" @keyup.enter="onAdd" :autofocus="true"/>
            </b-list-group-item>
        </b-list-group>

        <Alert v-if="error" :message="error" :count-down="10" @hide="hideAlert"/>
    </b-card>
</template>

<script lang="ts">
    import { Component, Vue } from 'vue-property-decorator'
    import VueResource from 'vue-resource'
    import { BCard, BListGroup, BListGroupItem, BButtonGroup, BButton, BFormInput } from 'bootstrap-vue'

    import SaveButton from './SaveButton.vue'
    import LoaderSpinner from './LoaderSpinner.vue'
    import Alert from './Alert.vue'
    import { IListItem } from '../types/ListItem'
    import { HttpResponse}  from 'vue-resource/types/vue_resource'

    Vue.use(VueResource)

    @Component({
        components: {
            BCard,
            BListGroup,
            BListGroupItem,
            BButtonGroup,
            BButton,
            BFormInput,
            SaveButton,
            LoaderSpinner,
            Alert
        }
    })
    export default class App extends Vue {
        viewMode: boolean = false
        addItemField: string = ''
        items: IListItem[] = []
        loading: boolean = true
        saveBtnLoading: boolean = false
        error: string = ''

        toDelete: number[] = []
        toUpdate: number[] = []

        created () {
            this.fetchData()
        }

        async fetchData () {
            try {
              const response = await this.$http.get('/api/items')
              this.items = response.data
            } catch (e) {
              this.error = e.message
              console.log('Error: ', e)
            }

            this.loading = false
        }

        toggleMode () {
            this.viewMode = !this.viewMode
        }

        hideAlert () {
            this.error = ''
        }

        async onSave () {
            this.saveBtnLoading = true
            const promises: PromiseLike<HttpResponse>[] = []

            this.items.map((item: IListItem) => {
                if (!item.id) {
                    promises.push(
                        this.$http
                            .post('/api/items', { title: item.title })
                            .then((response: HttpResponse) => {
                                item.id = response.data.id
                                return response
                            })
                    )
                }
                else if (this.toUpdate.includes(item.id)) {
                    promises.push(this.$http.put(`/api/items/${item.id}`, { title: item.title }))
                }
            })

            this.toDelete.map((id: number) => {
                promises.push(this.$http.delete(`/api/items/${id}`))
            })

            try {
                const response = await Promise.all(promises)
                this.toUpdate = []
                this.toDelete = []
                console.log('response', response)
            } catch (e) {
                this.error = e.statusText || e.status
            } finally {
                this.saveBtnLoading = false
            }
        }

        onAdd () {
            this.items.push({ title: this.addItemField })
            this.addItemField = ''
        }

        onChange (index: number) {
            const item = this.items[index]
            if (item && item.id && !this.toUpdate.includes(item.id)) {
                this.toUpdate.push(item.id)
            }
        }

        deleteItem (index: number) {
            const item = this.items[index]
            if (item.id && !this.toUpdate.includes(item.id)) {
                this.toDelete.push(item.id)
                if (this.toUpdate.includes(item.id)) {
                    const itemIndex = this.toUpdate.findIndex((i: number) => i === item.id)
                    this.toUpdate.splice(itemIndex, 1)
                }
            }
            this.items.splice(index, 1)
        }
    }
</script>

<style scoped lang="scss">
    .list-wrapper {
        position: relative;
        margin: auto;
        top: 60px;
        width: 650px;
        border: none;

        .control-panel {
            position: absolute;
            top: -20px;
            right: -1px;
            background-color: #fff;
            padding: 15px 20px 0;
            border: none;
            border-radius: 0.25rem;
            transform: translate(0, -50%);
            z-index: 1;

            .btn {
                outline: none;
                box-shadow: none;
            }
        }
    }
</style>
