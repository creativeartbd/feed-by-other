<template>
    <div class="navbar-container d-flex content align-items-center">
        <!-- Nav Menu Toggler -->
        <ul class="nav navbar-nav d-xl-none">
            <li class="nav-item">
                <b-link class="nav-link" @click="toggleVerticalMenuActive">
                    <feather-icon icon="MenuIcon" size="21" />
                </b-link>
            </li>
        </ul>

        <!-- Left Col -->

        <div class="bookmark-wrapper align-items-center flex-grow-1 d-none d-sm-block">
            <b-form inline>
                <v-select
                    v-model="selectedItem"
                    label="title"
                    :options="option"
                    @input="chooseProject"
                    :clearable="false"
                    class="nav-project-dropdown"
                />
                <!-- v-ripple.400="'rgba(255, 255, 255, 0.15)'" -->
                <b-button variant="primary" class="ml-1 create-project" v-b-modal.modal-lg> + </b-button>
            </b-form>

            <!-- <b-dropdown
                v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                split
                text="Project"
                variant="primary"
                left
            >
                <b-form-group
                    label="Search Project"
                    label-for="basicInput"
                    class="pt-0 pl-4 pr-4"
                >
                    <b-form-input name="search" id="basicInput" placeholder="Enter Keyword" @input="doSearch" />
                </b-form-group>
                <b-dropdown-divider />
                <div v-if="!searchResult.length">
                    <b-dropdown-item :to="{ path : '/dashboard/' + project.token }" v-for="( project, key ) in threeProject" :key="key">
                        {{ project.name }}
                    </b-dropdown-item>
                    <b-dropdown-divider />
                    <b-dropdown-item :to="{ path : '/dashboard/' + project.token }" v-for="( project, key ) in allProjects" :key="key+'P'">
                        {{ project.name }}
                    </b-dropdown-item>
                </div>
                <div v-else>

                    <b-dropdown-item :to="{ path : '/dashboard/' + project.token }" v-for="( project, key ) in searchResult" :key="key+'S'">
                        {{ project.name }}
                    </b-dropdown-item>
                </div>
            </b-dropdown> -->
            <!-- Bookmarks Container -->
            <!-- <bookmarks /> -->
            <b-modal
                id="modal-lg"
                no-close-on-backdrop
                ok-disabled
                centered
                size="lg"
                hide-footer
                title="Create New Project"
            >
                <b-card-text>
                    <!-- <b-card-code title=""> -->
                    <validation-observer ref="simpleRules">
                        <b-form>
                            <b-row>
                                <!--  Project Name field -->
                                <b-col md="6">
                                    <b-form-group>
                                        <label>Project Name</label>
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
                                            <small class="text-danger">{{ errors[0] }}</small>
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
                                            <small class="text-danger">{{ errors[0] }}</small>
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
                                                :state="errors.length > 0 ? false : null"
                                            />
                                            <!-- <v-select
                                                v-model="projects.country"
                                                :options="countries"
                                                :reduce="country => country.value"
                                                label="label"
                                                

                                            /> -->
                                            <small class="text-danger">{{ errors[0] }}</small>
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
                                                :state="errors.length > 0 ? false : null"
                                            />
                                            <!-- <v-select
                                                v-model="projects.currency"
                                                label="label"
                                                :options="currencies"
                                                
                                                :reduce="currency => currency.value"
                                            /> -->
                                            <small class="text-danger">{{ errors[0] }}</small>
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
                                                :state="errors.length > 0 ? false : null"
                                            />
                                            <!-- <v-select
                                                v-model="projects.timezone"
                                                label="label"
                                                :options="timezones"
                                                :state="errors.length > 0 ? false : null"
                                                :reduce="timezone => timezone.value"
                                            /> -->
                                            <small class="text-danger">{{ errors[0] }}</small>
                                        </validation-provider>
                                    </b-form-group>
                                </b-col>

                                <!-- submit button -->
                                <b-col cols="12">
                                    <b-button
                                        variant="primary"
                                        type="submit"
                                        @click.prevent="validationForm"
                                        :disabled="disableBtn" class="mb-1"
                                    >
                                        <!-- <div v-if="loadDone"> -->
                                        <b-spinner small v-if="loadDone" />
                                        <!-- <span class="sr-only">Loading...</span> -->
                                        <!-- </div> -->
                                        Add New Project
                                    </b-button>
                                </b-col>
                            </b-row>
                        </b-form>
                    </validation-observer>
                    <!-- </b-card-code> -->
                </b-card-text>
            </b-modal>
        </div>
        <b-navbar-nav class="nav align-items-center ml-auto">
            <!-- <locale /> -->
            <!-- <dark-Toggler class="d-none d-lg-block" /> -->
            <!-- <search-bar /> -->
            <!-- <cart-dropdown /> -->
            <notification-dropdown />
            <user-dropdown />
        </b-navbar-nav>
    </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import {
    BSpinner,
    BLink,
    BNavbarNav,
    BFormInput,
    BFormGroup,
    BForm,
    BRow,
    BCol,
    BButton,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BDropdownDivider,
    BCardText,
} from "bootstrap-vue";
import Locale from "./components/Locale.vue";
import SearchBar from "./components/SearchBar.vue";
import DarkToggler from "./components/DarkToggler.vue";
import CartDropdown from "./components/CartDropdown.vue";
import NotificationDropdown from "./components/NotificationDropdown.vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import UserDropdown from "./components/UserDropdown.vue";
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
import vSelect from "vue-select";

export default {
    data() {
        return {
            selectedItem: "",
            option: [],
            disableBtn: false,
            // activePrompt: false,
            loadDone: false,
            projects: {
                name: "",
                website: "",
                country: "",
                currency: "",
                timezone: "",
                user_id: "",
            },
            countries: [],
            currencies: [],
            timezones: [],
            foundProject: 0,
        };
    },
    components: {
        BLink,
        BDropdown,
        BDropdownDivider,
        BCardText,
        BDropdownItem,
        BButton,
        ToastificationContent,
        // Navbar Components
        BNavbarNav,
        Locale,
        SearchBar,
        DarkToggler,
        CartDropdown,
        NotificationDropdown,
        UserDropdown,
        ValidationProvider,
        ValidationObserver,
        BFormInput,
        BFormGroup,
        BForm,
        BRow,
        BFormSelect,
        BSpinner,
        vSelect,
    },
    props: {
        toggleVerticalMenuActive: {
            type: Function,
            default: () => {},
        },
    },
    created() {
        // Set the dropdown projecct
        // this.$watch(
        //     () => this.$route.params,
        //     (toParams, previousParams) => {
        //         this.selectedItem = {
        //             title : toParams.token,
        //         }
        //     }
        // )

        this.getProjects();
        this.getCountries();
        this.getCurrencies();
        this.getTimezones();
    },
    computed: {
        navbarSearchAndPinList() {
            return { pages: this.$store.state.navbarSearchAndPinList["pages"] };
        },
        starredPages() {
            return this.$store.state.starredPages;
        },
        starredPagesLimited: {
            get() {
                return this.starredPages.slice(0, 10);
            },
            set(list) {
                this.$store.dispatch("arrangeStarredPagesLimited", list);
            },
        },
        starredPagesMore: {
            get() {
                return this.starredPages.slice(10);
            },
            set(list) {
                this.$store.dispatch("arrangeStarredPagesMore", list);
            },
        },
        textColor() {
            return {
                "text-white":
                    this.$store.state.mainLayoutType === "vertical" &&
                    this.navbarColor !== (this.$store.state.theme === "dark" ? "#10163a" : "#fff"),
            };
        },

        validateForm() {
            return (
                this.projects.name !== "" &&
                this.projects.website !== "" &&
                this.projects.country !== "" &&
                this.projects.currency !== "" &&
                this.projects.timezone !== ""
            );
        },
    },
    methods: {
        extractFirstText(str) {
            const matches = str.match(/\((.*?)\)/);
            return matches ? matches[1] : str;
        },
        chooseProject(e) {
            
            if (this.foundProject > 0) {
                let token = this.extractFirstText(e.title);
                let projectName = e.title.replace(/[^a-z ]/gi, "");
                let userId = this.projects.user_id;
                let store = [];

                store[0] = projectName;
                store[1] = token;

                this.$router.push({
                    name: "select-project",
                    params: {
                        token: token,
                    },
                });

                // this.selectedItem =  {
                //     title : projectName + ' (' + token + ')',
                // };

                this.$router.resolve({
                    name: "project-import",
                    params: { token: token },
                });

                localStorage.setItem("lastProjectToken_" + userId, JSON.stringify(store));
            }
        },
        selected(obj) {
            this.$store.commit("TOGGLE_CONTENT_OVERLAY", false);
            this.$router.push(obj.pages.url).catch(() => {});
        },
        hnd_search_query_update(query) {
            // Show overlay if any character is entered
            this.$store.commit("TOGGLE_CONTENT_OVERLAY", !!query);
        },
        clearFields() {
            this.projects.name = "";
            this.projects.website = "";
            this.projects.timezone = "";
            this.projects.country = "";
            this.projects.currency = "";
        },
        chooseCountry(event) {
            this.getTimezones(event);
            this.getCurrencies(event);
        },

        getProjects() {
            let _this = this;
            let userData = JSON.parse(localStorage.getItem("userData"));
            let user_id = userData.id;
            let parsestoredItem = localStorage.getItem("lastProjectToken_" + user_id);
            this.projects.user_id = userData.id;

            http.get("projects/" + user_id)
                .then((response) => {
                    if (Object.keys(response.data).length !== 0) {
                        let token = this.$route.params.token;
                        this.foundProject = 1;
                        response.data.forEach(function (entry, index) {
                            if (token == entry.token) {
                                // Set the seelectd item
                                _this.selectedItem = {
                                    title: entry.name + " (" + entry.token + ")",
                                };
                            }
                            _this.option.push({
                                title: entry.name + " (" + entry.token + ")",
                            });
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
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

        validationForm() {
            this.$refs.simpleRules.validate().then((success) => {
                if (success) {
                    // eslint-disable-next-line
                    this.loadDone = true;
                    let _this = this;
                    this.disableBtn = true;

                    console.log( this.projects )
                    // this.$validator.validateAll().then(result => {
                    http.post("projects", this.projects)
                        .then((response) => {
                            console.log( response )
                            if (response.data.message === "success") {
                                this.loadDone = false;
                                this.disableBtn = false;
                                // show notification message
                                this.showToast(
                                    "success",
                                    5000,
                                    "Congratulation!",
                                    "Project has been successfully created"
                                );

                                this.option = [];
                                this.getProjects();
                                this.$bvModal.hide("modal-lg");
                                // Clear the input fields
                                this.clearFields();
                            } else {
                                // show notification message
                                this.showToast(
                                    "danger",
                                    5000,
                                    "Alert!",
                                    "OPPs! Something went wrong"
                                );
                            }
                        })
                        .catch((error) => {
                            this.showToast(
                                "danger",
                                5000,
                                "Alert!",
                                "OPPs! Something went wrong:" + error
                            );
                        });
                }
            });
        },
    },
};
</script>

<style scoped>
.nav-project-dropdown.v-select.vs--single.vs--searchable {
    width: calc(100% - 100px) !important;
    max-width: 450px;
}

.create-project {
    height : 35px;
}
</style>
