<template>
    <div class="actionbar-container">
        <div class="bar-section">
            <div class="search-field">
                <span><img src="@/assets/images/icons/search.svg"></span>
                <input type="text" placeholder="Suche etwas...">
            </div>
            <button class="btn btn-primary btn-l" style="margin-left: 2em"><img src="@/assets/images/icons/upload.svg"> Upload</button>
        </div>
        <div class="bar-section">
            <div class="bar-profile">
                <p>{{ user.name }}</p>
                <p>{{ user.status }}</p>
            </div>
            <button class="btn btn-icon bar-profile-picture" :style="'background-image: url(uploads/avatars/'+user.id+'.png);'"></button>
            <router-link :to="{name: 'Account'}" tag="button" class="btn" style="margin-left: 1em"><img src="@/assets/images/icons/cogs.svg"></router-link>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        user() {
            return this.$store.state.user;
        }
    }
}
</script>

<style lang="scss" scoped>

.actionbar-container {
    position: sticky;
    top: 0;
    width: 100%;
    overflow: hidden;
    padding: 2.5em;
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
    background-size: cover;
    background-position: center;
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