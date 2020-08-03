<template>
<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="sidebar-container">
            <div class="sidebar-col sidebar-categories">
                <img src="@/assets/images/branding/ts_logo_svg.svg" id="app-logo">
                <hr class="interface">
                <ul>
                    <router-link :to="{name: 'Home'}" tag="li" title="Webinterface verlassen"><img src="@/assets/images/icons/back.svg"></router-link>
                </ul>
                <hr class="interface">
                <ul>
                    <router-link :to="{name: 'Dashboard'}" tag="li" active-class="active" title="Dashboard" @click="changeCategory('DashboardActions')" :exact="true"><img src="@/assets/images/icons/dashboard.svg"></router-link>                    
                    <router-link :to="{name: 'PanelUserIndex'}" tag="li" active-class="active" title="Benutzerverwaltung" @click="changeCategory('UserActions')"><img src="@/assets/images/icons/team.svg"></router-link>
                    <router-link :to="{name: 'PanelGroupIndex'}" tag="li" active-class="active" title="Berechtigungen" @click="changeCategory('UserActions')"><img src="@/assets/images/icons/lock.svg"></router-link>

                    <!--<li :class="(currentMenu == 'UserActions' ? 'active' : '')" title="Benutzerverwaltung" @click="changeCategory('UserActions')"><img src="@/assets/images/icons/team.svg"></li>-->
                </ul>
            </div>
            <div class="sidebar-col">
                <component :is="currentMenuComp" class="loader"></component>
            </div>
        </div>
    </div>
</div>
    
</template>

<script>
import AppLoader from '@/components/loader/PrimaryLoader.vue';

export default {
    components: {
        AppLoader
    },
    computed: {
        currentRoute() {
            return this.$route.meta.actions;
        },
        currentMenuComp() {
            this.currentRoute
            return () => ({
                component: import('@/components/menus/'+this.currentRoute+'.vue'),
                loading: AppLoader
            });
        }
    },
    mounted() {
        document.getElementsByClassName('sidebar-wrapper')[0].style.height = window.innerHeight+'px';
        window.onresize = () => {
            document.getElementsByClassName('sidebar-wrapper')[0].style.height = window.innerHeight+'px';
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/animations.scss';
@import '@/assets/scss/popupMenu.scss';

    .loader {
        display: block;
        width: 45px;
    }

    img {
        height: 20px;
        margin: 0 auto;
        transition: all $animSpeedFast*1s $cubicNorm;

        &#app-logo {
            height: 32px;
            margin: 0 auto;
        }
    }

    .sidebar {
        width: 340px;
        background-color: $colorPrimary;
        height: 100%;
        background-color: $colorPrimaryDark;
    }
    .sidebar-wrapper {
        display: block;
        height: inherit;
        border-top-right-radius: 2em;
        border-bottom-right-radius: 2em;
        background-color: $colorPrimary;
    }
    .sidebar-container {
        display: table;
        width: 100%;
        height: 100%;

        .sidebar-col {
            display: table-cell;
            vertical-align: top;

            &:first-of-type {
                width: 70px;
                background-color: $colorPrimaryDark;
                border-top-right-radius: 2em;
                border-bottom-right-radius: 2em;
                text-align: center;
                padding-top: 1.5em;
            }

            &:last-of-type {
                padding: 3em 2em;
                max-width: 270px;
                overflow: hidden;
            }
        }
    }

    .sidebar-categories {
        ul {
            padding: 0;
            list-style: none;

            li {
                position: relative;
                height: 50px;
                line-height: 50px;
                cursor: pointer;
                transition: all $animSpeedFast*1s $cubicNorm;

                &:hover {
                    background-color: $colorPrimary;
                }
                &:active {
                    img {
                        transform: scale(0.94);
                    }
                }

                img {
                    
                    vertical-align: middle;
                }

                &.active {
                    background-color: $colorPrimary;
                }
            }
        }
    }

    .category-container {
        display: block;
        width: 100%;

        hr.interface {
            margin-bottom: 3em !important;
        }
    }
    
</style>