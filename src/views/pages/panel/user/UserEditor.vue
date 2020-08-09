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
                    this.$toast.error('Bitte fülle alle Felder aus!');
                    done();
                    return
                }

                var data = new FormData();
                data.append('name', this.form.username);
                data.append('password', this.form.password);

                this.$api.post('user/', {done, query: data}).then(() => {
                    this.$toast.success('Der Benutzer wurde erfolgreich erstellt');
                    this.$router.push({name: 'PanelUserIndex'});
                })
            } else {
                // Update user
                var form = new FormData();

                if(this.form.username) form.append('name', this.form.username);
                if(this.form.password) form.append('password', this.form.password);
                if(this.form.group) form.append('group', this.form.group);

                const query = new URLSearchParams(form);

                this.$api.put('user/'+this.userID, {done: () => {done(); this.loading = false;}, query}).then(() => {
                    this.$toast.success('Benutzer aktualisiert');
                    this.$router.push({name: 'PanelUserIndex'});
                });
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
            this.$api.get('user/'+userID, {done: () => {this.loading = false}}).then(data => {
                this.form.username = data.name;
                this.userID = data.id;
            });
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