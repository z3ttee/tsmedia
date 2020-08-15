<template>
<div>
    <div class="sidebar-navigation">
        <img class="sidebar-logo" src="@/assets/images/branding/ts_logo_svg.svg" alt="">
        <ul>
            <router-link :to="{name: 'home'}" custom v-slot="{navigate, isActive}">
                <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                    <img src="@/assets/images/icons/home.svg" alt="HomeIcon">
                    <span>Startseite</span>
                </li>
            </router-link>
            <router-link :to="{name: 'home'}" custom v-slot="{navigate, isActive}">
                <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}" disabled>
                    <img src="@/assets/images/icons/category.svg" alt="CategoryIcon">
                    <span>Kategorien</span>
                    <span class="badge dark">Soon</span>
                </li>
            </router-link>
            <router-link :to="{name: 'home'}" custom v-slot="{navigate, isActive}">
                <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                    <img src="@/assets/images/icons/media.svg" alt="MediaIcon">
                    <span>Mediathek</span>
                    <span class="badge dark">Soon</span>
                </li>
            </router-link>
            <router-link :to="{name: 'home'}" custom v-slot="{navigate, isActive}">
                <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                    <img src="@/assets/images/icons/cinema.svg" alt="StudioIcon">
                    <span>Studio</span>
                    <span class="badge dark">Soon</span>
                </li>
            </router-link>
            
            <router-link :to="{name: 'panel'}" custom v-slot="{navigate, isActive}">
                <li @click="navigate" :class="{'pressable-l': true, 'active': isActive}">
                    <img src="@/assets/images/icons/cogs.svg" alt="AdminIcon">
                    <span>Admin</span>
                </li>
            </router-link>
        </ul>
    </div>
    <div class="sidebar-activities">
        Activities
    </div>
</div>
    
</template>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.sidebar-logo {
    position: absolute;
    top: $innerPad;
    left: 50%;
    transform: translateX(-50%);
    width: 2em;
}

.sidebar-navigation {
    position: relative;
    padding-top: $innerPad*2.5;
    padding-bottom: $innerPad;
    background: linear-gradient(140deg, rgba(40,47,56,1) 20%, rgba(53,59,68,1) 100%);
    height: 50%;
    overflow-x: hidden;
    overflow-y: auto;

    ul {
        list-style: none;
        padding: 0;
        text-align: left;
        width: 100%;
        margin-top: $innerPad;
        font-size: 0.85em;
        letter-spacing: 0.5px;
        font-weight: 500;
        

        li {
            position: relative;
            padding: 0.8em 2em;
            width: 100%;
            opacity: 0.6;

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