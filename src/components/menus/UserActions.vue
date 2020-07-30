<template>
<div class="category-container">
    <h5>Benutzerverwaltung</h5>

    <hr class="interface large">

    <router-link tag="button" :to="{name: 'PanelUserIndex'}" class="btn btn-primary btn-full btn-interface" active-class="active" :exact="true">Übersicht</router-link>
    <router-link tag="button" :to="{name: 'PanelUserCreate'}" class="btn btn-primary btn-full btn-interface" active-class="active">Benutzer erstellen</router-link>
</div>
    
</template>