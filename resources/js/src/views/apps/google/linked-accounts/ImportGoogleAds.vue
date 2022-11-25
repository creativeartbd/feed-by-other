<template>
    <div>
        <b-row>
            <b-col md="6">
                <b-form-group
                    id="account-name"
                    label="Account name"
                    label-for="account-name"
                    description="Enter your google account name"
                >
                    <b-form-input
                        id="account-name"
                        type="text"
                        placeholder="Enter your google account name"
                        required
                    ></b-form-input>
                </b-form-group>
            </b-col>
            <b-col md="6">
                <b-form-group
                    id="relink"
                    label="Re-link account"
                    label-for="relink"
                >
                    <b-button type="button" variant="success">Re-link</b-button>
                </b-form-group>
            </b-col>
            <b-col md="12">
                <a href="http://localhost:3000/api/auth/redirect" variant="primary" class="float-right">Submit</a>
                <!-- <b-button type="submit" variant="primary" class="float-right" @click="onSubmit">Submit</b-button> -->
            </b-col>
        </b-row>
    </div>
</template>

<script setup lang="ts">
import { http } from "@/services/requests";

export default {
    methods: {
        onSubmit() {
            http.get('auth/redirect')
            .then( ( response ) => {
                console.log( response)
            })
            .catch( () => {

            });
        }
    },
}
</script>

<style scoped></style>
