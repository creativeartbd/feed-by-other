<template>
    <div>
        <b-row class="mb-2">
            <b-col md="6">
                <h3>All Import</h3>
            </b-col>
            <b-col md="6 text-right">
                <b-button variant="success" v-b-modal.import-list-type>
                    <feather-icon icon="UploadCloudIcon" class="mr-50" />
                    <span class="align-middle">New Import</span>
                </b-button>
            </b-col>
        </b-row>
        <b-row>
            <b-col md="12">
                <!-- table -->
                <div v-if="loading" class="text-center">
                    <b-spinner variant="primary" style="width: 3rem; height: 3rem" />
                    <p>Loading...</p>
                </div>
                <vue-good-table
                    v-else
                    :columns="columns"
                    :rows="rows"
                    :search-options="{
                        enabled: true,
                        externalQuery: searchTerm,
                    }"
                    :select-options="{
                        enabled: false,
                        selectOnCheckboxOnly: true, // only select when checkbox is clicked instead of the row
                        selectionInfoClass: 'custom-class',
                        selectionText: 'rows selected',
                        clearSelectionText: 'clear',
                        disableSelectInfo: true, // disable the select info panel on top
                        selectAllByGroup: true, // when used in combination with a grouped table, add a checkbox in the header row to check/uncheck the entire group
                    }"
                    :pagination-options="{
                        enabled: false,
                        perPage: pageLength,
                    }"
                    :sort-options="{
                        enabled: false,
                    }"
                >
                    >
                    <template slot="table-row" slot-scope="props">
                        <!-- Column: Action -->

                        <span v-if="props.column.field === 'feed_type'">
                            <span v-if="'xml' == props.row.type">
                                <b-img
                                    :src="require('@/assets/images/projects/xml.png')"
                                    blank-color="#ccc"
                                    width="64"
                                    alt="placeholder"
                                    style="width: 30px"
                                />
                            </span>
                            <span v-if="'csv' == props.row.type">
                                <b-img
                                    :src="require('@/assets/images/projects/csv.png')"
                                    blank-color="#ccc"
                                    width="64"
                                    alt="placeholder"
                                    style="width: 30px"
                                />
                            </span>
                            <span v-if="'scrape' == props.row.type">
                                <b-img
                                    :src="require('@/assets/images/projects/scrape.png')"
                                    blank-color="#ccc"
                                    width="64"
                                    alt="placeholder"
                                    style="width: 30px"
                                />
                            </span>
                        </span>

                        <span
                            v-if="
                                props.column.field === 'last_notification' &&
                                props.row.last_notification !== null
                            "
                        >
                            <span class="alert alert-success p-1">{{ props.row.last_notification }}</span>
                        </span>

                        <div v-else-if="props.column.field === 'action'" class="d-inline">
                            <!-- {{ props.row }} -->
                            <!-- <span v-if="props.row.running === 1 "> -->
                            <span v-if="props.row.running == 1">
                                <span id="disabled-wrapper" class="d-inline-block" tabindex="0">
                                    <b-button variant="outline-success" class="mr-1" disabled>
                                        <b-spinner small />
                                        Running
                                    </b-button>
                                </span>
                                <b-tooltip target="disabled-wrapper"
                                    >Import is running, you must wait for it to finish to run it
                                    again.</b-tooltip
                                >
                            </span>
                            <b-button
                                v-else
                                variant="outline-dark"
                                class="mr-1"
                                @click="handleRunNow($event, props.row.id_feed)"
                            >
                                Run Now
                            </b-button>
                            
                            <!-- Feed Log -->
                            <span v-if="props.row.type === 'csv'">
                                <b-button
                                    variant="outline-dark"
                                    class="mr-1"
                                    v-b-modal.csv-update-modal
                                    @click="setIdFeedTitle(props.row, true)"
                                >
                                    <feather-icon
                                        icon="ActivityIcon"
                                        size="16"
                                        class="text-body align-middle mr-25"
                                    />
                                    Log
                                </b-button>
                            </span>
                            <span v-else-if="props.row.type === 'scrape'">
                                <b-button
                                    variant="outline-dark"
                                    class="mr-1"
                                    v-b-modal.scrape-update-modal
                                    @click="setIdFeedTitle(props.row, true)"
                                >
                                    <feather-icon
                                        icon="ActivityIcon"
                                        size="16"
                                        class="text-body align-middle mr-25"
                                    />
                                    Log
                                </b-button>
                            </span>
                            <span v-else>
                                <b-button
                                    variant="outline-dark"
                                    class="mr-1"
                                    v-b-modal.project-update-modal
                                    @click="setIdFeedTitle(props.row, true)"
                                >
                                    <feather-icon
                                        icon="ActivityIcon"
                                        size="16"
                                        class="text-body align-middle mr-25"
                                    />
                                    Log
                                </b-button>
                            </span>

                            <b-dropdown variant="link" toggle-class="text-decoration-none" no-caret>
                                <template v-slot:button-content>
                                    <feather-icon
                                        icon="MoreVerticalIcon"
                                        size="16"
                                        class="text-body align-middle mr-25"
                                    />
                                </template>

                                <!-- Update feed -->
                                <span v-if="props.row.type === 'csv'">
                                    <b-dropdown-item
                                        v-b-modal.csv-update-modal
                                        @click="setIdFeedTitle(props.row, false)"
                                    >
                                        <feather-icon icon="Edit2Icon" class="mr-50" />
                                        <span>Edit</span>
                                    </b-dropdown-item>
                                </span>

                                <span v-else-if="props.row.type === 'xml' ">
                                    <b-dropdown-item
                                        v-b-modal.project-update-modal
                                        @click="setIdFeedTitle(props.row, false)"
                                    >
                                        <feather-icon icon="Edit2Icon" class="mr-50" />
                                        <span>Edit</span>
                                    </b-dropdown-item>
                                </span>

                                <span v-else>
                                    <b-dropdown-item
                                        v-b-modal.scrape-update-modal
                                        @click="setIdFeedTitle(props.row, false)"
                                    >
                                        <feather-icon icon="Edit2Icon" class="mr-50" />
                                        <span>Edit</span>
                                    </b-dropdown-item>
                                </span>

                                <!-- Delere feed -->
                                <b-dropdown-item @click="confirmDelete(props.row, project_token, user_id)">
                                    <feather-icon icon="TrashIcon" class="mr-50" />
                                    <span>Delete</span>
                                </b-dropdown-item>

                            </b-dropdown>
                        </div>

                        <span v-else-if="props.column.field === 'status'">
                            <b-form-checkbox
                                v-model="props.formattedRow[props.column.field]"
                                checked="true"
                                class="custom-control-success"
                                name="check-button"
                                switch
                                inline
                                @change="handleStatusChange($event, props.row)"
                            >
                            </b-form-checkbox>
                        </span>

                        <!-- Column: Common -->
                        <span v-else>
                            {{ props.formattedRow[props.column.field] }}
                        </span>
                    </template>

                    <!-- pagination -->
                    <template slot="pagination-bottom" slot-scope="props">
                        <div class="d-flex justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-0 mt-1">
                                <span class="text-nowrap"> Showing 1 to </span>
                                <b-form-select
                                    v-model="pageLength"
                                    :options="['3', '5', '10']"
                                    class="mx-1"
                                    @input="
                                        (value) =>
                                            props.perPageChanged({
                                                currentPerPage: value,
                                            })
                                    "
                                />
                                <span class="text-nowrap"> of {{ props.total }} entries </span>
                            </div>
                            <div>
                                <b-pagination
                                    :value="1"
                                    :total-rows="props.total"
                                    :per-page="pageLength"
                                    first-number
                                    last-number
                                    align="right"
                                    prev-class="prev-item"
                                    next-class="next-item"
                                    class="mt-1 mb-0"
                                    @input="(value) => props.pageChanged({ currentPage: value })"
                                >
                                    <template #prev-text>
                                        <feather-icon icon="ChevronLeftIcon" size="18" />
                                    </template>
                                    <template #next-text>
                                        <feather-icon icon="ChevronRightIcon" size="18" />
                                    </template>
                                </b-pagination>
                            </div>
                        </div>
                    </template>
                </vue-good-table>
            </b-col>
        </b-row>

        <!-- List if import modal -->
        <b-modal id="import-list-type" cancel-variant="secondary" hide-footer centered size="lg">
            <template #modal-title>
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">
                    Select Import Type
                </h5>
            </template>
            <ProjectImportType />
        </b-modal>

        <!-- Update Project Import Modal  -->
        <b-modal
            id="project-update-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/xml.png')"
                    width="34"
                    alt="New Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">
                    {{ importTitle }}
                </h5>
            </template>
            <ProjectImportUpdate :id_feed="id_feed" :rows="rows" :islog="isLog" />
        </b-modal>

        <!-- Update webiste scrape modal -->
        <b-modal
            id="scrape-update-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/scrape.png')"
                    width="34"
                    :alt="importTitle"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">
                    {{ importTitle }}
                </h5>
            </template>
            <UpdateWebScrape :id_feed="id_feed" :rows="rows" :islog="isLog" />
        </b-modal>
        

        <!-- New XML Project Import Modal  -->
        <b-modal
            id="import-xml-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="New Import"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/xml.png')"
                    width="34"
                    alt="N  ew Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">New Import</h5>
            </template>
            <ProjectImportNew :importlist="rows" />
        </b-modal>

        <!-- New CSV Project Import Modal  -->
        <b-modal
            id="import-csv-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="New Import"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/csv.png')"
                    width="34"
                    alt="New Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">New Import</h5>
            </template>
            <NewCsvImport :importlist="rows" />
        </b-modal>

        <!-- Update CSV  -->
        <b-modal
            id="csv-update-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="New Import"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/csv.png')"
                    width="34"
                    alt="New Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">
                    {{ importTitle }}
                </h5>
            </template>
            <UpdateCsvImport :id_feed="id_feed" :rows="rows" :islog="isLog" />
        </b-modal>

        <!-- New web scrape -->
        <!-- New CSV Project Import Modal  -->
        <b-modal
            id="web-scrape-modal"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="Scraping the website"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/projects/scrape.png')"
                    width="34"
                    alt="Scraping the website"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">Scraping the website</h5>
            </template>
            <NewWebScrape :importlist="rows" />
        </b-modal>
    </div>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import ProjectImportUpdate from "./ProjectImportUpdate.vue";
import ProjectImportDelete from "./ProjectImportDelete.vue";
import ProjectImportType from "./ProjectImportType.vue";
import ProjectImportNew from "./ProjectImportNew.vue";
import NewCsvImport from "./csv-import/NewCsvImport.vue";
import UpdateCsvImport from "./csv-import/UpdateCsvImport.vue";
import NewWebScrape from "./web-scrape/NewWebScrape.vue";
import UpdateWebScrape from "./web-scrape/UpdateWebScrape.vue";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

import {
    BAvatar,
    BBadge,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BDropdown,
    BDropdownItem,
    BButton,
    BFormCheckbox,
} from "bootstrap-vue";

import { VueGoodTable } from "vue-good-table";
import store from "@/store/index";
import { http } from "@/services/requests";
import Ripple from "vue-ripple-directive";

export default {
    directives: {
        Ripple,
    },
    components: {
        UpdateCsvImport,
        ToastificationContent,
        BFormCheckbox,
        ProjectImportUpdate,
        ProjectImportDelete,
        ProjectImportType,
        ProjectImportNew,
        NewCsvImport,
        NewWebScrape,
        UpdateWebScrape,
        BCardCode,
        VueGoodTable,
        BAvatar,
        BBadge,
        BPagination,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BDropdown,
        BDropdownItem,
        BButton,
    },
    data() {
        return {
            isRunning: false,
            modalShow: false,
            isLog: false,
            loading: false,
            importTitle: "",
            id_feed: "",
            project_token: this.$route.params.token,
            project_db_name: "",
            user_id: "",
            pageLength: 10,
            dir: false,
            columns: [
                {
                    label: "Type",
                    field: "feed_type",
                },
                {
                    label: "Name",
                    field: "feed_name",
                },
                {
                    label: "Products",
                    field: "item_count",
                },
                {
                    label: "Status",
                    field: "status",
                },
                {
                    label: "Last notification",
                    field: "last_notification",
                },
                {
                    label: "Action",
                    field: "action",
                },
            ],
            rows: [],
            searchTerm: "",
        };
    },
    methods: {
        hideImportTypeModal() {
            this.$bvModal.hide("import-list-type");
        },
        running(id_feed) {
            http.get(
                "projects/import/list/running/" +
                    this.project_token +
                    "/" +
                    this.user_id +
                    "/" +
                    id_feed,
                { id_feed: id_feed }
            )
                .then((response) => {
                    if (response.data.running == 1) {
                        setTimeout(() => {
                            this.running(id_feed);
                        }, 3000);
                    } else {
                        this.rows.forEach((row) => {
                            if (row.id_feed === id_feed) {
                                row.running = 0;
                            }
                        });
                    }
                })
                .catch((error) => {
                    this.showToast("danger", 5000, "Alert!", error);
                });
        },
        handleRunNow(e, id_feed) {
            if (!id_feed) return;
            // Set the particular feed as Running...
            this.rows.forEach((row) => {
                if (row.id_feed === id_feed) {
                    row.running = 1;
                }
            });
            // Set the running column to 1
            this.update_running(id_feed);
        },
        update_running(id_feed) {
            http.put(
                "projects/import/list/update-running/" +
                    this.project_token +
                    "/" +
                    this.user_id +
                    "/" +
                    id_feed,
                { id_feed: id_feed }
            )
                .then((response) => {
                    if (response.data) {
                        this.running(id_feed);
                    }
                    // else {
                    //     this.showToast("danger", 5000, "Alert!", "Opps! Something wen't wrong.");
                    // }
                })
                .catch((error) => {
                    this.showToast("danger", 5000, "Alert!", error);
                });
        },
        confirmDelete(row, project_token, user_id) {
            this.$swal
                .fire({
                    title: "Delete Feed!",
                    text: "Are you sure to delele this '" + row.feed_name + "' feed?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Delete it!",
                    showLoaderOnConfirm: true,
                    customClass: {
                        confirmButton: "btn btn-danger",
                        cancelButton: "btn btn-outline-success ml-1",
                        title: "mb-2",
                    },
                    buttonsStyling: false,
                    preConfirm: () => {
                        // Remove item from the list of import
                        const index = this.rows.findIndex((key) => key.id_feed === row.id_feed);

                        return http
                            .delete(
                                "projects/import/mapping/delete/" +
                                    project_token +
                                    "/" +
                                    user_id +
                                    "/" +
                                    row.id_feed
                            )
                            .then((response) => {
                                // console.log(response);
                                if (!response.data) {
                                    throw new Error(response);
                                } else {
                                    // Remove item from the list of import
                                    const index = this.rows.findIndex(
                                        (key) => key.id_feed === row.id_feed
                                    );
                                    this.rows.splice(index, 1);
                                }
                            })
                            .catch((error) => {
                                // console.log(error);
                                this.$swal.showValidationMessage(
                                    "Opps! Somethong wen't wrong : " + error
                                );
                            });
                    },
                    allowOutsideClick: () => !this.$swal.isLoading(),
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        this.$swal.fire({
                            icon: "success",
                            title: "Success!",
                            text: "Your feed has been deleted.",
                            customClass: {
                                confirmButton: "btn btn-success",
                            },
                        });
                    }
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
        handleStatusChange(e, row) {
            http.put(
                "projects/import/list/update/" +
                    this.project_token +
                    "/" +
                    this.user_id +
                    "/" +
                    row.id_feed,
                { status: e }
            )
                .then((response) => {
                    // show notification message
                    this.showToast("success", 5000, "Success!", "Status updated successfully.");
                })
                .catch((error) => {});
        },
        setIdFeedTitle(row, isLog) {
            this.isLog = isLog;
            this.id_feed = row.id_feed;
            this.importTitle = row.feed_name;
        },
        getImportList() {
            if (!this.user_id && !this.$route.params.token) return;
            this.loading = true;
            http.get("projects/import/" + this.$route.params.token + "/" + this.user_id)
                .then((response) => {
                    this.rows = [];
                    console.log(response);
                    // console.log(typeof response.data.success)
                    if (response.data.success === true) {
                        this.loading = false;
                        response.data.output.forEach((entry) => {
                            this.rows.push({
                                type: entry.feed_type,
                                feed_type: "",
                                id_feed: entry.id_feed,
                                feed_name: entry.feed_name,
                                item_count : entry.item_count,
                                tpi: "",
                                last_notification: entry.last_notification.length
                                    ? entry.last_notification[0].content
                                    : null,
                                status: entry.status == 1 ? true : null,
                                running: entry.running,
                            });
                        });
                    } else {
                        this.loading = false;
                        this.showToast("danger", 5000, "Alert!", "Sorry, no project is found.");
                    }
                })
                .catch((error) => {
                    this.loading = false;
                    this.showToast(
                        "danger",
                        5000,
                        "Alert!",
                        "Opps! Something wen't wrong : " + error
                    );
                });
        },
        capitalizeFirstLetter(str) {
            return str[0].toUpperCase() + str.slice(1);
        }
    },
    mounted() {
        // get user data        
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.user_id = userData.id;
        // Get the local storage data
        let currentProject = JSON.parse(localStorage.getItem("lastProjectToken_" + userData.id ));
        let projectName = currentProject[0];
        // Set the page title
        document.title = this.capitalizeFirstLetter( projectName ) + ' - All Import';
        // If there is no token on the URL
        if (!this.$route.params.token) {
            this.$router.push("/");
        } else {
            this.getImportList();
        }

    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
.hideModal {
    display: none;
}
</style>

<style scoped>
.alert {
    margin-bottom: 0;
    font-size: 11px;
}
</style>
