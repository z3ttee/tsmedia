<template>
    <div class="section">
        <div class="interface-head">
            <h2>Benutzerübersicht</h2>
            <hr class="interface large">
        </div>

        <app-table-view :columns="['Benutzer', 'Gruppe', 'Aktionen']" :dataset="users" :loading="loading" @page="getData" @select="selectAll" @delete="remove">
            <tr v-for="(user) in users.entries" :key="user.id">
                <td><input class="select" type="checkbox" :value="user.id" v-model="users.selected[user.id]"></td>
                <td>
                    <div class="profile-picture"></div>
                    <div class="profile-info">
                        {{ user.name }}
                        <span>{{ user.id }}</span>
                    </div>
                </td>
                <td>
                    <app-loader class="loader" v-if="groups.length == 0"></app-loader>
                    <span v-html="getGroupname(user.permissionGroup)" v-else></span> 
                </td>
                <td>
                    <app-button class="btn btn-light" @clicked="edit" :payload="user.id">Bearbeiten</app-button>
                    <app-button class="btn btn-accent" @clicked="remove" :payload="user.id">Löschen</app-button>
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
            users: {
                selected: {},
                entries: []
            },
            groups: []
        }
    },
    methods: {
        edit(event, done, id) {
            this.$router.push({name: 'panelUsersEditor', params: {id}})
        },
        remove(event, done, data) {
            if(data == 'selected') {
                var entries = this.users.entries.filter((element) => this.users.selected[element.id])
                var ids = entries.map((element) => element.id)

                this.$api.delete('user/?byIDs='+JSON.stringify(ids), {}).then(() => {
                    for(var entry of entries) {
                        var index = this.users.entries.indexOf(entry)
                        this.users.entries.splice(index, 1)
                        delete this.users.selected[entry.id]
                    }
                    this.$toast.success('Ausgewählte Einträge gelöscht')
                }).finally(() => {
                    done()
                })
            } else {
                var user = this.users.entries.filter((element) => element.id == data)[0]
                var index = this.users.entries.indexOf(user)

                this.$api.delete('user/'+data, {}).then(() => {
                    this.users.entries.splice(index, 1)
                    this.$toast.success('Benutzer ['+user.name+'] gelöscht')
                }).finally(() => {
                    done()
                })
            }
        },
        getGroupname(id) {
            if(id == '*') return 'root'

            var group = this.groups.filter((element) => element.id == id)[0] || {name: 'unknown'}
            return group.name
        },
        selectAll(checked) {
            var ids = this.users.entries.map((element) => {
                return element.id
            })

            for(var id of ids) {
                this.users.selected[id] = checked
            }
        },
        getData(offset = 0, limit = 1, done){
            this.loading = true
            this.users = {
                selected: {},
                entries: []
            }

            this.$api.get('user/all/?offset='+offset+'&limit='+limit).then((data) => {
                this.users = {...this.users, ...data}

                var ids = []
                for(var user of data.entries) {
                    if(user.permissionGroup != '*') ids.push(user.permissionGroup)
                }

                var url = 'group/all/'+(ids.length > 0 ? '?ofIDs='+JSON.stringify(ids)+'' : '')+'&props=["name", "id"]'
                this.$api.get(url, {}, false).then((data) => {
                    this.groups = data.entries
                })
            }).finally(() => {
                this.loading = false
                done()
            })
        }
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