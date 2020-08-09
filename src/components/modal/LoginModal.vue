<template>
    <div class="modal layout-table">
        <div class="layout-col"><img src="@/assets/images/branding/ts_logo_svg.svg"></div>
        <div class="layout-col">
            <div class="modal-header">
                <p>Anmelden</p>
                <button class="btn btn-icon btn-tertiary" @click="dismissModal(modal.id)"><img src="@/assets/images/icons/close.svg"></button>
            </div>
            <div class="modal-content">
                <app-message-box :message="error" type="error" v-if="error"></app-message-box>
                <p>Diese Seite dient der privaten Nutzung. Eine Anmeldung ist notwendig, um Zugriff für Unbefugte zu unterbinden.</p>

                <div class="form-group full">
                    <label for="input_username">Dein Benutzername:</label>
                    <input class="form-control" type="text" name="username" id="input_username" autocomplete="off" v-model="form.username">
                </div>
                <div class="form-group full">
                    <label for="input_password">Dein Passwort:</label>
                    <input class="form-control" type="password" name="password" id="input_password" autocomplete="off" @keyup.enter="enter" v-model="form.password">
                </div>
                <p>Du bleibst automatisch 7 Tage lang angemeldet</p>
            </div>
            <div class="modal-actions">
                <primary-loading-btn id="submit" text="Jetzt anmelden" @click="login" :disabled="!this.form.username || !this.form.password"></primary-loading-btn>
            </div>
        </div>
    </div>
</template>

<script>
import User from '@/models/user.js';

export default {
    props: ['modal'],
    data() {
        return {
            form: {},
            error: undefined
        }
    },
    methods: {
        enter() {
            document.getElementById('submit').click();
        },
        login(event,done) {
            this.error = undefined;

            setTimeout(() => {
                User.loginWithCredentials(this.form.username, this.form.password, (result) => {
                    done();

                    if(result.ok) {
                        this.dismissModal(this.modal.id);
                    } else {
                        if(result.message == 'not found' || result.message == 'wrong credentials') {
                            this.error = 'Benutzername und Passwort stimmen nicht überein.';
                            done();
                        } else {
                            this.error = 'Der Login-Service ist momentan nicht verfügbar.';
                        }
                    }
                });
            }, 500);
        }
    },
    mounted() {
        document.getElementById('input_username').focus();
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/forms.scss';

.form-group:first-of-type {
    margin-top: 2em;
}

.layout-table {
    display: table;
    padding-bottom: 0;
    min-height: 400px;
    width: 700px;
}
.layout-col {
    display: table-cell;
    vertical-align: top;

    &:first-of-type {
        position: relative;
        width: 180px;
        background-color: $colorPlaceholder;

        img {
            position: absolute;
            width: 64px;
            height: 64px;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
        }
    }

    &:last-of-type {
        padding-bottom: 2.5em;
    }
}

@media screen and (max-width: 840px) {
    .layout-table {
        display: block !important;
        width: 90%;
    }
    .layout-col {
        display: block !important;
        width: 100%;

        &:first-of-type {
            display: none !important;
        }

        img {
            width: 45px;
            height: 45px;
        }
    }
}
</style>