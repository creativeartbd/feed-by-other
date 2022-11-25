<template>
    <b-card-code title="All Projects">
        <!-- search input -->
        <div class="custom-search d-flex justify-content-end">
            <b-form-group>
                <div class="d-flex align-items-center">
                    <label class="mr-1">Search Project</label>
                    <b-form-input
                        v-model="searchTerm"
                        placeholder="Search Project"
                        type="text"
                        class="d-inline-block"
                    />
                </div>
            </b-form-group>
        </div>

        <!-- table -->
        <vue-good-table
            :columns="columns"
            :rows="rows"
            :search-options="{
                enabled: true,
                externalQuery: searchTerm,
            }"
            :select-options="{
                enabled: true,
                selectOnCheckboxOnly: true, // only select when checkbox is clicked instead of the row
                selectionInfoClass: 'custom-class',
                selectionText: 'rows selected',
                clearSelectionText: 'clear',
                disableSelectInfo: true, // disable the select info panel on top
                selectAllByGroup: true, // when used in combination with a grouped table, add a checkbox in the header row to check/uncheck the entire group
            }"
            :pagination-options="{
                enabled: true,
                perPage: pageLength,
            }"
        >
            <template slot="table-row" slot-scope="props">
                <!-- Column: Action -->
                <span v-if="props.column.field === 'action'">
                    <span>
                        <b-dropdown
                            variant="link"
                            toggle-class="text-decoration-none"
                            no-caret
                        >
                            <template v-slot:button-content>
                                <feather-icon
                                    icon="MoreVerticalIcon"
                                    size="16"
                                    class="text-body align-middle mr-25"
                                />
                            </template>
                            <b-dropdown-item :to="{path : 'update/' + props.row.pid }">
                                <feather-icon icon="Edit2Icon" class="mr-50" />
                                <span>Edit</span>
                            </b-dropdown-item>
                            <b-dropdown-item :to="{path : 'delete/' + props.row.pid }">
                                <feather-icon icon="TrashIcon" class="mr-50" />
                                <span>Delete</span>
                            </b-dropdown-item>
                        </b-dropdown>
                    </span>
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
                        <span class="text-nowrap">
                            of {{ props.total }} entries
                        </span>
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
                            @input="
                                (value) =>
                                    props.pageChanged({ currentPage: value })
                            "
                        >
                            <template #prev-text>
                                <feather-icon
                                    icon="ChevronLeftIcon"
                                    size="18"
                                />
                            </template>
                            <template #next-text>
                                <feather-icon
                                    icon="ChevronRightIcon"
                                    size="18"
                                />
                            </template>
                        </b-pagination>
                    </div>
                </div>
            </template>
        </vue-good-table>
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import { BAvatar, BBadge, BPagination, BFormGroup, BFormInput, BFormSelect, BDropdown, BDropdownItem } from "bootstrap-vue";
import { VueGoodTable } from "vue-good-table";
import store from "@/store/index";
import { http } from "@/services/requests";

export default {
    components: {
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
    },
    data() {
        return {
            user_id: "",
            pageLength: 3,
            dir: false,
            columns: [
                {
                    label: "Project Name",
                    field: "pname",
                },
                {
                    label: "Website",
                    field: "website",
                },
                {
                    label: "Country Name",
                    field: "cname",
                },
                {
                    label: "Currency",
                    field: "currency",
                },
                {
                    label: "Time Zone",
                    field: "timezone",
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
    created() {
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.user_id = userData.id;

        let _this = this;
        let user_id = this.user_id;
        http.get("projects/" + user_id)
            .then((response) => {
                _this.projectsData = response.data;

                response.data.forEach(function (entry) {
                    _this.rows.push({
                        pid : entry.id_project,
                        pname: entry.name,
                        website: entry.website,
                        cname: entry.country_name,
                        currency: entry.currency_name,
                        timezone: entry.time_zone,
                    });
                    console.log(entry);
                });
            })
            .catch((error) => {
                console.log(error);
            });
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
</style>
