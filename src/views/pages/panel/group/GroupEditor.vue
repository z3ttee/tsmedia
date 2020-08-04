<template>
    <div class="content-control">
        <transition name="fade" mode="out-in">
            <app-loader v-if="loading" class="content-loader-block"></app-loader>
            <div v-else>
                <div class="fieldset" >
                    <h5>Informationen <small-loading-btn text="Speichern" @click="submit"></small-loading-btn></h5>
                    <div class="form-group" v-if="form.name != 'default'">
                        <label for="">Gruppenname</label>
                        <input type="text" class="form-control full" v-model="form.name">
                    </div>
                    <div class="form-group">
                        <label for="">Anzeigename</label>
                        <input type="text" class="form-control full" v-model="form.displayname">
                    </div>
                    <div class="form-group" v-if="form.name != 'default'">
                        <label for="">Hierarchie</label>
                        <input type="number" class="form-control full" min="0" max="1000" v-model="form.hierarchy">
                    </div>
                </div>
                <div class="fieldset" v-if="form.name != 'default'">
                    <h5>Berechtigungen</h5>
                    <div class="form-group full checkboxes">
                        <p>Webinterface</p>
                        <input type="checkbox" id="permission.panel" v-model="form.permissions" value="permission.panel"><label for="permission.panel">Erlaubt Zugriff auf das Webinterface<span></span></label>
                    </div>
                    <div class="form-group full checkboxes" v-if="form.permissions.includes('permission.panel')">
                        <p>Benutzerverwaltung</p>
                        <input type="checkbox" id="permission.users" v-model="form.permissions" value="permission.users"><label for="permission.users">Erlaubt Zugriff auf Benutzerverwaltung<span></span></label>
                        <div v-if="form.permissions.includes('permission.users')">
                            <input type="checkbox" id="permission.users.create" v-model="form.permissions" value="permission.users.create"><label for="permission.users.create">Erlaubt es, Benutzer zu erstellen<span></span></label>
                        </div>
                        <div v-if="form.permissions.includes('permission.users')">
                            <input type="checkbox" id="permission.users.edit" v-model="form.permissions" value="permission.users.edit"><label for="permission.users.edit">Erlaubt es, Benutzer zu bearbeiten<span></span></label>
                        </div>
                        <div v-if="form.permissions.includes('permission.users')">
                            <input type="checkbox" id="permission.users.delete" v-model="form.permissions" value="permission.users.delete"><label for="permission.users.delete">Erlaubt es, Benutzer zu löschen<span></span></label>
                        </div>
                    </div>
                    <div class="form-group full checkboxes" v-if="form.permissions.includes('permission.panel')">
                        <p>Gruppenverwaltung</p>
                        <input type="checkbox" id="permission.groups" v-model="form.permissions" value="permission.groups"><label for="permission.groups">Erlaubt Zugriff auf Gruppenverwaltung<span></span></label>
                        <div v-if="form.permissions.includes('permission.groups')">
                            <input type="checkbox" id="permission.groups.create" v-model="form.permissions" value="permission.groups.create"><label for="permission.groups.create">Erlaubt es, Gruppen zu erstellen<span></span></label>
                        </div>
                        <div v-if="form.permissions.includes('permission.groups')">
                            <input type="checkbox" id="permission.groups.edit" v-model="form.permissions" value="permission.groups.edit"><label for="permission.groups.edit">Erlaubt es, Gruppen zu bearbeiten<span></span></label>
                        </div>
                        <div v-if="form.permissions.includes('permission.groups')">
                            <input type="checkbox" id="permission.groups.delete" v-model="form.permissions" value="permission.groups.delete"><label for="permission.groups.delete">Erlaubt es, Gruppen zu löschen<span></span></label>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>

export default {
    data() {
        return {
            form: {
                hierarchy: 0,
                permissions: []
            },
            createGroup: false,
            loading: true,
            groupID: undefined
        }
    },
    methods: {
        submit(event,done) {
            if(this.createGroup) {
                if(!this.form.name && !this.form.displayname) {
                    this.showNotice({content: 'Bitte fülle Name und Displayname aus!',type: 'error'});
                    done();
                    return
                }

                var data = new FormData();
                data.append('name', this.form.name);
                data.append('displayname', this.form.displayname);
                data.append('hierarchy', this.form.hierarchy);
                data.append('permissions', JSON.stringify(this.form.permissions));

                this.$http.post('group/', data).then((response) => {
                    if(response.data.status.code == 200) {
                        this.showNotice({
                            content: 'Die Gruppe wurde erfolgreich erstellt',
                            type: 'success'
                        });
                        this.$router.push({name: 'PanelGroupIndex'});
                    } else {
                        var message = response.data.status.message;

                        if(message == 'name already exists') {
                            this.showNotice({content: 'Der angegebene Gruppenname existiert bereits.',type: 'error'});
                        } else {
                            this.showNotice({content: 'Die Gruppe konnte nicht erstellt werden.',type: 'error'});
                        }
                    }
                }).catch((error) => {
                    console.log(error)
                    this.showModal('info', { content: 'Die Gruppe konnte nicht erstellt werden. Derzeit ist der Service nicht erreichbar' });
                }).finally(() => {
                    done();
                });
            } else {
                // Update user
                var form = new FormData();

                if(this.form.name && this.form.name != 'default') form.append('name', this.form.name);
                if(this.form.displayname) form.append('displayname', this.form.displayname);
                if(this.form.permissions && this.form.name != 'default') form.append('permissions', JSON.stringify(this.form.permissions));
                if(this.form.hierarchy && this.form.name != 'default') form.append('hierarchy', this.form.hierarchy);

                const query = new URLSearchParams(form);

                this.$http.put('group/'+this.groupID, query.toString()).then((response) => {
                    if(response.data.status.code == 200) {
                        this.showNotice({content: 'Gruppe aktualisiert',type: 'success'});
                        this.$router.push({name: 'PanelGroupIndex'});
                    } else {
                        console.log(response);
                        var message = response.data.status.message;
                        if(message == 'nothing to update') {
                            this.showNotice({content: 'Gruppe nicht aktualisiert: Keine Veränderung', type: 'error'});
                        } else {
                            this.showNotice({content: 'Die Gruppe konnte nicht aktualisiert werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
                        }
                    }
                }).catch((error) => {
                    console.log(error);
                    this.showNotice({content: 'Die Gruppe konnte nicht aktualisiert werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
                }).finally(() => {
                    done();
                })
            }
        }
    },
    validations: {
        username: {

        }
    },
    created() {
        var groupID = this.$route.params.id;
        this.createGroup = groupID == 'new';

        if(groupID != 'new') {
            // Load user data to display
            this.$http.get('group/'+groupID).then((response) => {
                if(response.data.status.code == 200) {
                    this.loading = false;
                    var data = response.data.data;

                    this.form.name = data.name;
                    this.form.displayname = data.displayname;
                    this.form.permissions = data.permissions;
                    this.form.hierarchy = data.hierarchy;
                    this.groupID = data.id;
                } else {
                    this.showNotice({content: 'Die Gruppe konnte nicht gefunden werden.',type: 'error'});
                }
            }).catch((error) => {
                console.log(error);
                this.showNotice({content: 'Die Gruppe konnte nicht geladen werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
            })
        } else {
            this.loading = false;
        }
    }
}
</script>

<style lang="scss">
@import '@/assets/scss/forms.scss';

.form-group {
    width: 350px !important;
}
</style>