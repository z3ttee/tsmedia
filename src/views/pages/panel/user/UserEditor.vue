<template>
    <div class="content-control">
        <transition name="fade" mode="out-in">
            <app-loader class="content-loader-block" v-if="loading"></app-loader>
            <div class="fieldset" v-else>
                <h5>Benutzereinstellungen 
                    <small-loading-btn text="Speichern" @click="submit"></small-loading-btn>
                </h5>
                <div class="form-group">
                    <label for="">Benutzername</label>
                    <input type="text" class="form-control full" v-model="form.username">
                </div>
                <div class="form-group">
                    <label for="">Passwort</label>
                    <input type="password" class="form-control full" v-model="form.password">
                </div>
                <div :class="{'form-group': true, 'error': $v.username.$error}">
                    <label for="">Berechtigungsgruppe</label>
                    <app-spinner-input :items="[]" v-model="form.group"></app-spinner-input>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import AppSpinnerInput from '@/components/input/SpinnerInput.vue';

export default {
    components: {
        AppSpinnerInput
    },
    data() {
        return {
            form: {},
            createUser: false,
            loading: true,
            userID: undefined
        }
    },
    methods: {
        submit(event,done) {
            if(this.createUser) {
                if(!this.form.username && !this.form.password) {
                    this.showNotice({content: 'Bitte fülle alle Felder aus!',type: 'error'});
                    done();
                    return
                }

                var data = new FormData();
                data.append('name', this.form.username);
                data.append('password', this.form.password);

                this.$http.post('user/', data).then((response) => {
                    if(response.data.status.code == 200) {
                        this.showNotice({ content: 'Der Benutzer wurde erfolgreich erstellt', type: 'success' });
                        this.$router.push({name: 'PanelUserIndex'});
                    } else {
                        var message = response.data.status.message;

                        if(message == 'name already exists') {
                            this.showNotice({ content: 'Der angegebene Benutzername existiert bereits.', type: 'error' });
                        } else {
                            this.showNotice({ content: 'Der Benutzer konnte nicht erstellt werden.', type: 'error' });
                        }
                    }
                }).catch((error) => {
                    console.log(error)
                    this.showNotice({ content: 'Der Benutzer konnte nicht erstellt werden. Derzeit ist der Service nicht erreichbar', type: 'error' });
                }).finally(() => {
                    done();
                });
            } else {
                // Update user
                var form = new FormData();

                if(this.form.username) form.append('name', this.form.username);
                if(this.form.password) form.append('password', this.form.password);
                if(this.form.group) form.append('group', this.form.group);

                const query = new URLSearchParams(form);

                this.$http.put('user/'+this.userID, query.toString()).then((response) => {
                    console.log(response);
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
    created() {
        var userID = this.$route.params.id;
        this.createUser = userID == 'new';

        if(userID != 'new') {
            // Load user data to display
            this.$http.get('user/'+userID).then((response) => {
                if(response.data.status.code == 200) {
                    this.loading = false;
                    var data = response.data.data;

                    this.form.username = data.name;
                    this.userID = data.id;
                } else {
                    this.showNotice({content: 'Der Benutzer konnte nicht gefunden werden.',type: 'error'});
                }
            }).catch((error) => {
                console.log(error);
                this.showNotice({content: 'Der Benutzer konnte nicht geladen werden. Derzeit ist der Service nicht erreichbar',type: 'error'});
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