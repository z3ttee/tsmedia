<template>
    <div class="sidebar-container">
        <img src="@/assets/images/branding/ts_logo_svg.svg" id="app-logo">

        <ul>
            <router-link :to="{name: 'Home'}" tag="li" active-class="active"><img src="@/assets/images/icons/home.svg"> Startseite</router-link>
            <router-link :to="{name: 'Categories'}" tag="li" active-class="active"><img src="@/assets/images/icons/home.svg"> Startseite</router-link>
        </ul>
    </div>
</template>

<script>
export default {
    
}
</script>

<style lang="scss" scoped>
    #app-logo {
        display: block;
        height: 45px;
    }
    .sidebar-container {
        width: 240px;
        background-color: $colorPrimary;
    }

    ul {
        list-style: none;
        padding: 0;
        margin-top: 4em;

        li {
            font-weight: 400;
            padding: 0.9em 1.5em;
            font-size: 0.95em;
            border-left: 3px solid transparent;
            transition: all $animSpeedFast*1s $cubicNorm;

            img {
                width: 16px;
                height: 16px;
                margin-right: 1.5em;
                vertical-align: middle;
            }

            &:hover {
                cursor: pointer;
                background-color: $colorPrimaryDark;
            }
            &.active {
                border-left-color: $colorAccent;
            }
        }
    }
</style>