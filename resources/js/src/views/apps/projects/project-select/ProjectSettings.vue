<template>
    <b-tabs
        vertical
        content-class="col-12 col-md-9 mt-1 mt-md-0"
        pills
        nav-wrapper-class="col-md-3 col-12"
        nav-class="nav-left"
    >
        <!-- Setup tab -->
        <b-tab active>
            <!-- title -->
            <template #title>
                <feather-icon icon="UserIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">Setup</span>
            </template>
            <ProjectSetup v-if="options.setup" :project_data="projects" />
        </b-tab>
        <!--/ Setup tab -->

        <!-- Data settings tab -->
        <b-tab>
            <!-- title -->
            <template #title>
                <feather-icon icon="LockIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">Data Settings</span>
            </template>

            <ProjectDataSettings v-if="options.data_settings" :project_data="projects" />
        </b-tab>
        <!--/ Data settings tab -->

        <!-- Connected account tab -->
        <b-tab>
            <!-- title -->
            <template #title>
                <feather-icon icon="LinkIcon" size="18" class="mr-50" />
                <span class="font-weight-bold">Connected Accounts</span>
            </template>

            <ProjectConnectedAccounts v-if="options.connected_accounts" :project_data="projects"/>
        </b-tab>
        <!--/ Connected account tab -->
    </b-tabs>
</template>

<script>
import { BTabs, BTab } from "bootstrap-vue";
import ProjectSetup from "./ProjectSetup.vue";
import ProjectDataSettings from "./ProjectDataSettings.vue";
import ProjectImport from "./ProjectImport.vue";
import ProjectConnectedAccounts from "./ProjectConnectedAccounts.vue";
import { http } from "@/services/requests";

export default {
    components: {
        BTabs, BTab, ProjectSetup, ProjectDataSettings, ProjectImport, ProjectConnectedAccounts,
    },
    data() {
        return {
            options: {},
            projectToken : '',
            userId : '',
            loadDone : false,
            projects: {
                name: "",
                website: "",
                country: "",
                currency: "",
                timezone: "",
                user_id: "",
                project_id: "",
                projectToken : "",
                countries : [],
                currencies : [],
                timezones : [], 
            },
        };
    },
    created() {

        // Set options value 
        this.options = {
            setup : 'setup',
            data_settings : 'data_settings',
            product_import : 'product_import',
            connected_accounts : 'connected_accounts',
        };

        // Get Project Token
        let getProjectToken = this.$route.params.token;
        this.projectToken = getProjectToken;

        // Get user data
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.userId = userData.id;

        // Get the local storage data
        let currentProject = JSON.parse(localStorage.getItem("lastProjectToken_" + userData.id ));
        let projectName = currentProject[0];
        // Set the page title
        document.title = this.capitalizeFirstLetter( projectName ) + ' - Update Project';

        http.get("projects/settings/" + getProjectToken + '/' +  userData.id,)
            .then((response) => {
                if( Object.keys(response.data).length === 0 ) {
                    this.$router.push("/");
                } else {
                    let project = response.data;
                    this.projects.name = project.name;
                    this.projects.website = project.website;
                    this.projects.country = project.country_id,
                    this.projects.currency = project.currency_id;
                    this.projects.timezone = project.timezone_id;
                    this.projects.project_id = project.id_project;
                }
            })
            .catch((error) => {
                console.log(error);
            });

        this.getCountries();
        this.getCurrencies();
        this.getTimezones();
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
        capitalizeFirstLetter(str) {
            return str[0].toUpperCase() + str.slice(1);
        },
        getTimezones() {
            http.get("timezones")
                .then((response) => {
                    let allTimezones = response.data;
                    allTimezones.forEach((item) => {
                        this.projects.timezones.push({
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
                        this.projects.currencies.push({
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
                    this.projects.allCountriesSelect = [];

                    allCountries.forEach((item) => {
                        this.projects.allCountriesSelect[item.id_country] = item.country_name;


                        this.projects.countries.push({
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
                    let projectToken = _this.projectToken;
                    _this.loadDone = true;
                    // this.$validator.validateAll().then(result => {
                    http.put("projects/settings/update/"+projectToken+"/"+_this.userId, this.projects)
                        .then((response) => {
                            _this.loadDone = false;
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
