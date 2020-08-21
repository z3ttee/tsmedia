<template>
    <div>
        <div class="interface-head">
            <h2>Gruppenübersicht</h2>
            <hr class="interface large">
        </div>
        
        <table class="interface-control">
            <thead>
                <tr>
                    <th>Informationen</th>
                    <th>Hierarchie</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="group in groups" :key="group.id">
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
                        <app-button class="btn btn-light" text="Bearbeiten" @clicked="edit" :payload="group.id"></app-button>
                        <app-button class="btn btn-accent" text="Löschen" @clicked="remove" :payload="group.id"></app-button>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="msg-box" v-if="groups.length == 0 && !this.loading">Keine Einträge gefunden.</p>
        <app-loader class="loader" v-if="loading"></app-loader>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            groups: []
        }
    },
    methods: {
        edit(event, done, id) {
            this.$router.push({name: 'panelGroupsEditor', params: {id}})
        },
        remove(event, done, id) {
            var group = this.groups.filter((element) => { if(element.id == id) return element })[0]
            var index = this.groups.indexOf(group)

            this.$api.delete('group/'+id, {}).then(() => {
                this.groups.splice(index, 1)
                this.$toast.success('Benutzer ['+group.name+'] gelöscht')
            }).finally(() => {
                done()
            })
        }
    },
    mounted() {
        this.$api.get('group/all/').then((data) => {
            this.groups = data

            var ids = []
            for(var group of data) {
                if(group.permissionGroup != '*') ids.push(group.permissionGroup)
            }
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