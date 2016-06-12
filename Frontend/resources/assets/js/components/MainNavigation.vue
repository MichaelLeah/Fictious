<template>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Fictitious Ltd</a>
            </div>
            <p class="navbar-text navbar-right">
                <a v-link="{ path: '/' }" class="navbar-link">Home</a>
                <a v-link="{ path: '/clients' }" class="navbar-link">Client List</a>
            </p>
        </div>
    </nav>
</template>

<style scoped></style>

<script>   
    export default {
        data: function() {
            return {
                
            }
        },
    }
</script>