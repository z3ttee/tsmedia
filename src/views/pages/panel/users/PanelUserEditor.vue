<template>
    <div class="section">
        <div class="interface-head">
            <h2>
                {{ editMode ? 'Benutzer bearbeiten' : 'Benutzer erstellen' }}
                <app-button class="btn btn-success" @clicked="submit">Speichern</app-button>
            </h2>
            <hr class="interface large">
        </div>

        <app-loader v-if="loading"></app-loader>
        <div class="section-box" v-if="!loading">
            <div class="interface-description">
                <h4>Informationen</h4>
                <p>Stelle hier allgemeine Benutzerinformationen ein, wie Name und Gruppe.</p>
            </div>

            <div :class="{'form-group half': true, 'error': user.name.error}">
                <label for="input_name">Benutzername <span>{{ editMode ? '(Optional)' : '(Required)' }}</span></label>
                <input class="full" type="text" name="input_name" id="input_name" v-model="user.name.model">
                <ul>
                    <li class="form-requirement">Min: 3, Max: 16</li>
                </ul>
                <p class="form-error" v-if="user.name.error" v-html="user.name.error"></p>
            </div>
            <div :class="{'form-group half': true, 'error': user.password.error}">
                <label for="input_password">Passwort <span>{{ editMode ? '(Optional)' : '(Required)' }}</span></label>
                <input class="full" type="password" name="input_password" id="input_password" v-model="user.password.model">
                <ul>
                    <li class="form-requirement">Min: 6, Max: 32</li>
                    <li class="form-requirement">min. 1 Groß- u. Kleinbuchstabe</li>
                    <li class="form-requirement">min. 1 Ziffer</li>
                </ul>
                <p class="form-error" v-if="user.password.error" v-html="user.password.error"></p>
            </div>
            <div :class="{'form-group half': true}">
                <label>Gruppe <span>(Optional)</span></label>
                <app-select :list="groups" v-model="user.group.model"></app-select>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            user: {
                name: {},
                password: {},
                group: {}
            },
            validated: false,
            groups: []
        }
    },
    computed: {
        editMode() {
            return this.$route.params.id != 'new'
        }
    },
    methods: {
        validate() {
            this.user.name.error = undefined
            this.user.password.error = undefined

            // Validate username
            if(!this.user.name.model) {
                if(!this.editMode) this.user.name.error = 'Dieses Feld wird benötigt'
            } else {
                if(this.user.name.model.length < 3 || this.user.name.model.length > 16) {
                    this.user.name.error = 'Bitte überprüfe die Länge der Eingabe'
                }
            }

            // Validate password
            if(!this.user.password.model) {
                if(!this.editMode) this.user.password.error = 'Dieses Feld wird benötigt'
            } else {
                if(!this.user.password.model.match(/\d/)) {
                    this.user.password.error = 'Mindestens 1 Ziffer benötigt'
                }
                if(!this.user.password.model.match(/(?=.*[A-Z])/) == null) {
                    this.user.password.error = 'Mindestens 1 Großbuchstabe benötigt'
                }
                if(this.user.password.model.match(/(?=.*[a-z])/) == null) {
                    this.user.password.error = 'Mindestens 1 Kleinbuchstabe benötigt'
                }
            }

            this.validated = !this.user.name.error && !this.user.password.error
        },
        submit(event, done) {
            this.validate()

            if(this.validated) {
                if(this.editMode) {
                    var formData = new FormData()
                    if(this.user.name.model) formData.append('name', this.user.name.model)
                    if(this.user.password.model) formData.append('password', this.user.password.model)
                    if(this.user.group.model) formData.append('group', this.user.group.model)

                    var query = new URLSearchParams(formData).toString()

                    this.$api.put('user/'+this.user.id, {query}).then(() => {
                        this.$toast.success('Benutzer ['+this.user.name.model+'] aktualisiert')
                        setTimeout(() => this.$router.push({name: 'panelUsers'}), 500)
                    }).catch((error) => {
                        if(error == 'name exists') {
                            this.user.name.error = 'Der Name existiert bereits'
                        }
                    }).finally(() => done())
                } else {
                    this.$api.post('user/', {
                        query: '&name='+this.user.name.model+'&password='+this.user.password.model+(this.user.group.model ? '&group='+this.user.group.model : '')
                    }).then(() => {
                        this.$toast.success('Benutzer ['+this.user.name.model+'] erstellt')
                        setTimeout(() => this.$router.push({name: 'panelUsers'}), 500)
                    }).catch((error) => {
                        if(error == 'name exists') {
                            this.user.name.error = 'Der Name existiert bereits'
                        }
                    }).finally(() => done())
                }
            } else {
                done()
            }
        }
    },
    mounted() {
        if(this.$route.params.id == 'new' && this.$user.hasPermission('permission.users.create')) {
            this.loading = false
            this.validated = false
        } else {
            if(!this.$user.hasPermission('permission.users.edit')) {
                this.$route.go(-1)
                this.$toast.error('Keine Berechtigung diese Seite zu besuchen.')
                return
            }

            this.validated = true
            this.$api.get('user/'+this.$route.params.id, {}).then((data) => {
                this.user.id = data.id
                this.user.name.model = data.name
                this.user.group.model = data.permissionGroup
                this.loading = false
            })
        }

        this.$api.get('group/all/&props=["name", "id"]', {}, false).then((data) => {
            this.groups = data.entries
        })
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

.profile-picture {
    display: inline-block;
    width: 38px;
    height: 38px;
    vertical-align: middle;
    background: $colorPlaceholder;
    border-radius: 50%;
}
.profile-info {
    padding-left: 1em;
    display: inline-block;
    vertical-align: middle;

    span {
        display: block;
        font-size: 0.8em;
        letter-spacing: 0.3px;
        opacity: 0.3;
    }
}

</style>