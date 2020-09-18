<template>
    <div id="actionbar" class="actionbar-container">
        <div class="bar-section menu-section">
            <button class="btn btn-icon btn-tertiary" @click="toggleSidebar"><img src="@/assets/images/icons/menu.svg" alt=""></button>
            <img class="sidebar-logo" src="@/assets/images/branding/ts_logo_svg.svg" alt="">
            TSMedia
        </div>
        <div class="bar-section">
            <router-link :to="{name: 'studioUploads'}" custom v-slot="{}">
                <button class="btn btn-accent btn-small" @click="upload"><img src="@/assets/images/icons/upload.svg" alt="">Upload</button>
            </router-link>
        </div>
        <transition name="slideLeft" mode="out-in">
            <div class="bar-section" v-if="!isLoggedIn">
                <button class="btn btn-light" @click="login"><img src="@/assets/images/icons/key.svg" alt="">Anmelden</button>
            </div>
            <div class="bar-section profile-section" v-else>
                <div class="profile">
                    <p>{{ user.name }}</p>
                </div>

                <img class="profile-picture" src="" alt="">
                <!--<div class="profile-picture"></div>-->
                <button class="btn btn-icon btn-tertiary" @click="logout"><img src="@/assets/images/icons/off.svg" alt=""></button>
            </div>
        </transition>
    </div>
</template>
<script>
import SidebarEventListener from '@/events/SidebarEventListener.js'

export default {
    methods: {
        login() {
            this.$modal.login()
        },
        logout() {
            this.$user.logout()

            if(this.$route.name != 'home') {
                this.$router.push({name: 'home'})
            }
        },
        upload() {
            if(this.$store.getters.isLoggedIn) {
                this.$modal.upload()
                if(this.$route.name != 'studio') this.$router.push({name: 'studio'})
            } else if(!this.$store.getters.isLoggedIn) {
                this.$modal.login()
            }
        },
        toggleSidebar() {
            SidebarEventListener.emit('toggle')
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.actionbar-container {
    display: inline-block;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 10;
    width: 100%;
    height: 60px;
    line-height: 60px;
    padding-right: $innerPad;

    background: linear-gradient(140deg, $colorPrimary 50%, rgba(53,59,68,1) 100%);

    .bar-section {
        position: relative;
        float: left;
        margin-right: $innerPad;

        &.menu-section {
            width: 250px;
            padding-left: $innerPad/2;
            font-weight: 500;

            .sidebar-logo {
                height: 22px;
                width: 22px;
                margin-left: 0.5em;
                margin-right: 0.2em;
            }
        }

        &.profile-section {
            float: right;
            text-align: right;
            margin-right: 0;

            button {
                margin-left: 0.5em;
            }
        }
    }
}

.profile {
    display: inline;
    vertical-align: middle;
    margin-right: 0.8em;

    p {
        display: inline;
        font-size: 0.8em;
        letter-spacing: 1px;
        font-weight: 400;
    }
}
.profile-picture {
    position: relative;
    display: inline-block;
    height: 32px;
    width: 32px;
    vertical-align: middle;
    border-radius: 50%;
    background: $colorPlaceholder;

    &:hover {
        cursor: pointer;

        .profile-dropdown {
            opacity: 1;
        }
    }
}
</style>
