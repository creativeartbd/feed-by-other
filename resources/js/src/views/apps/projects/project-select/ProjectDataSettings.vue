<template>
    <b-card>
        <validation-observer ref="simpleRules">
            <b-form>
                <b-row>
                    <b-col md="6">
                        <b-form-group>
                            <label>Select a country</label>
                            <validation-provider
                                #default="{ errors }"
                                name="country"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="project_data.country"
                                    :options="project_data.countries"
                                />
                                <!-- <v-select
                                    v-model="project_data.country"
                                    :options="project_data.countries"
                                    label="label"
                                    :state="errors.length > 0 ? false : null"
                                /> -->  

                                <!-- <v-select v-model="project_data.country" :options="project_data.countries" :reduce="country => country.value" label="label" :state="errors.length > 0 ? false : null"/> -->

                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="6">
                        <b-form-group>
                            <label>Select a Currency</label>
                            <validation-provider
                                #default="{ errors }"
                                name="currency"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="project_data.currency"
                                    :options="project_data.currencies"
                                />
                                <!-- <v-select
                                    :options="project_data.currencies"
                                    :reduce="text => text.value"
                                    v-model="project_data.currency"
                                    label="text"
                                    :state="errors.length > 0 ? false : null"
                                /> -->
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <b-col md="6">
                        <b-form-group>
                            <label>Select a Timezone</label>
                            <validation-provider
                                #default="{ errors }"
                                name="timezone"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="project_data.timezone"
                                    :options="project_data.timezones"
                                />
                                <!-- <v-select
                                    v-model="project_data.timezone"
                                    label="text"
                                    :options="project_data.timezones"
                                    :state="errors.length > 0 ? false : null"
                                    :reduce="timezone => timezone.value"
                                /> -->
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>
                    <b-col md="6"></b-col>
                    <!-- buttons -->
                      <!-- v-ripple.400="'rgba(255, 255, 255, 0.15)'" -->
                    <b-col cols="12">
                        <b-button
                            variant="primary"
                            class="mt-1 mr-1"
                            @click.prevent="updateForm"
                        >
                            <b-spinner small v-if="loadDone" />
                            Save changes
                        </b-button>
                    </b-col>
                    <!--/ buttons -->
                </b-row>
            </b-form>
        </validation-observer>
    </b-card>
</template>

<script>
import { BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BInputGroup, BInputGroupAppend, } from "bootstrap-vue";
import { http } from "@/services/requests";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required } from "@validations";
import vSelect from 'vue-select'

export default {
    components: {
        BButton, BForm, BFormGroup, BFormInput, BRow, BCol, BCard, BInputGroup, BInputGroupAppend, ValidationProvider, ValidationObserver, vSelect
    },
    props : [
        'project_data'
    ],
    data() {
        return {
            loadDone : false,
            required: "",
        };
    },
    computed: {
        
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
                            // show notification message
                            this.showToast(
                                "success",
                                5000,
                                "Success!",
                                "Project has been updated."
                            );
                        })
                        .catch((error) => {});
                }
            });
        },
    },
};
</script>
