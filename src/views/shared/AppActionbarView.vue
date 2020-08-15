<template>
    <div class="actionbar-container">
        <div class="bar-section">
            SEARCH
        </div>
        <div class="bar-section">
            <button class="btn btn-light" @click="login"><img src="@/assets/images/icons/key.svg" alt="">Anmelden</button>
        </div>
        
    </div>
</template>
<script>
export default {
    methods: {
        login() {
            this.showLogin();
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
</style>
