<template>
    <div class="content-control">
        <table>
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
                    <td></td>
                </tr>
            </tbody>
        </table>

        <p v-if="users.length == 0">Es konnten keine Einträge gefunden werden</p>
    </div>
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    data() {
        return {
            users: [],
            groups: []
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
        }
    },
    mounted() {
        this.$http.get('user/all/').then((response) => {
            console.log(response);
            if(response.data.status.code == 200) {
                this.users = response.data.data;
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
.loader {
    display: inline-block;
    height: 24px;
    width: 24px;
    vertical-align: middle;
}
</style>