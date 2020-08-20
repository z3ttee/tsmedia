<template>
    <div>
        <h2>Benutzerübersicht</h2>
        <hr class="interface large">

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
                    <td>
                        <div class="profile-picture"></div>
                        <div class="profile-info">
                            Benutzername
                            <span>ID</span>
                        </div>
                    </td>
                    <td><app-loader class="loader"></app-loader></td>
                    <td><button class="btn btn-light">Bearbeiten</button><button class="btn btn-accent">Löschen</button></td>
                </tr>
            </tbody>
        </table>
        <p class="msg-box" v-if="users.length == 0">Keine Einträge gefunden.</p>
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