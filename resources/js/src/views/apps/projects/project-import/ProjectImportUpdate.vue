<template>
    <div>
        <div v-if="loading" class="text-center">
            <b-spinner variant="primary" style="width: 3rem; height: 3rem" />
            <p>Loading...</p>
        </div>
        <b-tabs v-model="tabIndex" v-else class="xml-nav-tabs">
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
                                    <validation-provider #default="{ errors }" name="name" rules="required">
                                        <b-form-input
                                            v-model="mappingData.mapping_name"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="Enter a name"
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
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Username"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="mappingData.auth.username"
                                            :state="errors.length > 0 ? false : null"                                    
                                            placeholder="Enter username" autocomplete="username"
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
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Password"
                                        rules="required"
                                    >
                                        <b-form-input
                                            type="password"
                                            v-model="mappingData.auth.password"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="Enter Password" autocomplete="current-password"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
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
                                            id="name"
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
                                            id="name"
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
                                            id="name"
                                            placeholder="Enter AWS secret access key"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                            </b-row>
                        </b-col>
                        <b-col md="6">
                            <b-row>
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
                                            id="xml-file-url"
                                            placeholder="Enter your xml file URL"
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
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Encoding"
                                        rules="required"
                                    >
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
                                <b-col md="12" class="mb-1">
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
                                <b-col md="12" class="mb-1" v-if="mappingData.is_remove_link_params">
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
                                <b-col md="12" class="mb-1">
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
                            </b-row>
                        </b-col>
                    </b-row>
                    <b-row class="m-0 mb-1">
                        <b-col cols="12" class="border-top pt-1 p-0">
                            <b-button
                                variant="primary"
                                type="submit"
                                @click.prevent="validateSetting"
                            >
                                Next
                            </b-button>
                            <b-button
                                variant="success"
                                type="submit"
                                @click.prevent="validateSafety"
                                :class="{ disableBtn: submitDisable }"
                                class="float-right"
                                :disabled="btnLoading"
                            >
                                <b-spinner small v-if="btnLoading" />
                                Update
                            </b-button>
                        </b-col>
                    </b-row>
                </validation-observer>
            </b-tab>
            <b-tab ref="editMapping">
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
                                    The feed is being processed in the background, please wait until
                                    the process is complete as it may take several minutes.
                                </p>
                            </div>
                            <div v-else>
                                <div v-if="hasMappingError">
                                    <b-alert variant="danger" show>
                                        <div class="alert-body">
                                            <feather-icon icon="InfoIcon" class="mr-50" />
                                            <span
                                                >The feed url you entered is invalid or the XML file
                                                is invalid. Please verify it and try again.</span
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
                                            <p>
                                                Map the fields from the feed url to the ones used in
                                                the platform
                                            </p>
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

                                            <b-button variant="border-0" class="mr-1 ml-3">
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        External Field (Feed)
                                                        <feather-icon
                                                            icon="AlertCircleIcon"
                                                            class="mr-50 my-icon"
                                                            v-b-tooltip.hover.top="
                                                                'Some tooltip text'
                                                            "
                                                        />
                                                    </th>
                                                    <th>
                                                        Internal Field (Plateform)
                                                        <feather-icon
                                                            icon="AlertCircleIcon"
                                                            class="mr-50 my-icon"
                                                            v-b-tooltip.hover.top="
                                                                'Some tooltip text'
                                                            "
                                                        />
                                                    </th>
                                                    <th>
                                                        Custom
                                                        <feather-icon
                                                            icon="AlertCircleIcon"
                                                            class="mr-50 my-icon"
                                                            v-b-tooltip.hover.top="
                                                                'Some tooltip text'
                                                            "
                                                        />
                                                    </th>
                                                    <th class="" v-if="mappingData.advanced_option">
                                                        Updates
                                                        <feather-icon
                                                            icon="AlertCircleIcon"
                                                            class="mr-50 my-icon"
                                                            v-b-tooltip.hover.top="
                                                                'Some tooltip text'
                                                            "
                                                        />
                                                    </th>
                                                    <th class="" v-if="mappingData.advanced_option">
                                                        Removes
                                                        <feather-icon
                                                            icon="AlertCircleIcon"
                                                            class="mr-50 my-icon"
                                                            v-b-tooltip.hover.top="
                                                                'Some tooltip text'
                                                            "
                                                        />
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr
                                                    v-for="(
                                                        data, index
                                                    ) in mappingData.projectFieldOptions"
                                                    :key="index + 1 + '--'"
                                                    :class="{ 'd-none': data.text == 'ts_product_item_wrapper' }"
                                                >
                                                    <td>
                                                        <b-form-input
                                                            v-model="data.value"
                                                            readonly
                                                        ></b-form-input>
                                                    </td>
                                                    <td>
                                                        <b-input-group v-if="data.custom == 1">
                                                            <b-input-group-prepend is-text>
                                                                Custom
                                                            </b-input-group-prepend>
                                                            <b-form-input
                                                                v-model="data.custom_field"
                                                                placeholder="Enter field name"
                                                            />
                                                        </b-input-group>
                                                        <div v-else>
                                                            <b-form-select
                                                                v-model="data.text"
                                                                :options="xmlFields"
                                                            />
                                                        </div>
                                                    </td>
                                                    <td class="text-right">
                                                        <b-form-checkbox
                                                            v-model="data.custom"
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
                                                <tr
                                                    v-for="(row, index) in newRow"
                                                    :key="index + '-'" class="newRow"
                                                >
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
                                                    <td colspan="3">
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
                                            <div class="alert alert-danger p-2">
                                                Duplicate field name found.
                                            </div>
                                        </div>
                                        <div v-if="isMatched" class="mt-3">
                                            <div class="alert alert-danger p-2">
                                                You must map the "{{
                                                    mappingData.match_products_by
                                                }}" field.
                                            </div>
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
                                </div>
                                <b-row class="m-0 mb-1">
                                    <b-col md="12" class="border-top pt-1 p-0">
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
                                            class="float-right"
                                            :disabled="btnLoading"
                                        >
                                            <b-spinner small v-if="btnLoading" />
                                            Update
                                        </b-button>
                                    </b-col>
                                </b-row>
                            </div>
                        </b-col>
                    </b-row>
                </validation-observer>
            </b-tab>
            <b-tab>
                <template v-slot:title>
                    <feather-icon
                        icon="LockIcon"
                        class="mr-50 my-icon"
                        v-b-tooltip.hover.left="'Some tooltip text'"
                    />
                    Safety
                </template>
                <validation-observer ref="validateSafety" tag="form">
                    <b-row class="mb-2">
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
                                <!-- <b-form-select
                                    v-model="mappingData.safety.action_diff"
                                    :options="actionOnDifferentList"
                                    label=""
                                    :state="errors.length > 0 ? false : null"
                                /> -->
                                <b-form-select
                                    v-model="mappingData.safety.action_diff"
                                    :options="actionOnDifferentList"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="4" v-if="mappingData.safety.action_diff !== 'no_action'">
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
                                <!-- <b-form-select
                                    v-model="mappingData.safety.action_negative_diff"
                                    :options="actionOnNegativetList"
                                    :state="errors.length > 0 ? false : null"
                                /> -->
                                <b-form-select
                                    v-model="mappingData.safety.action_negative_diff"
                                    :options="actionOnNegativetList"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                        <b-col md="4" v-if="mappingData.safety.action_diff !== 'no_action'">
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
                                <!-- <b-form-select
                                    v-model="mappingData.safety.action_positive_diff"
                                    :options="actionOnPositivetList"
                                    :state="errors.length > 0 ? false : null"
                                /> -->
                                <b-form-select
                                    v-model="mappingData.safety.action_positive_diff"
                                    :options="actionOnPositivetList"
                                    :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                        </b-col>
                    </b-row>
                    <b-row class="m-0 mb-1">
                        <b-col md="12" class="border-top pt-1 p-0">
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
                                class="float-right"
                                :disabled="btnLoading"
                            >
                                <b-spinner small v-if="btnLoading" />
                                Update
                            </b-button>
                        </b-col>
                    </b-row>
                </validation-observer>
            </b-tab>
            <b-tab :active="islog">
                <template v-slot:title>
                    <feather-icon
                        icon="ActivityIcon"
                        class="mr-50 my-icon"
                        v-b-tooltip.hover.left="'Some tooltip text'"
                    />
                    Log
                </template>
                <validation-observer ref="validateLog" tag="form">
                    <b-row class="pb-3">
                        <b-col md="12">
                            <app-timeline>
                                <app-timeline-item
                                    v-for="(notification, index) in mappingData.notifications"
                                    :key="index + 1"
                                    :title="notification.title"
                                    :subtitle="notification.content"
                                    :icon="notification.icon"
                                    :time="
                                        notification.created_at
                                            ? notification.created_at
                                            : notification.updated_at
                                    "
                                    variant="success"
                                />
                            </app-timeline>
                        </b-col>
                    </b-row>
                    <b-row class="m-0 mb-1">
                        <b-col md="12" class="border-top pt-1 p-0">
                            <b-button
                                variant="outline-primary"
                                type="submit"
                                @click.prevent="tabIndex--"
                                >Previous</b-button
                            >
                        </b-col>
                    </b-row>
                </validation-observer>
            </b-tab>
        </b-tabs>
    </div>
</template>

<script>
import {
    BButton,
    BModal,
    VBModal,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BCard,
} from "bootstrap-vue";

import { BFormCheckbox } from "bootstrap-vue";
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { required, url } from "@validations";
import { http } from "@/services/requests";
import AppTimeline from "@core/components/app-timeline/AppTimeline.vue";
import AppTimelineItem from "@core/components/app-timeline/AppTimelineItem.vue";
import Cleave from "vue-cleave-component";

export default {
    components: {
        BButton,
        BModal,
        ValidationProvider,
        ValidationObserver,
        FormWizard,
        TabContent,
        BFormGroup,
        BFormInput,
        vSelect,
        BFormInvalidFeedback,
        BFormCheckbox,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
        AppTimeline,
        AppTimelineItem,
        Cleave,
    },
    props: ["id_feed", "rows", "islog"],
    data() {
        return {
            btnLoading: false,
            checkMatchBy: "",
            isMatched: false,
            isDuplicateFieldValue: false,
            isDisableNewRow: false,
            newRow: [],
            hasMappingError: false,
            id_mapping_processing: "",
            isRefreshing: false,
            loading: true,
            showAuth: false,
            showAuthHeader: false,
            showAws: false,
            submitDisable: false,
            loadDone: false,
            mapingDisabled: true,
            safetyDisable: true,
            required: "",
            showSafety: false,
            xmlFields: [],
            mappingData: {
                auth: {
                    name: "",
                    username: "",
                    password: "",
                    header: "",
                    aws_access_key: "",
                    aws_secret_access_key: "",
                },
                advanced_option: "",
                match_products_by: "",
                username: "",
                feed_type: "xml",
                category_delimitor: "",
                id_feed: "",
                mapping_name: "",
                feed_file_url: "",
                encoding: "",
                import_period: "",
                token: "",
                user_id: "",
                field_extra_info: "",
                projectFieldOptions: [],
                mergedFieldOptions: [],
                newProjectFieldOptions: [],
                is_remove_link_params: "",
                remove_link_params: [],
                update_products_if_found : '',
                safety: {
                    action_negative_diff: "",
                    action_positive_diff: "",
                    action_diff: "",
                    auto_update_title: "",
                    auto_update_description: "",
                    auto_update_images: "",
                    import_product_variations: "",
                },
                notifications: [],
            },
            tabIndex: 1,
            loadDone: true,
            selectedEncoding: "",
            selected: null,
            countHTTP: 0,
            actionOnNegativetList: [],
            actionOnPositivetList: [],
            projectToken: "",
            selectedProjectField: [],
            actionOnDifferentList: [
                { value: "no_action", text: "No Action" },
                { value: "send_notification", text: "Send Notification" },
                {
                    value: "block_send_notification",
                    text: "Block and send notification",
                },
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
            match_products_list: [
                { value: "id", text: "id" },
                { value: "link", text: "link" },
                { value: "gtin", text: "gtin" },
                { value: "sku", text: "sku" },
                { value: "isbn", text: "isbn" },
                { value: "ean", text: "ean" },
                { value: "mpn", text: "mpn" },
            ],
            match_products_list: [
                { value: "id", text: "id" },
                { value: "link", text: "link" },
                { value: "gtin", text: "gtin" },
                { value: "sku", text: "sku" },
                { value: "isbn", text: "isbn" },
                { value: "ean", text: "ean" },
                { value: "mpn", text: "mpn" },
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
        if (this.islog) {
            this.tabIndex = 3;
        }
        this.mappingData.mapping_name = this.$route.params.db_name;
    },
    created() {        
        // get the token from url and set it
        this.projectToken = this.$route.params.token;
        // get the user details
        let userData = JSON.parse(localStorage.getItem("userData"));
        this.mappingData.user_id = userData.id;
        this.mappingData.username = userData.name;
        // get project import data
        this.loading = true;
        http.get(
            "projects/import/mapping/show/" +
                this.projectToken +
                "/" +
                this.mappingData.user_id +
                "/" +
                this.id_feed
        )
            .then((response) => {
                // If is there any data available
                if (Object.keys(response.data).length !== 0) {
                    this.loadDone = false;
                    this.loading = false;
                    

                    let mapping_data = response.data.mapping_data;
                    let mapping_field = response.data.mapping_field;
                    let mapping_log = response.data.mapping_log;

                    mapping_data.forEach((item) => {
                        this.mappingData.mapping_name = item.feed_name;
                        this.mappingData.feed_file_url = item.feed_url_link;
                        this.mappingData.id_mapping_processing = item.id_mapping_processing;
                        this.mappingData.is_remove_link_params = JSON.parse(item.params).is_remove_link_params;
                        this.mappingData.update_products_if_found = JSON.parse(item.params).update_products_if_found;
                        this.mappingData.remove_link_params = JSON.parse(item.params).remove_link_params ? JSON.parse(item.params).remove_link_params : [];

                        this.mappingData.encoding = item.encoding;
                        this.mappingData.import_period = item.import_period;
                        this.mappingData.safety = JSON.parse(item.safety_info);
                        this.mappingData.auth = JSON.parse(item.auth_info);
                        this.mappingData.category_delimitor = JSON.parse(
                            item.params
                        ).category_delimiter;
                        this.mappingData.advanced_option =
                            JSON.parse(item.params).advanced_option == 1 ? true : null;

                        this.mappingData.match_products_by = item.match_by;

                        if (this.mappingData.auth.name == 2 || this.mappingData.auth.name == 3) {
                            this.showAuth = true;
                        } else if (this.mappingData.auth.name == 4) {
                            this.showAuthHeader = true;
                        } else if (this.mappingData.auth.name == 5) {
                            this.showAws = true;
                        }
                    });                   

                    mapping_field.forEach((item) => {
                        
                        if( item.internal_field_slug == 'ts_product_item_wrapper' ) {
                            this.mappingData.field_extra_info = item.import_field_slug;    
                        }                        
                        // if (item.internal_field_slug !== "ts_product_item_wrapper") {
                        if (item.custom_field == 2) {
                            this.newRow.push({
                                value: item.import_field_slug,
                                text: item.internal_field_slug,
                                custom: item.custom_field == 2 ? 2 : null,
                                custom_field:
                                    item.custom_field == 2 ? item.internal_field_slug : "",
                                id_feed_mappings: item.id_feed_mappings,
                                updates: item.updates == 1 ? true : null,
                                removes: item.removes == 1 ? true : null,
                            });
                        } else {
                            this.mappingData.projectFieldOptions.push({
                                value: item.import_field_slug,
                                text:
                                    item.custom_field == 1
                                        ? "Do Not Import"
                                        : item.internal_field_slug,
                                custom: item.custom_field == 1 ? true : null,
                                custom_field:
                                    item.custom_field == 1 ? item.internal_field_slug : "",
                                id_feed_mappings: item.id_feed_mappings,
                                updates: item.updates == 1 ? true : null,
                                removes: item.removes == 1 ? true : null,
                            });
                        }
                        // }
                    });

                    mapping_log.forEach((item) => {
                        this.mappingData.notifications.push({
                            content: item.content,
                            title: item.title,
                            icon: item.icon,
                            created_at: item.created_at,
                            updated_at: item.updated_at,
                        });
                    });
                } else {
                    this.loading = false;
                }
            })
            .catch((error) => {
                console.log(error);
            });

        // Get XML fields
        http.get("xml-fields")
            .then((response) => {
                let _this = this;
                _this.xmlFields.push({
                    value: "do_not_import",
                    text: "Do Not Import",
                    id: 0,
                });
                response.data.forEach(function (item, index) {
                    _this.xmlFields.push({
                        value: item.field_slug,
                        text: item.field_title,
                        itemKey: index,
                    });
                });
            })
            .catch((error) => {
                console.log(error);
            });

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
    },
    watch: {
        newRow: {
            handler(newData, oldData) {
                for (let field of newData) {
                    let find = this.mappingData.projectFieldOptions.filter(
                        (m) => m.value == field.value
                    );
                    if (find.length > 0) {
                        (this.isDuplicateFieldValue = true), (this.isDisableNewRow = true);
                    } else {
                        (this.isDuplicateFieldValue = false), (this.isDisableNewRow = false);
                    }
                }

                if (newData.length > 0) {
                    this.submitDisable = false;
                }
            },
            deep: true,
        },
        mappingData: {
            handler(newData, oldData) {
                this.checkMatchBy = newData.projectFieldOptions.filter((item) => {
                    return item.text !== newData.match_products_by;
                });

                if (this.checkMatchBy.length == newData.projectFieldOptions.length) {
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
            if (e == false) {
                if (this.mappingData.projectFieldOptions.length > 0) {
                    this.mappingData.projectFieldOptions.map((item) => {
                        item.updates = true;
                        item.removes = true;
                    });
                }
            }
        },
        handleAddNewRow(e) {
            this.newRow.push({
                value: "",
                custom_field: "",
                custom: 2,
                updates: "",
                removes: "",
            });
        },
        handleDeleteRow(index) {
            this.newRow.splice(index, 1);
            if (this.newRow.length == 0) {
                this.isDuplicateFieldValue = false;
                this.isDisableNewRow = false;
            }
        },
        handleRefreshFeed() {
            // Delete existing feed
            this.isRefreshing = true;
            let params = {
                token: this.projectToken,
                user_id: this.mappingData.user_id,
                feed_file_url: this.mappingData.feed_file_url,
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
        backTab(e) {
            this.tabIndex--;
        },
        validateSetting() {
            this.$refs.validateSetting.validate().then((success) => {
                if (success) {
                    this.mapingDisabled = false;
                    this.tabIndex++;
                    // let _this = this;
                    // this.callHttp();
                    this.countHTTP++;
                }
            });
        },
        callHttp(isRefreshed) {
            let importData = {
                token: this.projectToken,
                feed_file_url: this.mappingData.feed_file_url,
                name: this.mappingData.mapping_name,
                encoding: this.mappingData.encoding,
                feed_type: "xml",
            };
            http.post("projects/import", importData)
                .then((response) => {
                    let res = response.data;
                    let _this = this;

                    if (res.status !== "ready") {
                        setTimeout(function () {
                            _this.callHttp();
                            this.loadDone = true;
                        }, 5000);
                    } else if (res.status === "ready") {
                        // if no fields name found
                        _this.countHTTP == 0;
                        if (res.field_names === false) {
                            this.loadDone = false;
                            this.isRefreshing = false;
                            this.hasMappingError = true;
                            this.countHTTP = 0;
                        } else {
                            this.loadDone = false;
                            this.isRefreshing = false;
                            this.mappingData.id_feed = res.id_feed;
                            this.id_mapping_processing = res.id_mapping_processing;
                            this.mappingData.projectFieldOptions = [];

                            if (isRefreshed) {
                                // Set the
                                this.mappingData.projectFieldOptions.push({
                                    value: item,
                                    text: "do_not_import",
                                    custom: null,
                                    updates: true,
                                    removes: true,
                                });

                                _this.mappingData.newProjectFieldOptions = [];

                                res.field_names.forEach((item, index) => {
                                    this.mappingData.newProjectFieldOptions.push({
                                        value: item,
                                        text: "Do Not Import",
                                        custom: null,
                                        updates: true,
                                        removes: true,
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
                                        updates: true,
                                        removes: true,
                                    });
                                });
                            }

                            // res.field_names.forEach(function (item, index) {
                            //     _this.mappingData.projectFieldOptions.push({
                            //         value: item,
                            //         text: item,
                            //         custom: false,
                            //     });
                            // });
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
                    this.safetyDisable = false;
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
            this.$refs.validateSafety.validate().then((success) => {
                //this.submitDisable = true;

                if (this.submitDisable === true) {
                    // show notification message
                    this.showToast(
                        "danger",
                        5000,
                        "Alert!",
                        "you must complete all mandatory fields in all tabs before you can create the feed import."
                    );
                } else {

                    // if (!Object.values(this.mappingData.mergedFieldOptions).includes('ts_product_item_wrapper')){
                    //     this.mappingData.projectFieldOptions.push({
                    //         value: this.mappingData.field_extra_info,
                    //         text: "ts_product_item_wrapper",
                    //         custom: null,
                    //         updates: null,
                    //         removes: null,
                    //     });
                    // }
                    
                    // Merge newly added row
                    if (this.newRow.length > 0) {
                        this.mappingData.mergedFieldOptions = [
                            ...this.mappingData.projectFieldOptions,
                            ...this.newRow,
                        ];
                    } else {
                        this.mappingData.mergedFieldOptions = this.mappingData.projectFieldOptions;
                    }

                    this.btnLoading = true;   
                    
                    if (success) {
                        http.put(
                            "projects/import/mapping/update/" +
                                this.projectToken +
                                "/" +
                                this.mappingData.user_id +
                                "/" +
                                this.id_feed,
                            this.mappingData
                        )
                            .then((response) => {
                                // console.log(response);
                                // console.log( this.mappingData.projectFieldOptions );
                               
                                this.submitDisable = false;
                                this.btnLoading = false;
                                if (response.data.success === true) {
                                    // find the index
                                    const index = this.rows.findIndex(
                                        (key) => key.id_feed === this.id_feed
                                    );
                                    // Update the feed name on the list
                                    this.rows[index].feed_name = this.mappingData.mapping_name;
                                    // Tihs empty can be done in one for loop, I will check it later.
                                    //this.mappingData.projectFieldOptions = [];
                                    // this.mappingData.mapping_name = "";
                                    // this.mappingData.feed_file_url = "";
                                    // this.mappingData.encoding = "";
                                    // this.mappingData.import_period = "";

                                    this.countHTTP = 0;
                                    this.mapingDisabled = true;
                                    this.safetyDisable = true;

                                    // show notification message
                                    this.showToast(
                                        "success",
                                        5000,
                                        "Success!",
                                        "Import has been updated successfully."
                                    );
                                    // Hide edit import popup modal
                                    //this.$bvModal.hide("project-update-modal");
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                            });
                    }
                }
            });
        },
        submitXml() {},
    },
};
</script>
<style scoped>
.table tr {
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
}

.table tr td {
    border-top: 0;
    border-bottom: 1px solid #dee2e6;
}
.disabledTab {
    pointer-events: none;
}

.custom-switch .custom-control-label {
    padding-left: 1.5rem;
}

[dir] .table th, [dir] .table td {
    padding: 0.72rem 1rem;
}

.disableBtn {
    opacity: 0.5;
}

</style>
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
