<template>
    <div>
        <h2>Editor</h2>
        <hr class="interface large">

        
    </div>
</template>

<script>
import Api from '@/models/api.js';

export default {
    data() {
        return {
            loading: true,
            users: [],
            groups: []
        }
    },
    methods: {
        edit(event, done, id) {
            console.log(done)
            console.log(event.target)
            this.$router.push({name: 'panelUsers', params: {id}})
        },
        delete(id) {
            this.$router.push({name: 'panelUserIndex', params: {id}})
        }
    },
    mounted() {
        Api.get('user/all/').then((data) => {
            this.users = data
            console.log(data)
        }).finally(() => {
            this.loading = false
        })
    }
}
</script>

<style lang="scss" scoped>
@import '@/assets/scss/_variables.scss';
@import '@/assets/scss/tables.scss';

.profile-picture {
    display: inline-block;
    width: 38px;
    height: 38px;
    vertical-align: middle;
    background: $colorPlaceholder;
    border-radius: 50%;
}
.profile-info {
    padding-left: 1em;
    display: inline-block;
    vertical-align: middle;

    span {
        display: block;
        font-size: 0.8em;
        letter-spacing: 0.3px;
        opacity: 0.3;
    }
}

</style>