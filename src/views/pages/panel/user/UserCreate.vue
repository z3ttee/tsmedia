<template>
    <div class="content-control">
        <div class="fieldset">
            <h5>Benutzereinstellungen<button class="btn btn-primary btn-s" :disabled="!form.username">Speichern</button></h5>
            <div class="form-group">
                <label for="">Benutzername</label>
                <input type="text" class="form-control full">
            </div>
            <div class="form-group">
                <label for="">Passwort</label>
                <input type="text" class="form-control full">
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
    watch: {
        'form.group'(val) {
            console.log(val)
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