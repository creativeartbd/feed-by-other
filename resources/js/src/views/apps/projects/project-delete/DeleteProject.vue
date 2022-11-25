<template>
    <b-card-code title="Delete Project">
        <b-card-text class="mb-0">
            Are you sure to delete this project?
        </b-card-text>

        <div class="demo-inline-spacing">
            <!-- delete project -->
            <b-button
                v-ripple.400="'rgba(255, 159, 67, 0.15)'"
                variant="outline-danger"
                @click="makeToast('danger')"
            >
                Yes, Delete this project!
            </b-button>

            <!-- return me back -->
            <b-button
                v-ripple.400="'rgba(40, 199, 111, 0.15)'"
                variant="outline-success" @click="$router.back()"
            >
                No, Return me back.
            </b-button>
        </div>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { BButton, BCardText } from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { http } from "@/services/requests";

export default {
    components: {
        BCardCode,
        BButton,
        BCardText,
    },
    directives: {
        Ripple,
    },
    data() {
        return {
        };
    },
    methods: {
        makeToast(variant = null) {
            
            let currentUrl = window.location.pathname;
            let split = currentUrl.split("/");
            let project_id = parseInt(split[4]);
            
            let userData = JSON.parse(localStorage.getItem("userData"));
            let user_id = userData.id;

            http.delete("projects/delete/" + project_id)
                .then((response) => {
                    this.$bvToast.toast("Project has been deleted", {
                        title: `Variant ${variant || "default"}`,
                        variant,
                        solid: false,
                    });

                })
                .catch((error) => {
                    console.log(error);
                });
            
        },
    },
};
</script>
