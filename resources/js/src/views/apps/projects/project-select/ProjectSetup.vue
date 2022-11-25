<template>
    <b-card>
        <validation-observer ref="simpleRules">
            <b-form class="mt-2">
                <b-row>
                    <b-col sm="6">
                        <b-form-group label="name" label-for="project-name">
                            <validation-provider
                                #default="{ errors }"
                                name="Project Name"
                                rules="required"
                            >
                                <b-form-input
                                    v-model="project_data.name"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Project Name"
                                    name="name"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col sm="6">
                        <b-form-group label="Domain" label-for="project-domain">
                            <validation-provider
                                #default="{ errors }"
                                name="Project Domain"
                                rules="required"
                            >
                                <b-form-input
                                    v-model="project_data.website"
                                    name="domain"
                                    placeholder="Project Domain"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col cols="12">
                        <b-button
                            variant="primary"
                            class="mt-2 mr-1"
                            type="submit"
                            @click.prevent="updateForm"
                        >
                            <b-spinner small v-if="loadDone" />
                            Save Changes
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
    </b-card>
</template>

<script>
import {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BLink,
    BImg,
} from "bootstrap-vue";
import { ref } from "@vue/composition-api";
import { http } from "@/services/requests";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";

export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BLink,
        ValidationProvider,
        ValidationObserver,
    },
    props: ["project_data"],
    data() {
        return {
            required: "",
            loadDone : false,
        };
    },
    methods: {
        showToast(variant, timeout, title, message) {
            this.$toast(
                {
                    component: ToastificationContent,
                    props: {
                        title: title,
                        icon: "BellIcon",
                        text: this.$i18n.t(message),
                        variant,
                    },
                },
                {
                    timeout,
                }
            );
        },
        updateForm(e) {
            this.$refs.simpleRules.validate().then((success) => {
                if (success) {
                    let token = this.$route.params.token;
                    let userData = JSON.parse(localStorage.getItem("userData"));
                    let userId = userData.id;
                    this.loadDone = true;
                    http.put(
                        "projects/settings/update/" +
                            token +
                            "/" +
                            userId,
                        this.project_data
                    )
                        .then((response) => {
                            this.loadDone = false;
                            if( response.data.success ) {
                                this.showToast(
                                    "success",
                                    5000,
                                    "Success!",
                                    "Project has been updated."
                                );

                                let store = [];
                                    store[0] = response.data.name;
                                    store[1] = response.data.token;

                                let localProjectData = localStorage.getItem('lastProjectToken_'+userId);

                                localStorage.setItem( 'lastProjectToken_'+userId,  JSON.stringify(store) );
                            }
                        })
                        .catch((error) => {});
                }
            });
        },
    },
};
</script>
