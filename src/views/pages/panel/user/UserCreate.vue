<template>
    <div class="content-control">
        <div class="fieldset">
            <h5>Benutzereinstellungen <small-loading-btn text="Speichern" :disabled="!form.username || !form.password" @click="submit"></small-loading-btn></h5>
            <div class="form-group">
                <label for="">Benutzername</label>
                <input type="text" class="form-control full" v-model="form.username">
            </div>
            <div class="form-group">
                <label for="">Passwort</label>
                <input type="text" class="form-control full" v-model="form.password">
            </div>
            <div :class="{'form-group': true, 'error': $v.username.$error}">
                <label for="">Berechtigungsgruppe</label>
                <app-spinner-input :items="[]" v-model="form.group"></app-spinner-input>
            </div>
        </div>
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
            form: {}
        }
    },
    methods: {
        submit(event,done) {
            var data = new FormData();
            data.append('name', this.form.username);
            data.append('password', this.form.password);

            this.$http.post('user/', data).then((response) => {
                console.log(response);
                if(response.data.status.code == 200) {
                    this.$router.push({name: 'PanelUserIndex'});
                } else {
                    var message = response.data.status.message;

                    if(message == 'name already exists') {
                        this.showModal('info', { title: 'Ein Fehler ist aufgetreten', content: 'Der Benutzername ist bereits vergeben' });
                    } else {
                        this.showModal('info', { title: 'Ein Fehler ist aufgetreten', content: 'Der Benutzer konnte nicht erstellt werden.' });
                    }
                }
            }).catch((error) => {
                console.log(error)
                this.showModal('info', { title: 'Ein Fehler ist aufgetreten', content: 'Der Benutzer konnte nicht erstellt werden. Derzeit ist der Service nicht erreichbar' });
            }).finally(() => {
                done();
            });
        }
    },
    validations: {
        username: {

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