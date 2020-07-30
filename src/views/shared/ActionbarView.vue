<template>
    <div class="actionbar-container">
        <div class="bar-section" v-if="currentRoute.group != 'panel'">
            <div class="search-field">
                <span><img src="@/assets/images/icons/search.svg"></span>
                <input type="text" placeholder="Suche etwas...">
            </div>
            <button class="btn btn-primary btn-l" style="margin-left: 2em"><img src="@/assets/images/icons/upload.svg" >Upload</button>
        </div>
        <div class="bar-section" v-else>
            <h3>{{ currentRoute.title }}</h3>
        </div>
        <div class="bar-section">
            <div class="bar-profile" v-if="user.access_token">
                <p>{{ user.name }}</p>
                <p>{{ user.status }}</p>
            </div>
            <button class="btn btn-icon bar-profile-picture bar-profile-picture-ext btn-m" v-if="!user.access_token" @click="showModal('login', { title: 'Please login' })"><img src="@/assets/images/icons/user.svg"> Anmelden</button>
            <div class="bar-profile-picture popup-wrapper" style="background-image: url(uploads/avatars/'+user.id+'.png);" v-if="user.access_token">
                <div class="popup">
                    <div class="popup-content">
                        <ul>
                            <router-link tag="li" :to="{path: '/profile/'+user.id}"><img src="@/assets/images/icons/id.svg">Profil ansehen</router-link>
                            <router-link tag="li" :to="{name: 'Account'}"><img src="@/assets/images/icons/user.svg">Kontodetails</router-link>
                            <li @click="logout"><img src="@/assets/images/icons/logout.svg">Abmelden</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import User from '@/models/user.js';

export default {
    computed: {
        user() {
            return this.$store.state.user;
        },
        currentRoute() {
            return this.$route.meta;
        }
    },
    methods: {
        logout() {
            User.logout();
        }
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/popupMenu.scss';

.actionbar-container {
    position: sticky;
    display: inline-block;
    top: 0;
    width: 100%;
    padding: 2.5em;
    padding-top: 1.5em;
}

.bar-profile {
    display: inline-flex;
    flex-direction: column;
    justify-content: center;
    height: initial;
    margin-right: 1em;

    p {
        font-weight: 400;
        letter-spacing: 0.8px;
        line-height: 1.4em;
        font-size: 0.8em;
        text-align: right;
        width: 100%;

        &:last-of-type {
            color: $colorAccent;
        }
    }
}

.bar-section {
    display: inline-flex;
    height: 50px;
    line-height: 50px;
    
    h3 {
        font-weight: 700;
    }

    img {
        height: 20px;
    }

    .btn {
        height: 100%;
    }
    .btn-icon {
        width: 50px;
    }

    &:last-of-type {
        float: right;
    }
}

.bar-profile-picture {
    border-radius: $borderRadSmall;
    background-color: $colorPlaceholder;
    width: 50px;
    background-size: cover;
    background-position: center;
    cursor: pointer;

    &.bar-profile-picture-ext {
        width: initial;

        img {
            margin-right: 0.5em;
        }
    }
}

.search-field {
    position: relative;
    display: inline-block;
    height: 100%;
    background-color: $colorPlaceholder;
    padding: 0em 0.5em;
    border-radius: $borderRadSmall;

    span {
        position: absolute;
        top: 50%;
        left: 0.4em;
        display: inline-block;
        height: 38px;
        width: 38px;
        background-color: $colorPrimary;
        transform: translateY(-50%);
        text-align: center;
        border-radius: $borderRadSmall;
        cursor: pointer;
    }

    input {
        display: inline-block;
        appearance: none;
        background: none;
        border: none;
        outline: none;
        height: 100%;
        font-size: 0.8em;
        padding: 0em 0.5em;
        margin-left: 40px;
        font-family: 'Poppins';
        font-weight: 500;
        width: 250px;
    }
    input::placeholder {
        font-weight: 700;
        color: $colorPrimaryDark;
        opacity: 1;
    }
}

@media screen and (max-width: 1100px) {
    .bar-profile {
        display: none;
    }
}
@media screen and (max-width: 840px) {
    .actionbar-container {
        display: none;
    }
}
</style>