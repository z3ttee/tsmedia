<template>
    <div>
        <h2>Benutzerübersicht</h2>
        <hr class="interface large">

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
                    <td><app-loader class="loader"></app-loader></td>
                    <td>
                        <app-button class="btn btn-light" text="Bearbeiten" @clicked="edit" :payload="user.id"></app-button>
                        <app-button class="btn btn-light" text="Bearbeiten"></app-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="msg-box" v-if="users.length == 0 && !this.loading">Keine Einträge gefunden.</p>
        <app-loader class="loader" v-if="loading"></app-loader>
    </div>
</template>

<script>
import Api from '@/models/api.js';

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
        delete() {
            //this.$router.push({name: 'panelUserIndex', params: {id}})
        }
    },
    mounted() {
        Api.get('user/all/').then((data) => {
            this.users = data
            console.log(data)
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