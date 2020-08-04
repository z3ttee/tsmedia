<template>
    <div class="content-control">
        <table class="interface-control">
            <thead>
                <tr>
                    <th>Gruppenname</th>
                    <th>Hierarchie</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td><span v-html="group.displayname"></span> <span class="subtext" v-html="'('+group.name+')'" v-if="group.displayname != group.name"></span></td>
                    <td><span v-html="group.hierarchy"></span></td>
                    <td>
                        <small-loading-btn class="btn-icon" text="Bearbeiten" @click="$router.push({name: 'PanelGroupEditor', params: {id: group.id}})"></small-loading-btn>
                        <small-loading-btn text="Löschen" @click="deleteGroup" :identifier="group.id"></small-loading-btn>
                    </td>
                </tr>
            </tbody>
        </table>
        <app-loader class="loader large" v-if="loading"></app-loader>

        <p class="msg-box" v-if="groups.length == 0 && !loading">Es konnten keine Einträge gefunden werden</p>
    </div>
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    data() {
        return {
            groups: [],
            loading: true
        }
    },
    components: {
        AppLoader
    },
    methods: {
        getGroupIndex(groupID) {
            var group = this.groups.filter((element) => { if(element.id == groupID) return element })[0];
            var index = this.groups.indexOf(group);
            return index;
        },
        deleteGroup(event, done, groupID) {
            this.$http.delete('group/'+groupID).then((response) => {
                if(response.data.status.code == 200) {
                    this.showNotice({content: 'Die Gruppe wurde gelöscht',type: 'success'});
                    this.groups.splice(this.getGroupIndex(groupID), 1);
                } else {
                    var message = response.data.status.message;

                    if(message == 'no permission') {
                        this.showNotice({ content: 'Keine Berechtigung', type: 'error' });
                    } else {
                        this.showNotice({content: 'Die Gruppe konnte nicht gelöscht werden',type: 'error'});
                    }
                }
            }).catch((error) => {
                console.log(error);
                this.showNotice({content: 'Die Gruppe konnte nicht gelöscht werden', type: 'error'});
            }).finally(() => {
                done();
            });
        }
    },
    mounted() {
        this.$http.get('group/all/').then((response) => {
            if(response.data.status.code == 200) {
                this.groups = response.data.data;
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