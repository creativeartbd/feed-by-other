<template>
    <div v-if="loading" class="text-center">
        <b-spinner variant="primary" style="width: 3rem; height: 3rem" />
        <p>Loading...</p>
    </div>
    <div v-else>
        <h3 class="mb-2">{{ foundProduct ? foundProduct : '' }} Products </h3>
        <div class="custom-search">
            <!-- advance search input -->
            <b-row>
                <b-col md="11">
                    <b-row>
                        <b-col
                            md="3"
                            v-for="(header, index) in columns"
                            :key="index"
                        >
                            <b-form-group>
                                <b-input-group>
                                    <b-input-group-prepend>
                                        <b-form-select @change="debouncedSearch()" :options="search_operator_list" v-model="header.search_operator"></b-form-select>
                                    </b-input-group-prepend>
                                    <b-form-input
                                        :placeholder="header.label"
                                        type="text"
                                        class="d-inline-block"
                                        @input="debouncedSearch()" v-model="header.search_query"
                                    />
                                    <b-form-input
                                        :placeholder="header.label"
                                        type="text"
                                        class="d-inline-block"
                                        @input="debouncedSearch()"  v-model="header.search_to" v-if="header.search_operator === 'interval' "
                                    />
                                </b-input-group>                                
                            </b-form-group>
                        </b-col>
                    </b-row>
                </b-col>
                <b-col md="1" class="text-right" v-if="columns.length > 0">
                    <b-row>
                        <b-col md="12">
                            <b-button
                                variant="success"
                                class="btn-icon"
                                v-b-modal.modal-header-settings
                            >
                                <feather-icon icon="Edit2Icon" />
                            </b-button>
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
        </div>

        <b-modal
            id="modal-header-settings"
            ok-only
            ok-title="Save"
            centered
            size="lg"
            title="Check/uncheck the label to show/hide the column"
            @ok="handleSaveFields"
        >
            <b-card-text>
                <b-row>
                    <b-col
                        md="3"
                        v-for="(data, index) in productKeys"
                        :key="index"
                        class="mt-1"
                    >
                          
                        <b-form-checkbox
                            v-model="data.isChecked"
                            @change="setTableHeader($event, index, data.key)" v-if="data.key !== 'update_history'"
                        >
                            {{ data.label }}
                        </b-form-checkbox>
                    </b-col>
                </b-row>
            </b-card-text>
        </b-modal>

        <b-overlay :show="loadMore" rounded="sm">
            <vue-good-table
                style="background-color: #fff"
                @on-sort-change="onSortChange"
                :columns="columns"
                :rows="rows"
                :rtl="direction"
                :search-options="{
                    enabled: false,
                    externalQuery: searchTerm,
                }"
                :select-options="{
                    enabled: false,
                    selectOnCheckboxOnly: false, 
                    selectionInfoClass: 'custom-class',
                    selectionText: 'rows selected',
                    clearSelectionText: 'clear',
                    disableSelectInfo: true, 
                    selectAllByGroup: true,
                }"
                :pagination-options="{
                    enabled: false,
                    perPage: pageLength,
                }"
                theme="my-theme"
                @on-row-click="onRowClick"
            >
                <div slot="emptystate" class="alert alert-warning p-1 mb-0">
                    There are no products to be displayed. Add an import source to get started.
                </div>
                <template slot="table-row" slot-scope="props">
                    <!-- {{ props }} -->
                    <span
                        v-if="props.column.field === 'fullName'"
                        class="text-nowrap"
                    >
                        <b-avatar :src="props.row.avatar" class="mx-1" />
                        <span>{{ props.row.fullName }}</span>
                    </span>
                    <span
                        v-else-if="props.column.field === 'image_link'"
                        class="text-nowrap"
                    >
                        <span v-if="props.row.image_link.constructor === Array">
                            <!-- <b-avatar :src="props.row.image_link[0]" rounded/> -->
                            <b-img-lazy
                                v-bind="mainProps"
                                :src="props.row.image_link[0]"
                            ></b-img-lazy>
                        </span>
                        <span v-else>
                            <!-- <b-avatar :src="props.row.image_link" rounded/> -->
                            <b-img-lazy
                                v-bind="mainProps"
                                :src="props.row.image_link"
                            ></b-img-lazy>
                        </span>
                    </span>
                    <span v-else-if="props.column.field === 'price'">
                        {{ isNumber(props.row.price) ? currency_code + ' ' + props.row.price : props.row.price  }}
                    </span>
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
        </b-overlay>

        <div slot="emptystate" class="alert alert-warning p-1 mt-2 no-product-found" v-if="noProducts">
            Sorry, there are no products!
        </div>

        <b-sidebar
            id="sidebar-right"
            bg-variant="white"
            right
            backdrop
            shadow
            v-model="isShowing"
            @hidden="onHidden"
        >
            <sidebarProductDetails
                :data="singleRow"
                :currency_code="currency_code"
            />
        </b-sidebar>
    </div>
</template>

<script>
import {
    BAvatar,
    BPagination,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BRow,
    BCol,
    BImgLazy,
} from "bootstrap-vue";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import { VueGoodTable } from "vue-good-table";
import store from "@/store/index";
import { http } from "@/services/requests";
import sidebarProductDetails from "./sidebarProductDetails.vue";

export default {
    components: {
        VueGoodTable,
        BAvatar,
        BPagination,
        BFormGroup,
        BFormInput,
        BFormSelect,
        BRow,
        BCol,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
        sidebarProductDetails,
        BImgLazy,
    },
    data() {
        return {
            noProducts : false,
            loadMore : false,
            mainProps: {
                center: true,
                // fluidGrow: true,
                blank: true,
                blankColor: "#f2f2f7",
                class: "import-img",
            },
            isShowing: false,
            currency_code: "",
            pageLength: 5,
            dir: false,
            columns: [],
            rows: [],
            searchTerm: "",
            loading: true,
            loadingTable: true,
            productKeys: [],
            preDefinedHeader: [
                "id",
                "image_link",
                "title",
                "price",
                "brand",
                "link",
            ],
            nextPageUrl: "",
            page: 1,
            singleRow: "",
            user_id: "",
            projectToken: "",
            project_id: "",
            searchString: [],
            searchOperator: [],
            searchInterval : [],
            isSearchQuery: false,
            isSortQuery : false,
            sortQueryParams : '',
            debounceTimeout: 3000,
            foundProduct : '',
            search_operator_list : [
                { value : 'contain', text : 'Contain' },
                { value : 'notcontain', text : 'Does not contain' },
                { value : 'greater', text : 'Greater than' },
                { value : 'smaller', text : 'Smaller than' },
                { value : 'interval', text : 'Interval' },
            ]
        };
    },
    computed: {
        direction() {
            if (store.state.appConfig.isRTL) {
                // eslint-disable-next-line vue/no-side-effects-in-computed-properties
                this.dir = true;
                return this.dir;
            }
            // eslint-disable-next-line vue/no-side-effects-in-computed-properties
            this.dir = false;
            return this.dir;
        },
    },
    created() {
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.user_id = userData.id;
        // Get the local storage data
        let currentProject = JSON.parse(localStorage.getItem("lastProjectToken_" + userData.id ));
        let projectName = currentProject[0];
        // Set the page title
        document.title = this.capitalizeFirstLetter( projectName ) + ' - Products';
        // Set project token or redirect to dashboard page
        if (Object.keys(this.$route.params).length !== 0) {
            this.projectToken = this.$route.params.token;
            this.getImportList(this.projectToken, this.user_id);
        } else {
            this.$router.push("/dashboard");
        }
    },
    mounted() {
        this.handleScroll();
    },
    methods: {
        isNumber(x) {
            return /^\d*(?:[.,]\d+)?$/.test(x);
        },
        capitalizeFirstLetter(str) {
            return str[0].toUpperCase() + str.slice(1);
        },
        debouncedSearch() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(
                (e) => this.handleSearch(),
                500
            );
        },
        handleSearch() {

            let searchData = {};
            searchData.projectToken = this.projectToken;
            searchData.userId = this.user_id;
            searchData.search = [];

            this.columns.forEach( ( item, index ) => {
                searchData.search.push({
                    operator : item.search_operator,
                    search_field : item.field,
                    search_query : item.search_query, 
                    search_from : item.search_from, 
                    search_to : item.search_to, 
                });                
            });

            this.loadingTable = true; 
            this.noProducts = false;
            this.loadMore = true;

            http.post("projects/search", searchData )
                .then((response) => {
                    //console.log(response);

                    this.page += 1;
                    this.isSearchQuery = true;
                    this.foundProduct = response.data.product_value.total;

                    this.loadingTable = false;
                    this.loadMore = false;
                    this.rows = [];
                    this.nextPageUrl =
                        response.data.product_value.next_page_url;

                    response.data.product_value.data.forEach((item) => {
                        let temp_row = {};
                        this.productKeys.forEach((key) => {
                            temp_row = {
                                ...temp_row,
                                [key.key]: item[key.key],
                            };
                        });
                        this.rows.push(temp_row);
                    });
                })
                .catch((error) => {});
        },
        onSortChange(e) {
            // Set the loading overlay
            this.loadMore = true; 
            // Create search params object
            let searchParams = {};
            searchParams.userId = this.user_id;
            searchParams.projectToken = this.projectToken;

            let column = e[0].field;
            let orderBy = e[0].type;
            // Add sorting params to the search params object
            searchParams.sort = {
                [column]: orderBy,
            };
            // Store the sorting
            this.sortQueryParams = searchParams.sort;
            // Call http
            http.post("projects/import/sort", searchParams)
                .then((response) => {
                    this.page += 1;
                    this.isSearchQuery = true;
                    this.foundProduct = response.data.product_value.total;

                    this.loadingTable = false;
                    this.loadMore = false;
                    this.rows = [];
                    this.nextPageUrl =
                        response.data.product_value.next_page_url;

                    response.data.product_value.data.forEach((item) => {
                        let temp_row = {};
                        this.productKeys.forEach((key) => {
                            temp_row = {
                                ...temp_row,
                                [key.key]: item[key.key],
                            };
                        });
                        this.rows.push(temp_row);
                    });
                })
                .catch((error) => {});
        },
        handleSaveFields(e) {
            let params = {
                project_token: this.projectToken,
                columns: this.productKeys,
                user_id: this.user_id,
                project_id: this.project_id,
            };

            http.post("projects/import/save-columns", params)
                .then((response) => {
                    // Save field message
                })
                .catch(() => {});
        },
        collectSearchQ(e, field) {
            let finalObj = {};
            // this.searchString = [];
            this.searchString.push({
                [field]: e,
            });
        },
        setTableHeader(isChecked, index, key) {
            if (isChecked) {
                this.columns.push({
                    label: this.capitalizeFirstLetter(key.replace(/_/g, " ")),
                    field: key,
                    tdClass: "collapesCol",
                    hidden: false,
                    isChecked: this.preDefinedHeader.includes(key),
                });
            } else {
                this.columns = this.columns.filter((item) => {
                    return item.field !== key;
                });
            }
        },
        handleScroll(e) {
            window.onscroll = () => {
                let nextUrl;
                // calculate bottom of the page
                let bottomOfWindow = Math.round(document.documentElement.scrollTop + window.innerHeight) >= document.documentElement.scrollHeight;
                // If bottom of the page
                if (bottomOfWindow) {
                    if( this.nextPageUrl !== null ) {

                        let nextPageUrl = this.nextPageUrl.split("api/");
                        nextPageUrl = nextPageUrl[1];
                        // Show the loading bar
                        this.loadMore = true;
                        // If the scroll is for search field
                        if (this.isSearchQuery) {
     
                            let finalObj = {};

                            for (let i = 0; i < this.searchString.length; i++) {
                                Object.assign(finalObj, this.searchString[i]);
                            }

                            finalObj.projectToken = this.projectToken;
                            finalObj.userId = this.user_id;

                            // If its query for sorting
                            if( this.sortQueryParams !== null ) {
                                finalObj.sort = this.sortQueryParams;
                            }
                            
                            http.post(nextPageUrl, finalObj)
                            .then((response) => {

                                this.page += 1;
                                this.loadMore = false;
                                this.isSearchQuery = true;
                                this.loadingTable = false;
                                this.foundProduct = response.data.product_value.total;
                                this.nextPageUrl =
                                    response.data.product_value.next_page_url;

                                response.data.product_value.data.forEach(
                                    (item) => {
                                        let temp_row = {};
                                        this.productKeys.forEach((key) => {
                                            temp_row = {
                                                ...temp_row,
                                                [key.key]: item[key.key],
                                            };
                                        });
                                        this.rows.push(temp_row);
                                    }
                                );

                            })
                            .catch((response) => {});
                        } else if ( isSortQuery ) {

                        } else {
                            
                            http.get(nextPageUrl)
                            .then((response) => {
                                this.page += 1;
                                this.loadingTable = false;
                                this.loadMore = false;
                                this.foundProduct = response.data.product_value.total;
                                this.nextPageUrl = response.data.product_value.next_page_url;

                                response.data.product_value.data.forEach(
                                    (item) => {
                                        let temp_row = {};
                                        this.productKeys.forEach((key) => {
                                            temp_row = {
                                                ...temp_row,
                                                [key.key]: item[key.key],
                                            };
                                        });
                                        this.rows.push(temp_row);
                                    }
                                );
                            })
                            .catch((error) => {});
                        }
                    } else {
                        this.noProducts = true;
                    }
                }
            };
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        onRowClick(params) {
            //console.log( params )
            document.body.classList.add('body-overflow');
            this.isShowing = true;
            this.singleRow = '';
            this.singleRow = params.row;
        },
        onHidden() {
            document.body.classList.remove('body-overflow');
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
        getImportList(projectToken, userId) {
            // Set the list of products
            http.get("projects/import/all/" + projectToken + "/" + userId)
                .then((response) => {
                    console.log(response);
                    // hide the loading
                    this.loading = false;
                    this.loadingTable = false;
                    this.foundProduct = response.data.product_value.total;
                    this.currency_code = response.data.currency_code;
                    this.project_id = response.data.project_id;
                    this.page += 1;
                    this.nextPageUrl = response.data.product_value.next_page_url;

                    let parsedSavedColumns =
                        response.data.saved_columns !== null &&
                        response.data.saved_columns !== "0000-00-00"
                            ? JSON.parse(response.data.saved_columns)
                            : null;
                    // console.log( parsedSavedColumns );

                    if (parsedSavedColumns !== null) {
                        this.preDefinedHeader = [];
                        parsedSavedColumns.forEach((key) => {
                            if (key.isChecked) {
                                this.preDefinedHeader.push(key.key);
                            }
                        });
                    }

                    // Set the table header
                    response.data.product_keys.forEach((key, index) => {
                        // Set the product keys
                        // if (key !== "update_history") {
                            this.productKeys.push({
                                label: this.capitalizeFirstLetter(
                                    key.replace(/_/g, " ")
                                ),
                                key: key,
                                isChecked: this.preDefinedHeader.includes(key),
                            });
                        // }

                        // Set the columns
                        if (this.preDefinedHeader.includes(key)) {
                            this.columns.push({
                                label: this.capitalizeFirstLetter(
                                    key.replace(/_/g, " ")
                                ),
                                field: key,
                                tdClass: "collapesCol",
                                hidden: false,
                                isChecked: this.preDefinedHeader.includes(key),
                                search_operator : 'contain'
                            });
                        }
                    });

                    // Get the keys of products and set the table header
                    Object.keys(response.data.product_value.data).forEach(
                        (key) => {
                            if (key == "_id") return; // Skip the id and _id keys
                            this.productKeys.sort((a, b) => {
                                let ia = this.preDefinedHeader.indexOf(a.label);
                                let ib = this.preDefinedHeader.indexOf(b.label);
                                if (ia == -1) {
                                    if (ib == -1)
                                        return a.label.localeCompare(b.label);
                                    return 1;
                                } else if (ib == -1) {
                                    return -1;
                                }
                                return ia - ib;
                            });
                        }
                    );

                    //console.log( this.columns )
                    //console.log( this.productKeys )
                    // Set the table cell data
                    let temp_row = {};
                    response.data.product_value.data.forEach((item) => {
                        this.productKeys.forEach((key) => {
                            temp_row = { ...temp_row, [key.key]: item[key.key] };
                        });
                        //console.log( temp_row )
                        this.rows.push(temp_row);
                    });
              
                })
                .catch((error) => {
                    this.loading = false;
                    this.loadingTable = false;
                });
        },
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-good-table.scss";
</style>

<style scopped>
.custom-search {
    margin-bottom: 30px;
}
.vgt-inner-wrap {
    box-shadow: none;
}
.b-sidebar {
    width: 70%;
}

.import-img {
    object-fit: cover;
    width: 65px;
    height: 65px;
}
.body-overflow {
    overflow: hidden;
}
.no-product-found {
    display : table;
    margin : 0 auto;
}
</style>
