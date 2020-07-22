<template>
    <div class="sidebar-container">
        <div class="sidebar-navigation">
            <img src="@/assets/images/branding/ts_logo_svg.svg" id="app-logo">

            <ul>
                <router-link :to="{name: 'Home'}" tag="li" active-class="active"><img src="@/assets/images/icons/home.svg"> Startseite</router-link>
                <router-link :to="{name: 'Categories'}" tag="li" active-class="active"><img src="@/assets/images/icons/category.svg"> Kategorien</router-link>
                <router-link :to="{name: 'Panel'}" tag="li" active-class="active"><img src="@/assets/images/icons/cogs.svg"> Adminbereich</router-link>
            </ul>
        </div>
        <div class="sidebar-activities" id="latestActivities">
            <h6>Latest activities</h6>

            <ul>
                <li></li>
            </ul>
        </div>
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
        height: 100%;
    }

    .sidebar-activities {
        padding: 1.5em;
    }

    ul {
        list-style: none;
        padding: 0;
    }

    .sidebar-navigation {
        padding: 1.5em 0em;
        padding-bottom: 8em;
        background: linear-gradient(140deg, rgba(40,47,56,1) 20%, rgba(53,59,68,1) 100%);

        ul {
            margin-top: 2.5em;

            li {
                position: relative;
                font-weight: 400;
                padding: 0.9em 1.6em;
                font-size: 0.95em;
                transition: all $animSpeedFast*1s $cubicNorm;
                opacity: 0.5;

                img {
                    width: 16px;
                    height: 16px;
                    margin-right: 1.5em;
                    vertical-align: middle;
                }

                &:hover {
                    cursor: pointer;
                    background-color: $colorPrimaryDark;
                    opacity: 1;
                }
                &:active {
                    transform: scale(0.98);
                }
                &.active {
                    opacity: 1;

                    &:hover {
                        background: none;
                    }

                    &::after {
                        opacity: 1;
                        width: 4px;
                    }
                }

                &::after {
                    position: absolute;
                    display: block;
                    content: "";
                    width: 0px;
                    height: 50%;
                    top: 50%;
                    right: 0;
                    transform: translateY(-50%);
                    border-radius: 8px;
                    background-color: $colorWhite;
                    opacity: 0;
                    transition: all $animSpeedNormal*1s $cubicNorm;
                }
            }
        }
    }

    
</style>