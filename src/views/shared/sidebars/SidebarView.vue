<template>
    <div class="sidebar-container">
        <div class="sidebar-navigation">
            <div class="navigation-header">
                <div class="header-item">
                    <button class="btn btn-icon"><img src="@/assets/images/icons/search.svg"></button>
                </div>
                <div class="header-item">
                    <img src="@/assets/images/branding/ts_logo_svg.svg" id="app-logo">
                </div>
                <div class="header-item">
                    <button class="btn btn-icon"><img src="@/assets/images/icons/menu.svg"></button>
                </div>
            </div>

            <ul>
                <router-link :to="{name: 'Home'}" tag="li" active-class="active" exact><img src="@/assets/images/icons/home.svg"><span> Startseite</span></router-link>
                <router-link :to="{name: 'Categories'}" tag="li" active-class="active" exact><img src="@/assets/images/icons/category.svg"><span> Kategorien</span></router-link>
                <router-link :to="{name: 'Panel'}" tag="li" active-class="active" exact v-if="user.access_token"><img src="@/assets/images/icons/cogs.svg"><span> Admin</span></router-link>
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
    computed: {
        user() {
            return this.$store.state.user;
        }
    }
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

        h6 {
            display: none;
        }
    }

    ul {
        list-style: none;
        padding: 0;
    }

    .sidebar-navigation {
        padding: 1.5em 0em;
        padding-bottom: 8em;
        background: linear-gradient(140deg, rgba(40,47,56,1) 20%, rgba(53,59,68,1) 100%);

        button {
            display: none;

            img {
                display: inline-block;
                height: 16px;
            }
        }

        ul {
            margin-top: 2.5em;

            li {
                position: relative;
                font-weight: 400;
                padding: 1em 1.6em;
                font-size: 0.9em;
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

    @media screen and (max-width: 1100px) {
        #app-logo {
            height: 35px;
            margin: 2em auto;
        }

        .sidebar-container {
            width: 80px;
            overflow: hidden;

            .sidebar-navigation {
                padding: 0;

                ul {
                    margin: 0;

                    li { 
                        text-align: center;
                        padding: 1.6em 0em;

                        img {
                            height: 18px;
                            width: 18px;
                            margin: 0;
                        }

                        span {
                            display: block;
                            margin-top: 0.5em;
                            font-size: 0.7em;
                            font-weight: 400;
                            white-space: pre-line;
                        }

                        &::after {
                            height: 30%;
                        }
                    }
                }
            }

            .sidebar-activities {
                margin: 0;
                padding: 0;
            }
        }
    }

    @media screen and (max-width: 840px) {
        #app-logo {
            margin: 0 auto;
        }
        .sidebar-container {
            display: block !important;
            width: 100%;
            padding: 0.7em 2.5em !important;
            box-shadow: $shadowHeavy;
        }
        .sidebar-navigation {
            width: 100%;
            background: none;

            .navigation-header {
                display: table;
                width: 100%;

                &>div {
                    display: table-cell;
                    vertical-align: middle;

                    &:first-of-type {
                        text-align: left;
                    }
                    &:last-of-type {
                        text-align: right;
                    }
                }
            }

            button {
                display: inline-block;

                width: 42px;
                height: 42px;

                img {
                    width: 14px;
                }
            }

            ul {
                display: none !important;
            }
        }
        
        .sidebar-activities {
            display: none;
        }
    }
</style>