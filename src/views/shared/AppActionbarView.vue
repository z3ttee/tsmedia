<template>
    <div class="actionbar-container">
        <div class="bar-section">
                <button class="btn btn-accent" @click="upload"><img src="@/assets/images/icons/upload.svg" alt="">Upload</button>
            </div>
        <transition name="slideLeft" mode="out-in">
            <div class="bar-section" v-if="!isLoggedIn">
                <button class="btn btn-light" @click="login"><img src="@/assets/images/icons/key.svg" alt="">Anmelden</button>
            </div>
            <div class="bar-section" v-else>
                <div class="profile">
                    <p>{{ user.name }}</p>
                </div>
                <div class="profile-picture"></div>
            </div>
        </transition>
    </div>
</template>
<script>
export default {
    methods: {
        login() {
            this.showLogin();
        },
        upload() {
            
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
    padding: $innerPad $innerPad $innerPad/2 $innerPad;

    .bar-section {
        display: block;
        width: 100%;

        &:last-of-type {
            text-align: right;
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
    display: inline-block;
    height: 42px;
    width: 42px;
    vertical-align: middle;
    border-radius: $borderRadTiny;
    background: $colorPlaceholder;
}
</style>
