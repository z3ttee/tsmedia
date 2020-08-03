<template>
<div class="category-container">
    <h5>Berechtigungen</h5>

    <hr class="interface large">

    <router-link tag="button" :to="{name: 'PanelGroupIndex'}" class="btn btn-primary btn-full btn-interface" active-class="active" :exact="true">Übersicht</router-link>
    <router-link tag="button" :to="{name: 'PanelGroupEditor', params: {id: 'new'}}" class="btn btn-primary btn-full btn-interface" active-class="active">Gruppe erstellen</router-link>
</div>
    
</template>