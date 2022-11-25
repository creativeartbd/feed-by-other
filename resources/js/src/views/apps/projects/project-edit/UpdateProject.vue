<template>
    <b-card-code title="Update Project">
        <validation-observer ref="simpleRules">
            <b-form>
                <b-row>
                    <!--  Project Name field -->
                    <b-col md="6">
                        <b-form-group>
                            <label>This field is required</label>
                            <validation-provider
                                #default="{ errors }"
                                rules="required"
                                name="Project Name"
                            >
                                <b-form-input
                                    v-model="projects.name"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Project Name"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Project Website  -->
                    <b-col md="6">
                        <b-form-group>
                            <label>Enter your project URL</label>
                            <validation-provider
                                #default="{ errors }"
                                name="website"
                                rules="required|url"
                            >
                                <b-form-input
                                    v-model="projects.website"
                                    :state="errors.length > 0 ? false : null"
                                    placeholder="Enter project URL"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Project Country  -->
                    <b-col md="6">
                        <b-form-group>
                            <label>Select a Country</label>
                            <validation-provider
                                #default="{ errors }"
                                name="country"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="projects.country"
                                    :options="countries"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Project Currencies  -->
                    <b-col md="6">
                        <b-form-group>
                            <label>Select a Currency</label>
                            <validation-provider
                                #default="{ errors }"
                                name="currency"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="projects.currency"
                                    :options="currencies" 
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- Project Timezone  -->
                    <b-col md="6">
                        <b-form-group>
                            <label>Select a Timezone</label>
                            <validation-provider
                                #default="{ errors }"
                                name="timezone"
                                rules="required"
                            >
                                <b-form-select
                                    v-model="projects.timezone"
                                    :options="timezones"
                                />
                                <small class="text-danger">{{
                                    errors[0]
                                }}</small>
                            </validation-provider>
                        </b-form-group>
                    </b-col>

                    <!-- submit button -->
                    <b-col cols="12">
                        <b-button
                            variant="success"
                            type="submit"
                            @click.prevent="validationForm"
                        >
                            Update Project
                        </b-button>
                    </b-col>
                </b-row>
            </b-form>
        </validation-observer>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BFormInput,
    BFormGroup,
    BForm,
    BRow,
    BCol,
    BButton,
    BFormSelect,
} from "bootstrap-vue";
import {
    required,
    email,
    confirmed,
    url,
    between,
    alpha,
    integer,
    password,
    min,
    digits,
    alphaDash,
    length,
} from "@validations";
import { http } from "@/services/requests";


export default {
    components: {
        BCardCode,
        ValidationProvider,
        ValidationObserver,
        BFormInput,
        BFormGroup,
        BForm,
        BRow,
        BCol,
        BButton,
        BFormSelect,
    },
    data() {
        return {
            countries: [],
            currencies: [],
            timezones: [],
            currencySelected : '',
            project_id : '',
            projects: {
                name: "",
                website: "",
                country: "",
                currency: "",
                timezone: "",
                user_id: "",
                project_id: "",
            },
        };
    },
    mounted() {
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.getCountries();
        this.getCurrencies();
        this.getTimezones();
        this.projects.user_id = userData.id;
       
    },
    created() {
        console.log( this.projects );
        let currentUrl = window.location.pathname;
        let split = currentUrl.split("/");
        let project_id = parseInt(split[4]);
        this.project_id = project_id;
        
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.user_id = userData.id;

        let _this = this;
        let user_id = this.projects.user_id;
        http.get("projects/get/" + project_id)
            .then((response) => {
                let project = response.data;
                console.log( project );
                _this.projects.name = project.name;
                _this.projects.website = project.website;
                _this.projects.country = project.country_id;
                _this.projects.currency = project.currency_id;
                _this.projects.timezone = project.timezone_id;
                _this.projects.project_id = project.id_project;

            })
            .catch((error) => {
                console.log(error);
            });
    },
    methods: {
        getTimezones() {
            http.get("timezones")
                .then((response) => {
                    let allTimezones = response.data;
                    allTimezones.forEach((item) => {
                        this.timezones.push({
                            value: item.id_timezone,
                            text: item.time_zone,
                        });
                    });
                })
                .catch((error) => {
                    this.countries.push({
                        value: "",
                        text: "No country found",
                    });
                });
        },
        getCurrencies() {
            http.get("currencies")
                .then((response) => {
                    let allCurrencies = response.data;
                    allCurrencies.forEach((item) => {
                        this.currencies.push({
                            value: item.id_currency,
                            text: item.currency_name,
                        });
                    });
                })
                .catch((error) => {
                    this.countries.push({
                        value: "",
                        text: "No country found",
                    });
                });
        },
        getCountries() {
            http.get("countries")
                .then((response) => {
                    let allCountries = response.data;
                    allCountries.forEach((item) => {
                        this.countries.push({
                            value: item.id_country,
                            text: item.country_name,
                        });
                    });
                })
                .catch((error) => {
                    this.countries.push({
                        value: "",
                        text: "No country found",
                    });
                });
        },
        validationForm() {
            this.$refs.simpleRules.validate().then((success) => {
                if (success) {
                    // eslint-disable-next-line
                    let _this = this;
                    let project_id = _this.project_id;
                    // this.$validator.validateAll().then(result => {
                    http.put("projects/update/"+project_id, this.projects)
                        .then((response) => {
                            this.$vs.notify({
                                text: this.$i18n.t(
                                    "Project has been successfully updated."
                                ),
                                color: "success",
                                iconPack: "feather",
                                icon: "icon-check",
                            });
                        })
                        .catch((error) => {});
                }
            });
        },
    },
};
</script>
