<template>
    <div :class="'message-box message-type-'+(type ? type : 'info')" @click="dismissMessage" v-if="!dismissed" :style="dismissable ? 'cursor: pointer;' : ''">
        <div class="message-col">
            <img :src="messageIcon">
        </div>
        <div class="message-col">
            <p>{{ message }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message', 'type', 'dismissable'],
    data() {
        return {
            dismissed: false
        }
    },
    computed: {
        messageIcon() {
            switch (this.type ? this.type : 'info') {
                case 'error':
                    return "/images/icons/error.svg";
                case 'success':
                    return "/images/icons/check.svg";
                default:
                    return "/images/icons/info.svg";
            }
        }
    },
    methods: {
        dismissMessage() {
            if(!this.dismissable) {
                return
            }

            this.dismissed = true
            this.$emit('dismissed');
        }
    }
}
</script>

<style lang="scss" scoped>
.message-box {
    display: table;
    width: 100%;
    margin: 1em 0em;
    padding: 1em;
    border-radius: $borderRadSmall;
    box-shadow: $shadowNormal;
    background-color: $colorPlaceholder;

    .message-col {
        position: relative;
        display: table-cell;
        vertical-align: middle;

        &:first-of-type {
            width: 32px;
        }
    }

    p {
        font-size: 0.9em;
        opacity: 1;
    }

    img {
        position: absolute;
        width: 18px;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
    }

    &.message-type-success {
        background-color: $colorSuccess;
    }
    &.message-type-error {
        background-color: $colorAccent;
    }
}
</style>