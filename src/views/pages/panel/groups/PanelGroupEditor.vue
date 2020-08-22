<template>
    <div>
        <div class="interface-head">
            <h2>
                {{ editMode ? 'Gruppe bearbeiten' : 'Gruppe erstellen' }}
                <app-button class="btn btn-accent" text="Speichern" @clicked="submit"></app-button>
            </h2>
            <hr class="interface large">
        </div>

        <app-loader v-if="loading"></app-loader>
        <div class="section-box" v-if="!loading">
            <div class="interface-description">
                <h4>Informationen</h4>
                <p>Stelle hier allgemeine Gruppeninformationen ein, wie Name und Hierarchie.</p>
            </div>

            <div :class="{'form-group half': true, 'error': group.name.error}">
                <label for="input_name">Bezeichner <span>{{ editMode ? '(Optional)' : '(Required)' }}</span></label>
                <input class="full" type="text" name="input_name" id="input_name" v-model="group.name.model">
                <ul>
                    <li class="form-requirement">Min: 3, Max: 16</li>
                </ul>
                <p class="form-error" v-if="group.name.error" v-html="group.name.error"></p>
            </div>
            <div :class="{'form-group half': true, 'error': group.displayname.error}">
                <label for="input_displayname">Displayname <span>{{ editMode ? '(Optional)' : '(Required)' }}</span></label>
                <input class="full" type="text" name="input_displayname" id="input_displayname" v-model="group.displayname.model">
                <ul>
                    <li class="form-requirement">Min: 3, Max: 16</li>
                </ul>
                <p class="form-error" v-if="group.displayname.error" v-html="group.displayname.error"></p>
            </div>
            <div :class="{'form-group half': true, 'error': group.hierarchy.error}">
                <label for="input_hierarchy">Hierarchy <span>{{ editMode ? '(Optional)' : '(Required)' }}</span></label>
                <input class="full" type="number" min="0" max="1000" name="input_hierarchy" id="input_hierarchy" v-model="group.hierarchy.model">
                <ul>
                    <li class="form-requirement">Lowest: 0, Highest: 1000</li>
                </ul>
                <p class="form-error" v-if="group.hierarchy.error" v-html="group.hierarchy.error"></p>
            </div>
        </div>
        <div class="section-box" v-if="!loading">
            <div class="interface-description">
                <h4>Berechtigungen</h4>
                <p>Wähle hier die Berechtigungen für die Gruppe.</p>
            </div>

            <div class="form-group full">
                <h4>Webinterface</h4>
                <label class="checkbox" for="permission.panel"><input type="checkbox" id="permission.panel" v-model="group.permissions" value="permission.panel"> Erlaubt Zugriff auf Webinterface</label>

                <div v-if="group.permissions.includes('permission.panel')">
                    <br>
                    <h6>Benutzer</h6>
                    <label class="checkbox" for="permission.users"><input type="checkbox" id="permission.users" v-model="group.permissions" value="permission.users"> Erlaubt Zugriff auf Benutzerübersicht</label>
                    <label class="checkbox" for="permission.users.edit"><input type="checkbox" id="permission.users.edit" v-model="group.permissions" value="permission.users.edit"> Erlaubt es, Benutzer zu bearbeiten</label>
                    <label class="checkbox" for="permission.users.create"><input type="checkbox" id="permission.users.create" v-model="group.permissions" value="permission.users.create"> Erlaubt es, Benutzer zu erstellen</label>
                    <label class="checkbox" for="permission.users.delete"><input type="checkbox" id="permission.users.delete" v-model="group.permissions" value="permission.users.delete"> Erlaubt es, Benutzer zu löschen</label>
                </div>
                <div v-if="group.permissions.includes('permission.panel')">
                    <br>
                    <h6>Gruppen</h6>
                    <label class="checkbox" for="permission.groups"><input type="checkbox" id="permission.groups" v-model="group.permissions" value="permission.groups"> Erlaubt Zugriff auf Gruppenübersicht</label>
                    <label class="checkbox" for="permission.groups.edit"><input type="checkbox" id="permission.groups.edit" v-model="group.permissions" value="permission.groups.edit"> Erlaubt es, Gruppen zu bearbeiten</label>
                    <label class="checkbox" for="permission.groups.create"><input type="checkbox" id="permission.groups.create" v-model="group.permissions" value="permission.groups.create"> Erlaubt es, Gruppen zu erstellen</label>
                    <label class="checkbox" for="permission.groups.delete"><input type="checkbox" id="permission.groups.delete" v-model="group.permissions" value="permission.groups.delete"> Erlaubt es, Gruppen zu löschen</label>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: true,
            group: {
                name: {},
                displayname: {},
                hierarchy: {model: 0},
                permissions: []
            },
            validated: false
        }
    },
    watch: {
        group(val) {
            console.log(val)
      }
    },
    computed: {
        editMode() {
            return this.$route.params.id != 'new'
        }
    },
    methods: {
        validate() {
            this.group.name.error = undefined
            this.group.displayname.error = undefined
            this.group.hierarchy.error = undefined

            // Validate group name
            if(!this.group.name.model) {
                if(!this.editMode) this.group.name.error = 'Dieses Feld wird benötigt'
            } else {
                if(this.group.name.model.length < 3 || this.group.name.model.length > 16) {
                    this.group.name.error = 'Bitte überprüfe die Länge der Eingabe'
                }
            }

            // Validate group displayname
            if(!this.group.displayname.model) {
                if(!this.editMode) this.group.displayname.error = 'Dieses Feld wird benötigt'
            } else {
                if(this.group.displayname.model.length < 3 || this.group.displayname.model.length > 16) {
                    this.group.displayname.error = 'Bitte überprüfe die Länge der Eingabe'
                }
            }

            this.validated = !this.group.name.error && !this.group.displayname.error

            // Validate hierarchy
            if(isNaN(this.group.hierarchy.model)) {
                this.group.hierarchy.error = 'Du musst eine Ganzzahl eingeben'
                this.validated = false
            } else {
                if(this.group.hierarchy.model < 0 || this.group.hierarchy.model > 1000) {
                    this.group.hierarchy.error = 'Bitte beachte das Minimum und Maximum'
                    this.validated = false
                }
            }
        },
        submit(event, done) {
            this.validate()

            if(this.validated) {
                if(this.editMode) {
                    var formData = new FormData()
                    if(this.group.name.model) formData.append('name', this.group.name.model)
                    if(this.group.displayname.model) formData.append('displayname', this.group.displayname.model)

                    formData.append('permissions', JSON.stringify(this.group.permissions))
                    formData.append('hierarchy', this.group.hierarchy.model)

                    var query = new URLSearchParams(formData).toString()

                    this.$api.put('group/'+this.group.id, {query}).then(() => {
                        this.$toast.success('Gruppe ['+this.group.name.model+'] aktualisiert')
                        setTimeout(() => this.$router.push({name: 'panelGroups'}), 500)
                    }).catch((error) => {
                        if(error == 'name exists') {
                            this.group.name.error = 'Der Name existiert bereits'
                        }
                    }).finally(() => done())
                } else {
                    this.$api.post('group/', {
                        query: '&name='+this.group.name.model+'&displayname='+this.group.displayname.model+'&hierarchy='+this.group.hierarchy.model+'&permissions='+JSON.stringify(this.group.permissions)
                    }).then(() => {
                        this.$toast.success('Gruppe ['+this.group.name.model+'] erstellt')
                        setTimeout(() => this.$router.push({name: 'panelGroups'}), 500)
                    }).catch((error) => {
                        if(error == 'name exists') {
                            this.group.name.error = 'Der Name existiert bereits'
                        }
                    }).finally(() => done())
                }
            } else {
                done()
            }
        }
    },
    mounted() {
        if(this.$route.params.id == 'new' && this.$user.hasPermission('permission.groups.create')) {
            this.loading = false
            this.validated = false
        } else {
            if(!this.$user.hasPermission('permission.groups.edit')) {
                this.$route.go(-1)
                this.$toast.error('Keine Berechtigung diese Seite zu besuchen.')
                return
            }

            this.validated = true
            this.$api.get('group/'+this.$route.params.id, {}).then((data) => {

                this.group.id = data.id
                this.group.name.model = data.name
                this.group.displayname.model = data.displayname
                this.group.permissions = data.permissions
                this.group.hierarchy.model = data.hierarchy
                this.loading = false

                console.log(this.group)
            })
        }
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