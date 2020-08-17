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
                <tr>
                    <td>Default</td>
                    <td>def2</td>
                    <td><button class="btn btn-light">Bearbeiten</button><button class="btn btn-accent">Löschen</button></td>
                </tr>
            </tbody>
        </table>
        <p class="msg-box">Keine Einträge gefunden.</p>
        <app-loader class="loader"></app-loader>
    </div>
</template>

<script>
export default {
    
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

</style>