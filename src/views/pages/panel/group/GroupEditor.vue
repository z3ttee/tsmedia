<template>
    <div class="content-control">
        <transition name="fade" mode="out-in">
            <app-loader v-if="loading" class="content-loader-block"></app-loader>
            <div v-else>
                <div class="fieldset" >
                    <h5>Informationen <small-loading-btn text="Speichern" @click="submit"></small-loading-btn></h5>
                    <div class="form-group">
                        <label for="">Gruppenname</label>
                        <input type="text" class="form-control full" v-model="form.name">
                    </div>
                    <div class="form-group">
                        <label for="">Anzeigename</label>
                        <input type="text" class="form-control full" v-model="form.displayname">
                    </div>
                </div>
                <div class="fieldset" >
                    <h5>Berechtigungen</h5>
                    <div class="form-group">
                        <label for="">Gruppenname</label>
                        <input type="checkbox" id="" v-model="form.name">
                    </div>
                    <div class="form-group">
                        <label for="">Anzeigename</label>
                        <input type="text" class="form-control full" v-model="form.displayname">
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
            form: {},
            createGroup: false,
            loading: true,
            groupID: undefined
        }
    },
    methods: {
        submit(event,done) {
            if(this.createGroup) {
                if(!this.form.name && !this.form.displayname) {
                    this.showNotice({content: 'Bitte fülle alle Felder aus!',type: 'error'});
                    done();
                    return
                }

                var data = new FormData();
                data.append('name', this.form.name);
                data.append('displayname', this.form.displayname);

                this.$http.post('user/', data).then((response) => {
                    if(response.data.status.code == 200) {
                        this.showNotice({
                            content: 'Der Benutzer wurde erfolgreich erstellt',
                            type: 'success'
                        });
                        this.$router.push({name: 'PanelUserIndex'});
                    } else {
                        var message = response.data.status.message;

                        if(message == 'name already exists') {
                            this.showNotice({
                                content: 'Der angegebene Benutzername existiert bereits.',
                                type: 'error'
                            });
                        } else {
                            this.showModal('info', { content: 'Der Benutzer konnte nicht erstellt werden.' });
                        }
                    }
                }).catch((error) => {
                    console.log(error)
                    this.showModal('info', { content: 'Der Benutzer konnte nicht erstellt werden. Derzeit ist der Service nicht erreichbar' });
                }).finally(() => {
                    done();
                });
            } else {
                // Update user
                var form = new FormData();

                form.append('id', this.userID);
                if(this.form.username) form.append('name', this.form.username);
                if(this.form.password) form.append('password', this.form.password);
                if(this.form.group) form.append('group', this.form.group);

                const query = new URLSearchParams(form);

                this.$http.put('user/', query.toString()).then((response) => {
                    if(response.data.status.code == 200) {
                        this.showNotice({content: 'Benutzer aktualisiert',type: 'success'});
                        this.$router.push({name: 'PanelUserIndex'});
                    } else {
                        var message = response.data.status.message;
                        if(message == 'nothing to update') {
                            this.showNotice({content: 'Benutzer nicht aktualisiert: Keine Veränderung',type: 'error'});
                        } else {
                            this.showNotice({content: 'Der Benutzer konnte nicht aktualisiert werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
                        }
                    }
                }).catch((error) => {
                    console.log(error);
                    this.showNotice({content: 'Der Benutzer konnte nicht aktualisiert werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
                })
            }
        }
    },
    validations: {
        username: {

        }
    },
    mounted() {
        var groupID = this.$route.params.id;
        this.createGroup = groupID == 'new';

        console.log(this.createUser);

        if(groupID != 'new') {
            // Load user data to display
            this.$http.get('group/'+groupID).then((response) => {
                if(response.data.status.code == 200) {
                    this.loading = false;
                    var data = response.data.data;

                    this.form.username = data.name;
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