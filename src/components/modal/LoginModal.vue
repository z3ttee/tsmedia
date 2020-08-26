<template>
    <div class="modal modal-table">
        <div class="modal-col"><img src="@/assets/images/branding/ts_logo_svg.svg"></div>
        <div class="modal-col">
            <div class="modal-header">
                <p>Anmelden</p>
                <button class="btn btn-icon btn-tertiary" @click="dismissModal(modal.id)"><img src="@/assets/images/icons/close.svg"></button>
            </div>
            <div class="modal-content">
                <form>
                    <div class="error-box" v-if="error" v-html="error"></div>
                    <p>Diese Seite dient der privaten Nutzung. Eine Anmeldung ist notwendig, um Zugriff für Unbefugte zu unterbinden.</p>

                    <div class="form-group">
                        <label for="input_name">Dein Benutzername:</label>
                        <input class="full" type="text" name="username" id="input_name" autocomplete="off" v-model="form.name">
                    </div>
                    <div class="form-group">
                        <label for="input_password">Dein Passwort:</label>
                        <input class="full" type="password" name="password" id="input_password" autocomplete="off" @keyup.enter="enter" v-model="form.password">
                    </div>
                    <p>Du bleibst automatisch 7 Tage lang angemeldet</p>
                </form>
            </div>
            <div class="modal-actions">
                <app-button class="btn btn-accent btn-full" id="submit" @clicked="login" :disabled="!form.name || !form.password">Jetzt anmelden</app-button>
            </div>
        </div>
    </div>
</template>

<script>
import User from '@/models/user.js';

export default {
    props: {
        modal: Object
    },
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

            User.loginWithCredentials(this.form.name, this.form.password, (result) => {
                done();
                if(result.ok) {
                    this.dismissModal();
                    if(this.modal.redirect) {
                        this.$router.push(this.modal.redirect)
                    }
                } else {
                    this.error = 'Benutzername und Passwort stimmen nicht überein oder der Service ist derzeit nicht vefügbar.';
                }
            });
        }
    },
    mounted() {
        document.getElementById('input_name').focus();
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.form-group:first-of-type {
    margin-top: 2em;
}

.error-box {
    margin-bottom: 2em;
}

.modal-table {
    min-height: 400px;
    width: 700px;

    .modal-col {
        width: initial;

        &:first-of-type {
            position: relative;
            width: 180px;
            background-color: $colorPrimary;

            img {
                position: absolute;
                width: 64px;
                height: 64px;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
            }
        }
    }
}

.modal-header {
    background: none;
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