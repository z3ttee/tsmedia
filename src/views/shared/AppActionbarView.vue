<template>
    <div class="actionbar-container">
        <div class="bar-section">
            <router-link :to="{name: 'studioUploads'}" custom v-slot="{}">
                <button class="btn btn-accent" @click="upload"><img src="@/assets/images/icons/upload.svg" alt="">Upload</button>
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
                <div class="profile-picture"></div>
                <button class="btn btn-icon btn-tertiary" @click="logout"><img src="@/assets/images/icons/off.svg" alt=""></button>
            </div>
        </transition>
    </div>
</template>
<script>
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
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';

.actionbar-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    width: 100%;
    padding: $innerPad/2 $innerPad;

    .bar-section {
        display: block;
        width: 100%;

        &:last-of-type {
            text-align: right;
        }

        &.profile-section {
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
    height: 42px;
    width: 42px;
    vertical-align: middle;
    border-radius: $borderRadTiny;
    background: $colorPlaceholder;

    &:hover {
        cursor: pointer;

        .profile-dropdown {
            opacity: 1;
        }
    }
}
</style>
