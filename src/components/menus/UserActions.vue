<template>
<div class="category-container">
    <h5>Benutzerverwaltung</h5>

    <hr class="interface large">

    <button :class="'btn btn-primary btn-full btn-interface '+(selected == 'UsersOverview' ? 'active' : '')" @click="select('UsersOverview')">Übersicht</button>
    <button :class="'btn btn-primary btn-full btn-interface '+(selected == 'UserCreate' ? 'active' : '')" @click="select('UserCreate')">Benutzer erstellen</button>
</div>
    
</template>

<script>
import EventBus from '@/models/selectedEventBus.js';

export default {
    data() {
        return {
            selected: 'UsersOverview'
        }
    },
    methods: {
        select(val) {
            this.selected = val;
            EventBus.$emit('actionSelected', val);
        }
    }
}
</script>