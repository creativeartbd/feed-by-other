<template>
    <b-tabs v-model="tabIndex" v-on:activate-tab="tabActivated">
        <b-tab active>
            <template v-slot:title>
                <feather-icon
                    icon="SettingsIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                Settings
            </template>
            <validation-observer ref="validateSetting" tag="form">
                <b-row class="">
                    <b-col md="6">
                        <b-row>
                            <b-col md="12" class="mb-2">
                                <label
                                    >Name
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.left="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Mapping Name"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="mappingData.mapping_name"
                                        :state="errors.length > 0 ? false : null"
                                        id="mappingName"
                                        placeholder="Enter a maping name"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2">
                                <label
                                    >Import Period
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.left="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Import Period"
                                    rules="required"
                                >
                                    <b-form-select
                                        v-model="mappingData.import_period"
                                        :options="import_period_list"
                                        :state="errors.length > 0 ? false : null"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2">
                                <label
                                    >Authentication
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Authentication"
                                    rules="required"
                                >
                                    <b-form-select
                                        v-model="mappingData.auth.name"
                                        :options="authList"
                                        :state="errors.length > 0 ? false : null"
                                        @input="handleAuthChange"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <!-- Basic Digetst authentication -->
                            <b-col md="12" class="mb-2" v-if="showAuth">
                                <label
                                    >Username
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider #default="{ errors }" name="Username" rules="required">
                                    <b-form-input
                                        v-model="mappingData.auth.username"
                                        :state="errors.length > 0 ? false : null"
                                        id="username"
                                        placeholder="Enter username"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="showAuth">
                                <label
                                    >Password
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider #default="{ errors }" name="Password" rules="required">
                                    <b-form-input
                                        type="password"
                                        v-model="mappingData.auth.password"
                                        :state="errors.length > 0 ? false : null"
                                        id="password"
                                        placeholder="Enter Password"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <!-- Basic Digetst authentication -->
                            <b-col md="12" class="mb-2" v-if="showAuthHeader">
                                <label
                                    >Authentication header token
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Authentication header token"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="mappingData.auth.header"
                                        :state="errors.length > 0 ? false : null"
                                        id="authToken"
                                        placeholder="Enter authentication header token"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="showAws">
                                <label
                                    >AWS access key ID
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="AWS access key ID"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="mappingData.auth.aws_access_key"
                                        :state="errors.length > 0 ? false : null"
                                        id="awsKey"
                                        placeholder="Enter AWS access key ID"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="showAws">
                                <label
                                    >AWS secret access key
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="AWS secret access key"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="mappingData.auth.aws_secret_access_key"
                                        :state="errors.length > 0 ? false : null"
                                        id="awsSecretKey"
                                        placeholder="Enter AWS secret access key"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            
                        </b-row>
                    </b-col>
                    <b-col md="6">
                        <b-col md="12" class="mb-2">
                            <label
                                >XML file url
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                />
                            </label>
                            <validation-provider
                                #default="{ errors }"
                                name="XML file url"
                                rules="required|url"
                            >
                                <b-form-input
                                    v-model="mappingData.feed_file_url"
                                    :state="errors.length > 0 ? false : null"
                                    id="xmlFileUrl"
                                    placeholder="https://"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="12" class="mb-2">
                            <label
                                >Encoding
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                />
                            </label>
                            <validation-provider #default="{ errors }" name="Encoding" rules="required">
                                <b-form-select
                                    v-model="mappingData.encoding"
                                    :options="encodingName"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="12" class="mb-2">
                            <label
                                >Category delimitor
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                />
                            </label>
                            <validation-provider #default="{ errors }" name="Category delimitor">
                                <b-form-input
                                    v-model="mappingData.category_delimitor"
                                    :state="errors.length > 0 ? false : null"
                                    id="category_delimitor"
                                    placeholder="Enter category delimitor"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="12" class="mb-2">
                            <label
                                >Match products by
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                />
                            </label>
                            <validation-provider #default="{ errors }" name="Match products by">
                                <b-form-select
                                    v-model="mappingData.match_products_by"
                                    :options="match_products_list"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="12" class="mt-1 mb-1">
                            <label>
                                Remove Link Parameteres
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                /> </label
                            ><br />
                            <b-form-checkbox
                                v-model="mappingData.is_remove_link_params"
                                checked="true"
                                class="custom-control-success"
                                name="check-button"
                                switch
                                inline
                            >
                            </b-form-checkbox>
                        </b-col>
                        <b-col md="12" class="mt-1 mb-1" v-if="mappingData.is_remove_link_params">
                            <b-form-tags
                                v-model="mappingData.remove_link_params"
                                input-id="tags-pills"
                                tag-variant="primary"
                                tag-pills
                                size="lg"
                                separator=" "
                                placeholder="Exception parameters"
                                class="mb-2"
                            />
                        </b-col>
                        <b-col md="12" class="mt-1 mb-1">
                            <label>
                                Only update products if found
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.right="'Some tooltip text'"
                                /> </label
                            ><br />
                            <b-form-checkbox
                                v-model="mappingData.update_products_if_found"
                                checked="true"
                                class="custom-control-success"
                                name="check-button"
                                switch
                                inline
                            >
                            </b-form-checkbox>
                        </b-col>
                    </b-col>
                </b-row>
                <b-row class="mt-2 border-top m-0 mb-1">
                    <b-col cols="12 d-flex align-items-end justify-content-between pt-1 p-0">
                        <b-button variant="primary" type="submit" @click.prevent="validateSetting">
                            Next
                        </b-button>
                        <div>
                            <b-button
                                variant="success"
                                type="submit"
                                @click.prevent="validateSafety"
                                :class="{ disableBtn: submitDisable }"
                                class="float-right" :disabled="btnLoading"
                            >
                                <b-spinner small v-if="btnLoading" />
                                Create
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </validation-observer>
        </b-tab>
        <b-tab ref="editMapping" :title-item-class="{disabledTab : disableMappingTab === true}">
            <template v-slot:title>
                <feather-icon
                    icon="MenuIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                Mapping
            </template>
            <validation-observer ref="validateEditMapping" tag="form">
                <b-row class="">
                    <b-col md="12">
                        <div class="text-center" v-if="loadDone">
                            <b-spinner variant="primary" style="width: 3rem; height: 3rem" />
                            <p>
                                The feed is being processed in the background, please wait until the
                                process is complete as it may take several minutes.
                            </p>
                        </div>
                        <div v-else>
                            <div v-if="hasMappingError">
                                <b-alert variant="danger" show>
                                    <div class="alert-body">
                                        <feather-icon icon="InfoIcon" class="mr-50" />
                                        <span
                                            >The feed url you entered is invalid or the XML file is
                                            invalid. Please verify it and try again.</span
                                        >
                                    </div>
                                </b-alert>
                                <b-button
                                    class="text-right"
                                    variant="warning"
                                    type="warning"
                                    @click.prevent="tabIndex--"
                                    >Check Settings
                                </b-button>
                            </div>
                            <div v-else>
                                <b-row class="mb-2">
                                    <b-col md="7">
                                        <p>Map the fields from the feed url to the ones used in the platform</p>
                                    </b-col>
                                    <b-col md="5 text-right">
                                        <b-button
                                            variant="outline-success"
                                            class="mr-1"
                                            :disabled="isRefreshing"
                                            v-if="isRefreshing" 
                                        >
                                            <b-spinner small />
                                            Refreshing Feed
                                        </b-button>
                                        <b-button
                                            variant="outline-dark"
                                            class="mr-1"
                                            @click.prevent="handleRefreshFeed"
                                            :disabled="isRefreshing"
                                            v-else
                                        >
                                            Refresh Feed
                                        </b-button>

                                        <b-button
                                            variant="border-0"
                                            class="mr-1 ml-3"
                                        >
                                            Advanced Option
                                        </b-button>
                                        <b-form-checkbox
                                            v-model="mappingData.advanced_option"
                                            checked="null"
                                            name="check-button"
                                            class="custom-control-success align-middle"
                                            switch
                                            inline
                                            @change="handleAdvancedOption"
                                        >
                                        </b-form-checkbox>
                                    </b-col>
                                </b-row>

                                <b-row>
                                    <b-col md="12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            External Field (Feed)
                                                            <feather-icon
                                                                icon="AlertCircleIcon"
                                                                class="mr-50 my-icon"
                                                                v-b-tooltip.hover.top="'Some tooltip text'"
                                                            />
                                                        </th>
                                                        <th>
                                                            Internal Field (Plateform)
                                                            <feather-icon
                                                                icon="AlertCircleIcon"
                                                                class="mr-50 my-icon"
                                                                v-b-tooltip.hover.top="'Some tooltip text'"
                                                            />
                                                        </th>
                                                        <th>
                                                            Custom
                                                            <feather-icon
                                                                icon="AlertCircleIcon"
                                                                class="mr-50 my-icon"
                                                                v-b-tooltip.hover.top="'Some tooltip text'"
                                                            />
                                                        </th>
                                                        <th class="" v-if="mappingData.advanced_option">
                                                            Updates
                                                            <feather-icon
                                                                icon="AlertCircleIcon"
                                                                class="mr-50 my-icon"
                                                                v-b-tooltip.hover.top="'Some tooltip text'"
                                                            />
                                                        </th>
                                                        <th class="" v-if="mappingData.advanced_option">
                                                            Removes
                                                            <feather-icon
                                                                icon="AlertCircleIcon"
                                                                class="mr-50 my-icon"
                                                                v-b-tooltip.hover.top="'Some tooltip text'"
                                                            />
                                                        </th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                    <tr
                                                        v-for="(data, index) in mappingData.projectFieldOptions"
                                                        :key="index"
                                                        :class="{ 'd-none': data.text == 'ts_product_item_wrapper' }"
                                                    >
                                                        <td class="">
                                                            <b-form-input
                                                                v-model="data.value"
                                                                readonly
                                                            ></b-form-input>
                                                        </td>
                                                        <td class="">
                                                            <div v-if="data.custom">
                                                                <b-input-group>
                                                                    <b-input-group-prepend is-text>
                                                                        Custom
                                                                    </b-input-group-prepend>
                                                                    <b-form-input
                                                                        v-model="data.custom_field"
                                                                        placeholder="Enter field name"
                                                                    />
                                                                </b-input-group>
                                                            </div>
                                                            <div v-else>
                                                                <b-form-select
                                                                    v-model="data.text"
                                                                    :options="xmlFields"
                                                                />
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <b-form-checkbox
                                                                v-model="data.custom"
                                                                checked="null"
                                                                name="check-button"
                                                                class="custom-control-success"
                                                                switch
                                                                inline
                                                            >
                                                            </b-form-checkbox>
                                                        </td>
                                                        <td class="" v-if="mappingData.advanced_option">
                                                            <b-form-checkbox
                                                                v-model="data.updates"
                                                                checked="true"
                                                                name="check-button"
                                                                class="custom-control-success"
                                                                switch
                                                                inline
                                                                
                                                            >
                                                            </b-form-checkbox>
                                                        </td>
                                                        <td class="" v-if="mappingData.advanced_option">
                                                            <b-form-checkbox
                                                                v-model="data.removes"
                                                                checked="true"
                                                                name="check-button"
                                                                class="custom-control-success"
                                                                switch
                                                                inline
                                                                
                                                            >
                                                            </b-form-checkbox>
                                                        </td>
                                                    </tr>
                                                    <tr v-for="(row, index ) in newRow" :key="index + '-'">
                                                        <td>
                                                            <b-input-group>
                                                                <b-input-group-prepend is-text>
                                                                    Field Name
                                                                </b-input-group-prepend>
                                                                <b-form-input
                                                                    v-model="row.value"
                                                                    placeholder="Enter field name"
                                                                />
                                                            </b-input-group>
                                                        </td>
                                                        <td>
                                                            <b-input-group>
                                                                <b-input-group-prepend is-text>
                                                                    Field Value
                                                                </b-input-group-prepend>
                                                                <b-form-input
                                                                    v-model="row.custom_field"
                                                                    placeholder="Enter field value"
                                                                />
                                                            </b-input-group>
                                                        </td>
                                                        <td>
                                                            <b-button
                                                                variant="outline-danger"
                                                                type="submit"
                                                                @click.prevent="handleDeleteRow(index)"
                                                                >Delete</b-button
                                                            >
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div v-if="isDuplicateFieldValue" class="mt-3">
                                                <div class="alert alert-danger">Duplicate field name found.</div>
                                            </div>
                                            <div v-if="isMatched" class="mt-3">
                                                <div class="alert alert-danger p-1">You must map the "{{ mappingData.match_products_by }}" field.</div>
                                            </div>
                                        </div>
                                        <b-button
                                            variant="outline-dark"
                                            class="mb-2"
                                            type="submit"
                                            @click.prevent="handleAddNewRow"
                                            :disabled="isDisableNewRow"
                                            >Add new field</b-button
                                        >
                                    </b-col>
                                </b-row>

                                <b-row class="border-top m-0 mb-1">
                                    <b-col md="12" class="pt-1 p-0">
                                        <b-button
                                            variant="outline-primary"
                                            type="submit"
                                            @click.prevent="tabIndex--"
                                            >Previous</b-button
                                        >
                                        <b-button
                                            variant="primary"
                                            type="submit"
                                            @click.prevent="validateEditMapping"
                                        >
                                            Next
                                        </b-button>
                                        <b-button
                                            variant="success"
                                            type="submit"
                                            @click.prevent="validateSafety"
                                            :class="{ disableBtn: submitDisable }"
                                            class="float-right" :disabled="btnLoading"
                                        >
                                            <b-spinner small v-if="btnLoading" />
                                            Create
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </div>
                        </div>
                    </b-col>
                </b-row>
            </validation-observer>
        </b-tab>
        <b-tab ref="safety" :title-item-class="{ disabledTab: disableSafetyTab === true }">
            <template v-slot:title>
                <feather-icon
                    icon="LockIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                Safety
            </template>
            <validation-observer ref="validateSafety" tag="form">
                <b-row class="pb-3">
                    <b-col md="4">
                        <validation-provider
                            #default="{ errors }"
                            name="Action of difference"
                            rules="required"
                        >
                            <label
                                >Action on difference
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.left="'Some tooltip text'"
                                />
                            </label>
                            <b-form-select
                                v-model="mappingData.safety.action_diff"
                                :options="actionOnDifferentList"
                                :state="errors.length > 0 ? false : null"
                                @input="safetyChange"
                            />
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                    </b-col>
                    <b-col md="4" v-if="showSafety">
                        <validation-provider
                            #default="{ errors }"
                            name="Action on negative difference"
                            rules="required"
                        >
                            <label
                                >Action on negative difference
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.left="'Some tooltip text'"
                                />
                            </label>
                            <b-form-select
                                v-model="mappingData.safety.action_negative_diff"
                                :options="actionOnNegativetList"
                                :state="errors.length > 0 ? false : null"
                            />
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                    </b-col>
                    <b-col md="4" v-if="showSafety">
                        <validation-provider
                            #default="{ errors }"
                            name="Action on positive difference"
                            rules="required"
                        >
                            <label
                                >Action on positive difference
                                <feather-icon
                                    icon="AlertCircleIcon"
                                    class="mr-50 my-icon"
                                    v-b-tooltip.hover.left="'Some tooltip text'"
                                />
                            </label>
                            <b-form-select
                                v-model="mappingData.safety.action_positive_diff"
                                :options="actionOnPositivetList"
                                :state="errors.length > 0 ? false : null"
                            />
                            <small class="text-danger">{{ errors[0] }}</small>
                        </validation-provider>
                    </b-col>
                </b-row>
                <b-row class="border-top m-0 mb-1">
                    <b-col md="12" class="pt-1 p-0">
                        <b-button
                            variant="outline-primary"
                            type="submit"
                            @click.prevent="tabIndex--"
                            >Previous</b-button
                        >
                        <b-button
                            variant="success"
                            type="submit"
                            @click.prevent="validateSafety"
                            :class="{ disableBtn: submitDisable }"
                            class="float-right" :disabled="btnLoading"
                        >
                            <b-spinner small v-if="btnLoading" />
                            Create
                        </b-button>
                    </b-col>
                </b-row>
            </validation-observer>
        </b-tab>
    </b-tabs>
</template>

<script>
import {
    BMedia,
    BImg,
    BCardText,
    BButton,
    BModal,
    VBModal,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BCard,
} from "bootstrap-vue";
import BCardCode from "@core/components/b-card-code";
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { required, url } from "@validations";
import { http } from "@/services/requests";
import Cleave from "vue-cleave-component";

export default {
    components: {
        BCard,
        BCardText,
        BMedia,
        BImg,
        BButton,
        BModal,
        ValidationProvider,
        ValidationObserver,
        FormWizard,
        TabContent,
        BFormGroup,
        BFormInput,
        vSelect,
        BCardCode,
        BFormInvalidFeedback,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
        Cleave,
    },
    props : ['importlist'],
    data() {
        return {
            current_feed_token : '',
            btnLoading : false,
            checkMatchBy : '',
            isMatched : false,
            isDuplicateFieldValue : false,
            isDisableNewRow : false,
            newRow : [],
            isRefreshing: false,
            projectToken: "",
            id_mapping_processing: "",
            disableMappingTab: true,
            disableUpdateTab: true,
            disableSafetyTab: true,
            stepOne: false,
            submitDisable: false,
            showAuth: false,
            showAuthHeader: false,
            showAws: false,
            project_db_name: "",
            mapping_import_field: "",
            mapping_project_field: "",
            selectMap: [],
            importField: [],
            loadDone: true,
            required: "",
            showSafety: false,
            xmlFields: [],
            countMappingError : "",
            mappingData: {
                advanced_option : '',
                id_feed: "",
                id_project: "",
                mapping_name: "",
                feed_file_url: "",
                encoding: 1,
                import_period: 24,
                username: "",
                category_delimitor: "",
                feed_type : 'xml',
                match_products_by : 'id',
                is_remove_link_params: "",
                remove_link_params: [],
                update_products_if_found : false,
                auth: {
                    name: 1,
                    username: "",
                    password: "",
                    header: "",
                    aws_access_key: "",
                    aws_secret_access_key: "",
                },
                token: "",
                user_id: "",
                projectFieldOptions: [],
                field_extra_info : "",
                newProjectFieldOptions: [],
                safety: {
                    action_negative_diff: 0,
                    action_positive_diff: 0,
                    action_diff: "no_action",
                    auto_update_title: true,
                    auto_update_description: true,
                    auto_update_images: true,
                    import_product_variations: true,
                },
            },
            tabIndex: 1,
            hasMappingError: false,
            selectedEncoding: "",
            selected: null,
            countHTTP: 0,
            actionOnNegativetList: [],
            actionOnPositivetList: [],
            actionOnDifferentList: [
                { value: "no_action", text: "No Action" },
                { value: "send_notification", text: "Send Notification" },
                { value: "block_send_notification", text: "Block and send notification" },
            ],
            match_products_list : [
                { value : 'id', text : 'id' },
                { value : 'link', text : 'link' },
                { value : 'gtin', text : 'gtin' }, 
                { value : 'sku', text : 'sku' }, 
                { value : 'isbn', text : 'isbn' }, 
                { value : 'ean', text : 'ean' },
                { value : 'mpn', text : 'mpn' }
            ],
            import_period_list: [
                { value: 1, text: "1 Hour" },
                { value: 2, text: "2 Hours" },
                { value: 3, text: "3 Hours" },
                { value: 4, text: "4 Hours" },
                { value: 6, text: "6 Hours" },
                { value: 12, text: "12 Hours" },
                { value: 24, text: "24 Hours" },
                { value: 48, text: "48 Hours" },
                { value: 168, text: "168 Hours" },
            ],
            match_products_list : [
                { value : 'id', text : 'id' },
                { value : 'link', text : 'link' },
                { value : 'gtin', text : 'gtin' }, 
                { value : 'sku', text : 'sku' }, 
                { value : 'isbn', text : 'isbn' }, 
                { value : 'ean', text : 'ean' },
                { value : 'mpn', text : 'mpn' }
            ],
            encodingName: [
                { value: 1, text: "Automatically Detect" },
                { value: 2, text: "UTF-8" },
                { value: 3, text: "UTF-16 with byte order mark" },
                { value: 4, text: "UTF-16 (little endian)" },
                { value: 5, text: "UTF-16 (little endian)" },
                { value: 6, text: "UTF-16 (big endian)" },
                { value: 7, text: "UTF-32 with byte order mark" },
                { value: 8, text: "UTF-32 (little endian)" },
                { value: 9, text: "UTF-32 (big endian)" },
                { value: 10, text: "ISO-8859-1" },
                { value: 11, text: "ISO-8859-15" },
                { value: 12, text: "Windows-1252" },
                { value: 13, text: "Windows-1254" },
            ],
            authList: [
                { value: 1, text: "No authentication" },
                { value: 2, text: "Username and password (basic auth)" },
                { value: 3, text: "Username and password (digest auth)" },
                { value: 4, text: "Authentication header" },
                { value: 5, text: "Amazon S3" },
            ],
        };
    },
    directives: {
        "b-modal": VBModal,
    },
    mounted() {
        this.project_db_name = this.$route.params.db_name;
        this.mappingData.mapping_name = this.$route.params.db_name;
    },
    created() {
       
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.mappingData.user_id = userData.id;
        this.mappingData.username = userData.name;

        let localTokenName = "lastProjectToken_" + userData.id;
        let storageData = JSON.parse(localStorage.getItem(localTokenName));
        this.mappingData.token = storageData[1];

        for (let i = 1; i <= 100; i++) {
            if (i == 1) {
                this.actionOnNegativetList.push({
                    value: 0,
                    text: "Do Nothing",
                });
                this.actionOnPositivetList.push({
                    value: 0,
                    text: "Do Nothing",
                });
            }
            this.actionOnNegativetList.push({
                value: i,
                text: i + "% less item difference",
            });
            this.actionOnPositivetList.push({
                value: i,
                text: i + "% more item difference",
            });
        }

        // Get XML fields
        http.get("xml-fields")
            .then((response) => {
                let _this = this;
                _this.xmlFields.push({
                    value: "do_not_import",
                    text: "Do Not Import",
                    id: 0,
                });
                response.data.forEach(function (item) {
                    _this.xmlFields.push({
                        value: item.field_slug,
                        text: item.field_title,
                        id: item.id_xml_field,
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });
    },
    watch: {
        newRow: {
            handler( newData, oldData ) {  
                for ( let field of newData ) {
                    let find = this.mappingData.projectFieldOptions.filter(m => m.value == field.value);
                    if( find.length > 0 ) {
                        this.isDuplicateFieldValue = true,
                        this.isDisableNewRow = true;
                    } else {
                        this.isDuplicateFieldValue = false,
                        this.isDisableNewRow = false;
                    }
                }

                if(newData.length > 0 ) {
                    this.submitDisable = false;
                } else {
                    this.submitDisable = true;
                }
            }, 
            deep : true
        },
        mappingData: {
            handler(newData, oldData) {
                
                this.countMappingError = newData.projectFieldOptions.filter( ( item ) => {
                    return item.text !== 'Do Not Import' && item.text !== 'do_not_import';
                });

                this.checkMatchBy = newData.projectFieldOptions.filter( ( item ) => {
                    return item.text !== newData.match_products_by;
                });

                // Control Mapping Tab
                if (newData.mapping_name == 'undefined' || newData.mapping_name == null ) {
                    this.disableMappingTab = true;
                } else if ( newData.feed_file_url == "" || newData.feed_file_url == null ) {
                    this.disableMappingTab = true;
                } else {
                    this.disableMappingTab = false;
                }

                // Control update and safety Tab
                if (newData.projectFieldOptions.length == 0) {
                    this.disableUpdateTab = true;
                    this.disableSafetyTab = true;
                } else {
                    this.disableUpdateTab = false;
                    this.disableSafetyTab = false;
                }

                //Control Create button
                if (newData.mapping_name === "undefined") {
                    this.submitDisable = true;
                } else if (newData.feed_file_url === "" || newData.feed_file_url === null) {
                    this.submitDisable = true;
                } else if (newData.import_period === "" || newData.import_period === null) {
                    this.submitDisable = true;
                } else if (newData.encoding === "" || newData.encoding === null) {
                    this.submitDisable = true;
                } else if (newData.auth.name.value === "" || newData.auth.name.value === null) {
                    this.submitDisable = true;
                } else if (
                    newData.auth.name.value === 2 &&
                    (newData.auth.username === "" || newData.auth.password === "")
                ) {
                    this.submitDisable = true;
                } else if (
                    newData.auth.name.value === 3 &&
                    (newData.auth.username === "" || newData.auth.password === "")
                ) {
                    this.submitDisable = true;
                } else if (newData.auth.name.value === 4 && newData.auth.header === "") {
                    this.submitDisable = true;
                } else if (
                    newData.auth.name.value === 5 &&
                    (newData.auth.aws_access_key === "" ||
                        newData.auth.aws_secret_access_key === "")
                ) {
                    this.submitDisable = true;
                } else if (
                    newData.safety.action_diff === "send_notification" &&
                    (newData.safety.action_negative_diff === "" ||
                        newData.safety.action_positive_diff === "")
                ) {
                    this.submitDisable = true;
                } else if (newData.projectFieldOptions.length === 0) {
                    this.submitDisable = true;
                } else if( this.countMappingError.length == 0 ) {
                     this.submitDisable = true;
                }  else if ( this.checkMatchBy.length == newData.projectFieldOptions.length ) {
                    this.submitDisable = true;
                    this.isMatched = true;
                } else {
                    this.submitDisable = false;
                    this.isMatched = false;
                }
            },
            deep: true,
        },
    },
    methods: {
        handleAdvancedOption(e) {
            if ( e == false ) {
                if( this.mappingData.projectFieldOptions.length > 0 ) {
                    this.mappingData.projectFieldOptions.map( ( item ) => {
                        item.updates = true;
                        item.removes = true;
                    });
                }
            }
        },
        handleAddNewRow(e) {
            this.newRow.push({
                value : '', 
                custom_field : '',
                custom : '2',
                updates : '', 
                removes : '',
            });
        },
        handleDeleteRow( index ) {
            this.newRow.splice( index, 1);
            if( this.newRow.length == 0 ) {
                this.isDuplicateFieldValue = false;
                this.isDisableNewRow = false;
            }
        },
        handleRefreshFeed() {
            // Delete existing feed
            this.isRefreshing = true;
            let params = {
                user_id: this.mappingData.user_id,
                feed_file_url: this.mappingData.feed_file_url,
                project_token: this.projectToken,
                old_feed_token : this.current_feed_token,
            };
            http.post("projects/import/mapping/refresh", params)
                .then((response) => {
                    if (response.data.success == true) {
                        this.callHttp(true);
                    }
                })
                .catch((error) => {
                    this.showToast(
                        "danger",
                        5000,
                        "Alert!",
                        "Opps! Something wen't wrong: " + error
                    );
                });
        },
        tabActivated(newTabIndex, oldTabIndex, event) {
            if (1 == newTabIndex) {
                if (this.countHTTP === 0) {
                    this.callHttp();
                    this.countHTTP++;
                }
            }
        },
        handleAuthChange(authType) {
            if (2 === authType || 3 === authType) {
                this.showAuth = true;
                this.showAuthHeader = false;
                this.showAws = false;
            } else if (4 === authType) {
                this.showAuthHeader = true;
                this.showAuth = false;
                this.showAws = false;
            } else if (5 === authType) {
                this.showAws = true;
                this.showAuthHeader = false;
                this.showAuth = false;
            } else {
                this.showAuth = false;
                this.showAuthHeader = false;
                this.showAws = false;
            }
        },
        safetyChange(e) {
            if ("no_action" == e) {
                this.showSafety = false;
            } else {
                this.showSafety = true;
            }
        },
        validateSetting() {
            this.$refs.validateSetting.validate().then((success) => {
                if (success) {
                    this.tabIndex++;
                    if (this.countHTTP === 0) {
                        this.callHttp();
                        this.countHTTP++;
                    }
                }
            });
        },
        callHttp(isRefreshed) {
            // Prepare the data
            let importData = {
                token: this.mappingData.token,
                feed_file_url: this.mappingData.feed_file_url,
                name: this.mappingData.mapping_name,
                encoding: this.mappingData.encoding,
                auth_info: this.mappingData.auth,
                category_delimitor: this.mappingData.category_delimitor,
                feed_type : 'xml',
            };
            // Show the loading
            this.loadDone = true;
            // Get the mapping fields
            http.post("projects/import", importData)
                .then((response) => {
                    // console.log(response);
                    let res = response.data;
                    if (res.status !== "ready") {
                        setTimeout(() => {
                            //_this.mappingData.projectFieldOptions = [];
                            this.callHttp(isRefreshed);
                            this.loadDone = true;
                        }, 5000);
                    } else if (res.status === "ready") {
                        // if no fields name found
                        this.current_feed_token = res.feed_url_token;
                        this.countHTTP == 0;

                        if (res.field_names === false) {
                            this.loadDone = false;
                            this.hasMappingError = true;
                            this.isRefreshing = false;
                            this.countHTTP = 0;
                        } else {
                            this.loadDone = false;
                            this.isRefreshing = false;
                            this.hasMappingError = false;
                            this.mappingData.id_feed = res.id_feed;
                            this.id_mapping_processing = res.id_mapping_processing;
                            this.projectToken = res.id_project;
                            this.field_extra_info = res.field_extra_info;

                            if (isRefreshed) {
                                // Set the 
                                this.mappingData.projectFieldOptions.push({
                                    value: item,
                                    text: "do_not_import",
                                    custom: null,
                                    updates : true, 
                                    removes : true,
                                });

                                _this.mappingData.newProjectFieldOptions = [];
                             
                                res.field_names.forEach((item, index) => {
                                    this.mappingData.newProjectFieldOptions.push({
                                        value: item,
                                        text: "Do Not Import",
                                        custom: null,
                                        updates : true, 
                                        removes : true,
                                    });
                                });
                                // Combined the old mapping data with the new one and remove duplicate key/value
                                const filteredProjectFieldOptions = [
                                    ...new Map(
                                        [
                                            ...this.mappingData.newProjectFieldOptions,
                                            ...this.mappingData.projectFieldOptions,
                                        ].map((item) => [item.value, item])
                                    ).values(),
                                ];
                                this.mappingData.projectFieldOptions = [];
                                this.mappingData.projectFieldOptions = filteredProjectFieldOptions;
                               
                            } else {
                                this.mappingData.projectFieldOptions = [];
                                
                                res.field_names.forEach((item) => {
                                    this.mappingData.projectFieldOptions.push({
                                        value: item,
                                        text: "do_not_import",
                                        custom: null,
                                        updates : true, 
                                        removes : true,
                                    });
                                });
                            }
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        validateEditMapping() {
            this.$refs.validateEditMapping.validate().then((success) => {
                if (success) {
                    this.tabIndex++;
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
        validateSafety() {
            if (this.submitDisable === true) {
                // show notification message
                this.showToast(
                    "danger",
                    5000,
                    "Alert!",
                    "you must complete all mandatory fields in all tabs before you can create the feed import."
                );
            } else {
                this.$refs.validateSafety.validate().then((success) => {
                    if (success) {
                        this.submitDisable = true;
                        this.btnLoading = true;
                        // Add custom filed 
                        
                        this.mappingData.projectFieldOptions.push({
                            value : this.field_extra_info,
                            text : 'ts_product_item_wrapper',
                            custom : null
                        });
                        

                        // delete do not import
                        // if( this.mappingData.projectFieldOptions.length > 0 ) {
                        //     this.mappingData.projectFieldOptions = this.mappingData.projectFieldOptions.filter(( item ) => {
                        //         return item.text !== 'Do Not Import' || item.custom === true;
                        //     });
                        // }

                        // Merge newly added row
                        if( this.newRow.length > 0 ) {
                            this.mappingData.projectFieldOptions = [...this.newRow, ...this.mappingData.projectFieldOptions];
                        }
                        
                        http.post("projects/import/mapping", this.mappingData)
                            .then((response) => {
                                if (response.data.success) {

                                    // Add newly added row to import list
                                    this.importlist.unshift({
                                        feed_name : response.data.feed.feed_name,
                                        type : response.data.feed.feed_type,
                                        id_feed : response.data.feed.id_feed,
                                        last_notification : response.data.notification,
                                        status : true,
                                        running : 0,
                                        tpi : ""
                                    });

                                    // Tihs empty can be done in one for loop, I will check it later.
                                    this.mappingData.projectFieldOptions = [];
                                    this.mappingData.mapping_name = "";
                                    this.mappingData.feed_file_url = "";
                                    this.mappingData.encoding = "";
                                    this.mappingData.import_period = "";
                                    this.mappingData.auth.name = "";
                                    this.mappingData.auth.username = "";
                                    this.mappingData.auth.password = "";
                                    this.mappingData.auth.header = "";
                                    this.mappingData.auth.aws_access_key = "";
                                    this.mappingData.auth.aws_secret_access_key = "";

                                    this.countHTTP = 0;
                                    this.mapingDisabled = true;
                                    this.safetyDisable = true;
                                    this.submitDisable = false;
                                   
                                    // show notification message
                                    this.showToast(
                                        "success",
                                        5000,
                                        "Success!",
                                        "Successfully imported a new feed."
                                    );
                                } else {
                                    // show notification message
                                    this.showToast(
                                        "danger",
                                        5000,
                                        "Alert! ",
                                        "Something is wrong!."
                                    );
                                    
                                }
                                // Close moda
                                this.$bvModal.hide("import-xml-modal");
                                this.$bvModal.hide("import-list-type");
                                this.btnLoading = false;
                            })
                            .catch((error) => {
                                this.showToast(
                                    "danger",
                                    5000,
                                    "Alert! ",
                                    "Something is wrong!." + error
                                );
                            });
                    }
                });
            }
        },
        submitXml() {},
    },
};
</script>
<style scoped>

.table tr {
    border-left : 1px solid #dee2e6;
    border-right : 1px solid #dee2e6;
}

.table tr td {
    border-top : 0;
    border-bottom : 1px solid #dee2e6;
}

.disableBtn {
    opacity: 0.5;
}

[dir] .table th, [dir] .table td {
    padding: 0.72rem 1rem;
}

.nav-item .active {
    font-weight: bold !important;
    border-bottom-color: #000 !important;
}

.nav-tabs .nav-link:after {
    background: linear-gradient(30deg, #000000, rgb(0 0 0 / 50%)) !important;
}
</style>
<style lang="scss" scoped>
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
