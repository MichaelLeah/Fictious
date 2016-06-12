<template>
    <div>
        {{ message }}
    </div>
</template>

<style scoped>
    div {
        background-color: lightgreen;
    }
</style>

<script>   
    export default {
        data: function() {
            return {
                message: 'Hello World!'
            }
        },
    }
</script>