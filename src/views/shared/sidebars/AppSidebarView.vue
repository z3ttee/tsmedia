<template>
    <div class="container">
        <div class="sidebar-navigation">
            <ul>
                <router-link :to="{name: 'home'}" custom v-slot="{navigate, isActive}">
                    <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                        <img src="@/assets/images/icons/home.svg" alt="HomeIcon">
                        <span>Startseite</span>
                    </li>
                </router-link>
                <!--<router-link :to="{name: 'categories'}" custom v-slot="{navigate, isActive}">
                    <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}" disabled>
                        <img src="@/assets/images/icons/category.svg" alt="CategoryIcon">
                        <span>Kategorien</span>
                        <span class="badge dark">Soon</span>
                    </li>
                </router-link>
                <router-link :to="{name: 'library'}" custom v-slot="{navigate, isActive}">
                    <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                        <img src="@/assets/images/icons/media.svg" alt="MediaIcon">
                        <span>Mediathek</span>
                        <span class="badge dark">Soon</span>
                    </li>
                </router-link>-->
                <router-link :to="{name: 'studio'}" custom v-slot="{navigate, isActive}" v-if="$store.getters.isLoggedIn">
                    <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                        <img src="@/assets/images/icons/cinema.svg" alt="StudioIcon">
                        <span>Studio</span>
                        <span class="badge dark">NEW</span>
                    </li>
                </router-link>
                
                <router-link :to="{name: 'panelDashboard'}" custom v-slot="{navigate, isActive}" v-if="$store.getters.hasPermission('permission.panel')">
                    <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                        <img src="@/assets/images/icons/cogs.svg" alt="AdminIcon">
                        <span>Admin</span>
                    </li>
                </router-link>

                <li class="pressable-l" @click="imprint()">
                    <img src="@/assets/images/icons/law.svg" alt="ImprintIcon">
                    <span>Impressum</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import SidebarEventListener from '@/events/SidebarEventListener.js'
import config from '@/config.json'

export default {
    data() {
        return {
            config,
            sidebarHidden: false
        }
    },
    methods: {
        imprint() {
            window.open(config.urls.imprint, '_blank')
        },
        toggle(show) {
            this.sidebarHidden = show || !this.sidebarHidden
            document.getElementById('sidebar').style.width = this.sidebarHidden ? '0px' : '250px'
        }
    },
    mounted() {
        SidebarEventListener.on('toggle', this.toggle)
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.sidebar-navigation {
    position: relative;
    padding-bottom: $innerPad;
    background: linear-gradient(140deg, $colorPrimary 25%, rgba(53,59,68,1) 90%);
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;

    ul {
        list-style: none;
        padding: 0;
        text-align: left;
        width: 100%;
        font-size: 0.85em;
        letter-spacing: 0.5px;
        font-weight: 500;
        
        li {
            position: relative;
            padding: 0.8em 2em;
            width: 100%;
            opacity: 0.6;
            cursor: pointer;

            img {
                display: inline-block;
                height: 1em;
                width: 1em;
                margin-right: 2em;
                vertical-align: middle;
            }

            &:hover {
                //background-color: $colorPrimary;
                opacity: 1;
            }

            &.active {
                opacity: 1;

                &::after {
                   height: 45%;
                   opacity: 1;
                }
            }

            &::after {
                content: "";
                position: absolute;
                top: 50%;
                right: 0;
                opacity: 0;
                width: 3px;
                height: 0;
                background-color: $colorWhite;
                transform: translateY(-50%);
                border-radius: 4px;
                transition: all $animSpeedNormal*1s $cubicNorm;
            }
        }
    }
}
.sidebar-activities {
    text-align: left;
    padding-top: $innerPad;
    padding-bottom: $innerPad;
}
</style>