<template>
    <div>
        <div class="interface-head">
            <h2>Gruppenübersicht</h2>
            <hr class="interface large">
        </div>

        <app-table-view :columns="['Gruppe', 'Hierarchie', 'Aktionen']" :dataset="groups" :loading="loading" @page="getData" @select="selectAll" @delete="remove">
            <tr v-for="group in groups.entries" :key="group.id">
                <td><input class="select" type="checkbox" :value="group.id" v-model="groups.selected[group.id]"></td>
                <td>
                    <div class="profile-info">
                        {{ group.name }}
                        <span>{{ group.id }}</span>
                    </div>
                </td>
                <td>
                    {{ group.hierarchy }}
                </td>
                <td>
                    <app-button class="btn btn-light" @clicked="edit" :payload="group.id" v-if="group.hierarchy <= $store.state.user.hierarchy">Bearbeiten</app-button>
                    <app-button class="btn btn-accent" @clicked="remove" :payload="group.id" v-if="group.hierarchy <= $store.state.user.hierarchy">Löschen</app-button>
                </td>
            </tr>
        </app-table-view>
    </div>
</template>

<script>
import AppTableView from '@/components/table/AppTableView.vue'

export default {
    components: {
        AppTableView
    },
    data() {
        return {
            loading: true,
            groups: {
                selected: {},
                entries: []
            },
        }
    },
    methods: {
        edit(event, done, id) {
            this.$router.push({name: 'panelGroupsEditor', params: {id}})
        },
        remove(event, done, data) {
            if(data == 'selected') {
                var entries = this.groups.entries.filter((element) => this.groups.selected[element.id])
                var ids = entries.map((element) => element.id)

                this.$api.delete('group/?byIDs='+JSON.stringify(ids), {}).then(() => {
                    for(var entry of entries) {
                        var index = this.groups.entries.indexOf(entry)
                        this.groups.entries.splice(index, 1)
                        delete this.groups.selected[entry.id]
                    }
                    this.$toast.success('Ausgewählte Einträge gelöscht')
                }).finally(() => {
                    done()
                })
            } else {
                var group = this.groups.entries.filter((element) => element.id == data)[0]
                var index = this.groups.entries.indexOf(group)

                this.$api.delete('group/'+data, {}).then(() => {
                    this.groups.entries.splice(index, 1)
                    this.$toast.success('Gruppe ['+group.name+'] gelöscht')
                }).finally(() => {
                    done()
                })
            }
        },
        selectAll(checked) {
            var ids = this.groups.entries.map((element) => {
                return element.id
            })

            for(var id of ids) {
                this.groups.selected[id] = checked
            }
        },
        getData(offset = 0, limit = 1, done){
            this.loading = true
            this.groups = {
                selected: {},
                entries: []
            }

            this.$api.get('group/all/?offset='+offset+'&limit='+limit).then((data) => {
                this.groups = {...this.groups, ...data}
            }).finally(() => {
                this.loading = false
                done()
            })
        }
    },
    mounted() {
        /*this.$api.get('group/all/').then((data) => {
            this.groups = data.entries

            var ids = []
            for(var group of data.entries) {
                if(group.permissionGroup != '*') ids.push(group.permissionGroup)
            }
        }).finally(() => {
            this.loading = false
        })*/
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

.profile-picture {
    display: inline-block;
    width: 38px;
    height: 38px;
    vertical-align: middle;
    background: $colorPlaceholder;
    border-radius: 50%;
}
.profile-info {
    padding-left: 1em;
    display: inline-block;
    vertical-align: middle;

    span {
        display: block;
        font-size: 0.8em;
        letter-spacing: 0.3px;
        opacity: 0.3;
    }
}

</style>