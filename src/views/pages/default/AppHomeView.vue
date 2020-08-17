<template>
<div>
<h1>Startseite</h1>
    <button @click="toast('Test')">Send toast</button>
    <h1>Startseite</h1>
    <button @click="toast('Test')">Send toast</button>
    <h1>Startseite</h1>
    <button @click="toast('Test')">Send toast</button>
</div>
</template>

<script>
export default {
    methods: {
        toast(message) {
            this.successToast(message);
        }
    }
}
</script>