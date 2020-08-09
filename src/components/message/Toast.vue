<template>
    <div :class="'toast '+toast.type">
        <p class="title" v-html="title"></p>
        <p class="content" v-html="toast.content"></p>
        <div class="duration-bar"><div class="duration-bar-progress" id="progress"></div></div>
        <div class="close" @click="$toast.dismiss">
            <img src="@/assets/images/icons/close.svg">
        </div>
    </div>
</template>

<script>
export default {
    props: ['toast'],
    data() {
        return {
            interval: undefined
        }
    },
    computed: {
        title() {
            var type = this.toast.type || 'success';
            
            switch(type) {
                case 'error':
                    return 'Ein Fehler ist aufgetreten'
                default:
                    return 'Aktion erfolgreich!'
            }
        }
    },
    mounted() {
        var duration = this.toast.duration || 15;
        var max = duration*1000
        var current = 0

        this.interval = setInterval(() => {
            current+=1000;

            document.getElementById('progress').style.width = (100-((current/max)*100))+'%';
        }, 1000);

        setTimeout(() => {
            clearInterval(this.interval);
            this.$toast.dismiss();
        }, max+1000);
    },
    destroyed() {
        if(this.interval) clearInterval(this.interval);
    }
}
</script>

<style lang="scss" scoped>
.toast {
    position: relative;
    display: inline-block;
    width: 100%;
    padding: 1em;
    background-color: $colorDark;
    border-radius: $borderRadTiny;
    box-shadow: $shadowHeavy;
    overflow: hidden;

    p {
        padding-right: 3em;

        &.title {
            font-size: 0.7em;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
        }

        &.content {
            font-size: 0.8em;
            letter-spacing: .5px;
        }
    }

    .duration-bar {
        position: absolute;
        display: block;
        width: 100%;
        background: $colorPlaceholder;
        height: 4px;
        left: 0;
        bottom: 0;
        text-align: left;

        .duration-bar-progress {
            display: block;
            height: 100%;
            width: 100%;
            border-radius: 4px;
            background: $colorAccent;
            transition: all 1s linear;
        }
    }

    .close {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 4px;
        width: 2em;
        text-align: center;
        cursor: pointer;
        padding: 0em 1.5em;
        transition: all $animSpeedFast*1s $cubicNorm;

        &:hover {
            background: rgba(0, 0, 0, 0.1);
        }
        &:active {
            img {
                transform: scale(0.9) translate(-50%, -50%);
            }
        }

        img {
            position: relative;
            height: 12px;
            top: 50%;
            left: 50%;
            margin-bottom: 4px;
            transform: translate(-50%, -50%);
            width: 12px;
        }
    }

    &.success {
        background: $colorSuccess;

        .duration-bar-progress {
            background: $colorPrimary;
        }
    }

    &.error {
        background-color: $colorAccent;

        .duration-bar-progress {
            background: $colorPrimary;
        }
    }
}
</style>