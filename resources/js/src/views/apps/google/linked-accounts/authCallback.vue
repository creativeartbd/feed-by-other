<template>
    <div>
        <h1>Hello ee  there </h1>
    </div>
</template>

<script>
import { http } from "@/services/requests";

export default {
    mounted() {
        
        http.post('auth/callback/?code=4%2F0AfgeXvvpN9O39azsA5GgwBgk1k_aenhwBj7FbVEu-8lHAs6w9TsgHM0-Jhf8G0DMVjOBEQ&scope=email%20profile%20openid%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email&authuser=0&prompt=consent')
        .then( ( response ) => {
            console.log( response)
        })
        .catch( ( error ) => {
            console.log( error )
        });
    },
}
</script>

<style scoped>

</style>