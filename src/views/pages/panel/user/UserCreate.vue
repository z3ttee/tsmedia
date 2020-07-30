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
            <div class="form-group">
                <label for="">Berechtigungsgruppe</label>
                <select>
                    <option selected>Defsult</option>
                </select>
                <input type="text" class="form-control full">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {}
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/forms.scss';

.form-group {
    width: 350px !important;
}
</style>