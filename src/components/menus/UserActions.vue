<template>
<div class="category-container">
    <h5>Benutzerverwaltung</h5>

    <hr class="interface large">

    <router-link tag="button" :to="{name: 'PanelUserIndex'}" class="btn btn-primary btn-full btn-interface" active-class="active" :exact="true">Übersicht</router-link>
    <router-link tag="button" :to="{name: 'PanelUserEditor', params: {id: 'new'}}" class="btn btn-primary btn-full btn-interface" active-class="active">Benutzer erstellen</router-link>
    <router-link tag="button" :to="{}" class="btn btn-primary btn-full btn-interface" disabled>Gesperrte Nutzer [WiP]</router-link>
</div>
    
</template>