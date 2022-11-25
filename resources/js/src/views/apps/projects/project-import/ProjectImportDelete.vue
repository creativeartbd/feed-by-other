<template>
    <div>
        <div>
      <h5>Confirm Button Text</h5>
      <b-button
        v-ripple.400="'rgba(113, 102, 240, 0.15)'"
        variant="outline-primary"
        @click="confirmText"
      >
        Confirm Text
      </b-button>
    </div>
        <b-alert variant="danger" show>
            <h4 class="alert-heading">Delete  {{ id_feed }}</h4>
            <div class="alert-body">
                <span>Are you sure to delete this import?.</span>
            </div>
        </b-alert>
        <b-button variant="danger" @click="deleteImport"> Yes, Delete it! </b-button>
        <b-button variant="success" @click="goBack"> Go Back</b-button>
    </div>
</template>

<script>
import { BAlert } from "bootstrap-vue";
import { http } from "@/services/requests";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
    components: {
        BAlert, ToastificationContent
    },
    data() {
        return {
            user_id: "",
            project_token: "",
        };
    },
    props: ["id_feed", "rows"],
    created() {
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.user_id = userData.id;
        this.project_token = this.$route.params.token;
    },
    methods: {
        goBack() {
            this.$bvModal.hide("project-delete-modal");
        },
        showToast( variant, timeout, title, message ) {
            this.$toast({
                component: ToastificationContent,
                props: {
                    title: title,
                    icon: 'BellIcon',
                    text: this.$i18n.t( message ),
                    variant,
                },
            },
            {
                timeout,
            })
        },
        deleteImport() {
            http.delete(
                "projects/import/mapping/delete/" +
                    this.project_token +
                    "/" +
                    this.user_id +
                    "/" +
                    this.id_feed
            )
                .then((response) => {
                    console.log(response)
                    if (response.data) {
                        // show notification message
                        this.showToast('success', 5000, 'Success!', 'Feed has been deleted successfully.');
                        // Close the add new import popup modal
                        this.$bvModal.hide("project-delete-modal");
                        // Remove item from the list of import
                        const index = this.rows.findIndex( key => key.id_feed === this.id_feed );
                        this.rows.splice( index, 1);
    
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
