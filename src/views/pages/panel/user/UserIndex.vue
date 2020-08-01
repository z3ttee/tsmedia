<template>
    <div class="content-control">
        <table class="interface-control">
            <thead>
                <tr>
                    <th>Benutzername</th>
                    <th>Gruppe</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.name }}</td>
                    <td><span v-if="groupExists(user.permissionGroup)" v-html="groupName(user.permissionGroup)"></span><app-loader class="loader" v-else></app-loader></td>
                    <td>
                        <small-loading-btn class="btn-icon" text="Bearbeiten"></small-loading-btn>
                        <small-loading-btn text="Löschen" @click="deleteUser" :identifier="user.id"></small-loading-btn>
                    </td>
                </tr>
            </tbody>
        </table>
        <app-loader class="loader large" v-if="loading"></app-loader>

        <p class="msg-box" v-if="users.length == 0 && !loading">Es konnten keine Einträge gefunden werden</p>
    </div>
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    data() {
        return {
            users: [],
            groups: [],
            loading: true
        }
    },
    components: {
        AppLoader
    },
    methods: {
        groupExists(groupID) {
            if(groupID == '*') {
                return true;
            }
            return this.groups.filter((element) => { if(element.id == groupID) return element }).length > 0;
        },
        groupName(groupID) {
            if(groupID == '*') {
                return 'root';
            }
            return this.groups.filter((element) => { if(element.id == groupID) return element })[0].name;
        },
        getUserIndex(userID) {
            var user = this.users.filter((element) => { if(element.id == userID) return element })[0];
            var index = this.users.indexOf(user);
            return index;
        },
        deleteUser(event, done, userID) {
            this.$http.delete('user/?id='+userID).then((response) => {
                if(response.data.status.code == 200) {
                    this.showNotice({
                        title: 'Aktion erfolgreich',
                        content: 'Der Benutzer wurde gelöscht',
                        type: 'success'
                    });
                    this.users.splice(this.getUserIndex(userID), 1);
                } else {
                    var message = response.data.status.message;

                    if(message == 'no permission') {
                        this.showNotice({ content: 'Keine Berechtigung', type: 'error' });
                    } else {
                        this.showNotice({
                            content: 'Der Benutzer konnte nicht gelöscht werden',
                            type: 'error'
                        });
                    }
                }
            }).catch((error) => {
                console.log(error);
                this.showNotice({
                    content: 'Der Benutzer konnte nicht gelöscht werden',
                    type: 'error'
                });
            }).finally(() => {
                done();
            });
        }
    },
    mounted() {
        this.$http.get('user/all/').then((response) => {
            console.log(response);
            if(response.data.status.code == 200) {
                this.users = response.data.data;
                this.loading = false;
            } else {
                if(response.data.status.message != 'not found') {
                    this.showNotice({ title: 'Ein Fehler ist aufgetreten', content: 'Die Services sind nicht erreichbar', type: 'error' });
                }
            }
        }).catch((error) => {
            console.log(error)
            this.showNotice({title: 'Nicht verfügbar', content: 'Die Services sind nicht erreichbar', type: 'error' });
        });
    },
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/tables.scss';

.loader {
    display: inline-block;
    height: 24px;
    width: 24px;

    &.large {
        display: block;
        width: 45px;
        height: 45px;
    }
}
</style>