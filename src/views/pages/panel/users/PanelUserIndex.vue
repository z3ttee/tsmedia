<template>
    <div>
        <div class="interface-head">
            <h2>Benutzerübersicht</h2>
            <hr class="interface large">
        </div>
        
        <table class="interface-control">
            <thead>
                <tr>
                    <th>Informationen</th>
                    <th>Gruppe</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
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
                        <app-button class="btn btn-light" text="Bearbeiten" @clicked="edit" :payload="user.id"></app-button>
                        <app-button class="btn btn-accent" text="Löschen" @clicked="remove" :payload="user.id"></app-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="msg-box" v-if="users.length == 0 && !this.loading">Keine Einträge gefunden.</p>
        <app-loader class="loader" v-if="loading"></app-loader>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            users: [],
            groups: []
        }
    },
    methods: {
        edit(event, done, id) {
            this.$router.push({name: 'panelUsersEditor', params: {id}})
        },
        remove(event, done, id) {
            var user = this.users.filter((element) => { if(element.id == id) return element })[0]
            var index = this.users.indexOf(user)

            this.$api.delete('user/'+id, {}).then(() => {
                this.users.splice(index, 1)
                this.$toast.success('Benutzer ['+user.name+'] gelöscht')
            }).finally(() => {
                done()
            })
        },
        getGroupname(id) {
            if(id == '*') return 'root'

            var group = this.groups.filter((element) => { if(element.id == id) return element })[0] || {name: 'unknown'}
            return group.name
        },
    },
    mounted() {
        this.$api.get('user/all/').then((data) => {
            this.users = data

            this.$api.get('group/all/?ofIDs=[]&props=["name", "id"]', {}, false).then((data) => {
                this.groups = data
            })
        }).finally(() => {
            this.loading = false
        })
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