<template>
    <div class="sidebar-categories">
        <ul>
            <router-link :to="{name: 'home'}" custom v-slot="{navigate}">
                <li @click="navigate" id="sidebar-logo"><img src="@/assets/images/branding/ts_logo_svg.svg" alt=""></li>
            </router-link>
            <hr class="interface">
            <router-link :to="{name: 'panelDashboard'}" custom v-slot="{navigate, isExactActive}">
                <li @click="navigate" :class="{'pressable-xl': true, 'active': isExactActive}"><img src="@/assets/images/icons/dashboard.svg" alt=""></li>
            </router-link>
            <router-link :to="{name: 'panelGroups'}" custom v-slot="{navigate, isExactActive}">
                <li @click="navigate" :class="{'pressable-xl': true, 'active': isExactActive}"><img src="@/assets/images/icons/team.svg" alt=""></li>
            </router-link>
            <router-link :to="{name: 'panelGroups'}" custom v-slot="{navigate, isExactActive}">
                <li @click="navigate" :class="{'pressable-xl': true, 'active': isExactActive}"><img src="@/assets/images/icons/lock.svg" alt=""></li>
            </router-link>
        </ul>
    </div>
</template>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.sidebar-categories {
    position: fixed;
    padding-top: $innerPad;
    padding-bottom: $innerPad;
    top: 0;
    left: 0;
    bottom: 0;
    width: 70px;
    background: $colorPrimaryDark;
    border-top-right-radius: $borderRadLarge;
    border-bottom-right-radius: $borderRadLarge;
    overflow-y: auto;
    overflow-x: hidden;

    ul {
        list-style: none;
        padding: 0;

        li {
            position: relative;
            padding: 0.8em 0;
            opacity: 0.6;
            transition: all $animSpeedNormal*1s $cubicNorm;

            &#sidebar-logo {
                padding: 0;
                opacity: 1;

                &::after {
                    display: none !important;
                }

                &:hover {
                    background: none;
                }
                
                img {
                    width: 32px;
                    height: 32px;
                }
            }

            &:hover {
                background: $colorPrimary;
                opacity: 1;
                cursor: pointer;
            }

            &.active {
                opacity: 1;

                &::after {
                    height: 40%;
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
                border-radius: 8px;
                transition: all $animSpeedNormal*1s $cubicNorm;
            }
        }
    }

    

    img {
        display: inline-block;
        width: 18px;
        height: 18px;
        vertical-align: middle;
    }
}
</style>