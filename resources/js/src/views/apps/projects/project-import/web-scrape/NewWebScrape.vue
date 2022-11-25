<template>
    <b-tabs v-model="tabIndex" v-on:activate-tab="tabActivated">
        <b-tab active>
            <template v-slot:title>
                <feather-icon
                    icon="SettingsIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                {{ $t('Settings') }}    
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
                                        placeholder="Enter a maping name"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2">
                                <label
                                    >Scrape Frequency
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.left="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Scrape Frequency"
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
                                <validation-provider
                                    #default="{ errors }"
                                    name="Password"
                                    rules="required"
                                >
                                    <b-form-input
                                        type="password"
                                        v-model="mappingData.auth.password"
                                        :state="errors.length > 0 ? false : null"
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
                                    >Scrape from
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Scrape URL"
                                    rules="required"
                                >
                                    <b-form-select
                                        v-model="mappingData.scrape_from"
                                        :options="scrape_from_list"
                                        :state="errors.length > 0 ? false : null"
                                        @change="handleScrapUrl"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="is_csv_xml_import_visible">
                                <label
                                    >CSV/XML Import
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="CSV/XML import"
                                    rules="required"
                                >
                                    <b-form-select
                                        v-model="mappingData.csv_xml_import"
                                        :options="csv_xml_import_list"
                                        :state="errors.length > 0 ? false : null"
                                        multiple
                                    ></b-form-select>
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="is_external_sitemap_visible">
                                <div
                                    v-for="(item, index) in mappingData.external_sitemap_url"
                                    :key="index"
                                    class="mb-2"
                                >
                                    <label for="external-sitemap-url">External sitemap url</label>
                                    <validation-provider
                                        #default="{ errors }"
                                        name="External sitemap url"
                                        rules="required|url"
                                    >
                                        <b-input-group>
                                            <b-form-input
                                                placeholder="External sitemap url"
                                                v-model="item.url"
                                                :state="errors.length > 0 ? false : null"
                                            />
                                            <b-input-group-append>
                                                <b-button
                                                    variant="outline-danger"
                                                    @click="deleteRow(index, mappingData.external_sitemap_url)"
                                                >
                                                    -
                                                </b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </div>
                                <b-button variant="outline-success" @click="addNewRow(mappingData.external_sitemap_url)">
                                    +
                                </b-button>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="is_regex_url_visible">
                                <label
                                    >Regex URL
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.left="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Regex URL"
                                    rules="required"
                                >
                                    <b-form-input
                                        v-model="mappingData.regex_url"
                                        :state="errors.length > 0 ? false : null"
                                        placeholder="Enter a regex url"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-2" v-if="is_from_specifc_pages">
                                <b-input-group>
                                    <b-input-group-prepend>
                                        <b-form-select v-model="mappingData.from_specific_pages_option" :options="from_specific_pages_list"></b-form-select>
                                    </b-input-group-prepend>
                                    <b-form-input placeholder="" v-model="mappingData.from_specific_pages_input" />
                                </b-input-group>
                                <div
                                    v-for="(item, index) in mappingData.from_specific_page_url"
                                    :key="index"
                                    class="mt-2"
                                >
                                    <validation-provider
                                        #default="{ errors }"
                                        name="Page url"
                                        rules="required|url"
                                    >
                                        <b-input-group>
                                            <b-form-input
                                                placeholder="Page url"
                                                v-model="item.url"
                                                :state="errors.length > 0 ? false : null"
                                            />
                                            <b-input-group-append>
                                                <b-button
                                                    variant="outline-danger"
                                                    @click="deleteRow(index, mappingData.from_specific_page_url)"
                                                >
                                                    -
                                                </b-button>
                                            </b-input-group-append>
                                        </b-input-group>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </div>
                                <b-button variant="outline-success" class="mt-2" @click="addNewRow(mappingData.from_specific_page_url)">
                                    +
                                </b-button>
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
                                    >Scrape Method
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.left="'Some tooltip text'"
                                    />
                                </label>
                                <validation-provider
                                    #default="{ errors }"
                                    name="Scrape Method"
                                    rules="required"
                                >
                                    <b-form-select
                                        v-model="mappingData.scrape_method"
                                        :options="scrape_method_list"
                                        :state="errors.length > 0 ? false : null"
                                    />
                                    <small class="text-danger">{{ errors[0] }}</small>
                                </validation-provider>
                            </b-col>
                            <b-col md="12" class="mb-1">
                                <label>
                                    Use Proxies?
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    /> </label
                                ><br />
                                <b-form-checkbox
                                    v-model="mappingData.use_proxies"
                                    checked="true"
                                    class="custom-control-success"
                                    name="check-button"
                                    switch
                                    inline
                                >
                                </b-form-checkbox>
                            </b-col>
                            <b-col md="12" class="mb-1">
                                <label>
                                    Scrape each product only once?
                                    <feather-icon
                                        icon="AlertCircleIcon"
                                        class="mr-50 my-icon"
                                        v-b-tooltip.hover.right="'Some tooltip text'"
                                    /> </label
                                ><br />
                                <b-form-checkbox
                                    v-model="mappingData.scrape_once"
                                    checked="true"
                                    class="custom-control-success"
                                    name="check-button"
                                    switch
                                    inline
                                >
                                </b-form-checkbox>
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
                                class="float-right"
                                :disabled="btnLoading"
                            >
                                <b-spinner small v-if="btnLoading" />
                                Create
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </validation-observer>
        </b-tab>
        <b-tab ref="editMapping" :title-item-class="{ disabledTab: disableMappingTab === true }">
            <template v-slot:title>
                <feather-icon
                    icon="MenuIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                Scrape Rules
            </template>
            <validation-observer ref="validateSafety" tag="form">
                <div v-for="(key, index) in mappingData.mapping" :key="index">
                    <b-row>
                        <b-col md="12" class="">
                            <validation-provider
                                #default="{ errors }"
                                name="Rule Name"
                                rules="required"
                            >
                                <b-form-input
                                    v-model="key.rule_name"
                                    class="no-border"
                                    placeholder="Enter rule name" :state="errors.length > 0 ? false : null"
                                />
                                <small class="text-danger">{{ errors[0] }}</small>
                            </validation-provider>
                            <hr />
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="1">
                            <div class="set-line text-center">Set</div>
                        </b-col>
                        <b-col cols="11">
                            <b-row>
                                <b-col md="6" class="mb-2">
                                    <label
                                        >Title
                                        <feather-icon
                                            icon="AlertCircleIcon"
                                            class="mr-50 my-icon"
                                            v-b-tooltip.hover.left="'Some tooltip text'"
                                        />
                                    </label>
                                    <validation-provider #default="{ errors }" rules="required">
                                        <b-form-select
                                            v-model="key.title"
                                            :options="xml_list"
                                            :state="errors.length > 0 ? false : null"
                                            @change="handleScrapUrl"
                                            v-if="!key.is_custom_field"
                                        />
                                        <b-form-input
                                            v-model="key.title"
                                            :state="errors.length > 0 ? false : null"
                                            id="title"
                                            placeholder="Enter a title name"
                                            v-if="key.is_custom_field"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                                <b-col md="2" class="mb-2">
                                    <label>
                                        Custom
                                        <feather-icon
                                            icon="AlertCircleIcon"
                                            class="mr-50 my-icon"
                                            v-b-tooltip.hover.right="'Is custom field?'"
                                        /> </label
                                    ><br />
                                    <b-form-checkbox
                                        v-model="key.is_custom_field"
                                        checked="true"
                                        class="custom-control-success"
                                        name="check-button"
                                        switch
                                        inline
                                    >
                                    </b-form-checkbox>
                                </b-col>
                                <b-col md="2" class="mb-2">
                                    <label>
                                        Update
                                        <feather-icon
                                            icon="AlertCircleIcon"
                                            class="mr-50 my-icon"
                                            v-b-tooltip.hover.right="'Future Update?'"
                                        /> </label
                                    ><br />
                                    <b-form-checkbox
                                        v-model="key.is_update"
                                        checked="true"
                                        class="custom-control-success"
                                        name="check-button"
                                        switch
                                        inline
                                    >
                                    </b-form-checkbox>
                                </b-col>
                                <b-col md="2" class="mb-2">
                                    <label>
                                        Remove
                                        <feather-icon
                                            icon="AlertCircleIcon"
                                            class="mr-50 my-icon"
                                            v-b-tooltip.hover.right="'Future Remove?'"
                                        /> </label
                                    ><br />
                                    <b-form-checkbox
                                        v-model="key.is_remove"
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
                    <b-row>
                        <b-col cols="1">
                            <div class="as-line text-center">As</div>
                        </b-col>
                        <b-col cols="11">
                            <b-row class="mb-2">
                                <b-col md="6" class="mb-2">
                                    <label
                                        >If select option is
                                        <feather-icon
                                            icon="AlertCircleIcon"
                                            class="mr-50 my-icon"
                                            v-b-tooltip.hover.left="'Some tooltip text'"
                                        />
                                    </label>
                                    <validation-provider #default="{ errors }" rules="required">
                                        <b-form-select
                                            v-model="key.rule_option"
                                            :options="rule_option_list"
                                            :state="errors.length > 0 ? false : null"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                                <b-col md="6" class="mb-2">
                                    <!-- If rule option is 1 -->
                                    <label>&nbsp;</label>
                                    <validation-provider
                                        v-if="key.rule_option === 1"
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.specifc_single_value"
                                            :state="errors.length > 0 ? false : null"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <!-- If rule option is 2 -->
                                    <validation-provider
                                        v-if="key.rule_option === 2"
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-textarea
                                            v-model="key.specifc_value"
                                            :state="errors.length > 0 ? false : null"
                                            max-rows="6"
                                        ></b-form-textarea>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <!-- If rule option is 3 -->
                                    <validation-provider
                                        v-if="key.rule_option === 3"
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.jsond_value"
                                            :state="errors.length > 0 ? false : null"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                            </b-row>
                            <b-row>
                                <b-col md="3" class="mb-2">
                                    <!-- If rule option is 4 -->
                                    <validation-provider
                                        v-if="key.rule_option === 4"
                                        #default="{ errors }"
                                        rules="required"
                                        name="jQuery Path"
                                    >
                                        <b-form-input
                                            v-model="key.jquery_path"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="element attribute"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                                <b-col md="3" class="mb-2">
                                    <!-- If rule option is 4 -->
                                    <validation-provider
                                        v-if="key.rule_option === 4"
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-select
                                            v-model="key.jquery_path_get_option"
                                            :options="jquery_path_get_list"
                                            :state="errors.length > 0 ? false : null"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>

                                <b-col md="3" class="mb-2">
                                    <!-- If jquery path get option is 1 -->
                                    <validation-provider
                                        v-if="key.jquery_path_get_option in [1, 2, 3, 4, 5, 6, 7]"
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <div class="d-flex">
                                            <label class="mt-1 mr-2">Get</label>
                                            <b-form-select
                                                v-model="key.jquery_path_from_option"
                                                :options="jquery_path_from_list"
                                                :state="errors.length > 0 ? false : null"
                                            />
                                        </div>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <!-- If jquery path get option is 7 -->
                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 7 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.jquery_get_other_input"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="jQuery Path"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <!-- If jquery path get option is 8 -->
                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 8 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.jquery_get_key_value_input_one"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="table#id tr"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <br />

                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 8 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.jquery_get_key_value_input_two"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="td:nth-child(1)"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>

                                    <br />

                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 8 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <b-form-input
                                            v-model="key.jquery_get_key_value_input_three"
                                            :state="errors.length > 0 ? false : null"
                                            placeholder="td:nth-child(2)"
                                        />
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                                <b-col md="3">
                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 7 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <div class="d-flex">
                                            <label class="mt-1 mr-2">From</label>
                                            <b-form-select
                                                v-model="key.jquery_get_other_input_from"
                                                :options="jquery_path_from_list"
                                                :state="errors.length > 0 ? false : null"
                                            />
                                        </div>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                    <validation-provider
                                        v-if="
                                            key.jquery_path_get_option === 8 &&
                                            key.rule_option === 4
                                        "
                                        #default="{ errors }"
                                        rules="required"
                                    >
                                        <div class="d-flex">
                                            <label class="mt-1 mr-2">From</label>
                                            <b-form-select
                                                v-model="key.jquery_get_key_value_input_from"
                                                :options="jquery_path_from_list"
                                                :state="errors.length > 0 ? false : null"
                                            />                                            
                                        </div>
                                        <small class="text-danger">{{ errors[0] }}</small>
                                    </validation-provider>
                                </b-col>
                            </b-row>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="1">
                            <div class="if-line text-center">If</div>
                        </b-col>
                        <b-col cols="11">
                            <b-button
                                variant="success"
                                type="submit"
                                @click.prevent="handleAddCondition(index)"
                                v-if="!key.showCondition"
                                class="mb-5"
                            >
                                + Add condition
                            </b-button>
                            <b-button
                                variant="danger"
                                type="submit"
                                @click.prevent="handleAddCondition(index)"
                                v-else
                                class="mb-5"
                            >
                                - Remove Condition
                            </b-button>
                            <b-button
                                variant="default"
                                type="submit"
                                @click.prevent="handleRemoveMore(index)"
                                class="mb-5 float-right btn-outline-danger"
                            >
                                - Remove
                            </b-button>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col cols="1"> </b-col>
                        <b-col cols="11">
                            <vue-query-builder
                                :rules="rules"
                                v-model="key.query"
                                v-if="key.showCondition"
                            ></vue-query-builder>
                        </b-col>
                    </b-row>
                </div>
                <b-row>
                    <b-col md="12">
                        <b-button
                            variant="default"
                            type="submit"
                            @click.prevent="handleAddMore"
                            class="mb-5 float-right btn-outline-success mr-2"
                        >
                            + Add More
                        </b-button>
                    </b-col>
                </b-row>
                <b-row class="mt-2 border-top m-0 mb-1">
                    <b-col cols="12 d-flex align-items-end justify-content-between pt-1 p-0">
                        <b-button
                            variant="outline-primary"
                            type="submit"
                            @click.prevent="tabIndex--"
                            >Back</b-button
                        >
                        <div>
                            <b-button
                                variant="success"
                                type="submit"
                                @click.prevent="validateSafety"
                                :class="{ disableBtn: submitDisable }"
                                class="float-right"
                                :disabled="btnLoading"
                            >
                                <b-spinner small v-if="btnLoading" />
                                Create
                            </b-button>
                        </div>
                    </b-col>
                </b-row>
            </validation-observer>
        </b-tab>
        <!-- <b-tab ref="safety" :title-item-class="{ disabledTab: disableSafetyTab === true }">
            <template v-slot:title>
                <feather-icon
                    icon="LockIcon"
                    class="mr-50 my-icon"
                    v-b-tooltip.hover.left="'Some tooltip text'"
                />
                Safety
            </template>
            <validation-observer ref="validateSafety" tag="form">
                <b-row class="pt-3 pb-3">
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
                <b-row class="pb-3 border-top m-1">
                    <b-col md="12" class="pt-3 p-0">
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
        </b-tab> -->
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
import VueQueryBuilder from "vue-query-builder";
import VueQueryBuilderCSS from "vue-query-builder/dist/VueQueryBuilder.css";
import PageDomElement from "./page-dom-element.vue";

export default {
    components: {
        VueQueryBuilderCSS,
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
        VueQueryBuilder,
        PageDomElement,
    },
    props: ["importlist"],
    data() {
        return {
            locale: this.$i18n.locale,
            btnLoading: false,
            isDuplicateFieldValue: false,
            isDisableNewRow: false,
            projectToken: "",
            disableMappingTab: true,
            disableUpdateTab: true,
            disableSafetyTab: true,
            stepOne: false,
            submitDisable: false,
            showAuth: false,
            showAuthHeader: false,
            showAws: false,

            rules: [
                {
                    type: "text",
                    id: "url_regex",
                    label: "Url Regex",
                },
                {
                    type: "text",
                    id: "page_source",
                    label: "Page Source ",
                },
                {
                    type: "custom-component",
                    id: "page_dom_element",
                    label: "Page Dom Element",
                    operators: [],
                    default: {
                        dom_jquery_path: "",
                        dom_contain: "",
                        dom_value: "",
                    },
                    component: PageDomElement,
                },
            ],
            custom_element: "",
            project_db_name: "",
            mapping_import_field: "",
            mapping_project_field: "",
            selectMap: [],
            importField: [],
            loadDone: true,
            required: "",
            showSafety: false,
            is_csv_xml_import_visible: false,
            is_external_sitemap_visible: false,
            is_regex_url_visible: false,
            is_from_specifc_pages : false,
            csv_xml_import_list: [],

            mappingData: {
                id_feed: "",
                id_project: "",
                username: "",
                feed_type: "scrape",
                token: "",
                user_id: "",
                mapping_name: "",
                scrape_from: "",
                // import_period: 24,
                import_period: 24,

                scrape_method: "normal",
                encoding: 1,
                csv_xml_import: [],
                external_sitemap_url: [{ url: "" }],
                from_specific_page_url: [{ url: "" }],
                regex_url: "",
                from_specific_pages_option : null, 
                from_specific_pages_input : "",

                use_proxies: "",
                scrape_once : "",
                is_remove_link_params: "",
                remove_link_params: [],
                update_products_if_found : "",
                mapping: [
                    {
                        rule_name: " Rule# 1",
                        title: "",
                        rule_option: "",
                        specifc_single_value: "",
                        from_specific_pages : "",
                        from_specific_pages_dropdown : "",
                        specifc_value: "",
                        jsond_value: "",
                        jquery_path: "",
                        jquery_path_get_option: "",
                        jquery_path_from_option: "",
                        jquery_get_other_input: "",
                        jquery_get_key_value_input_one: "",
                        jquery_get_key_value_input_two: "",
                        jquery_get_key_value_input_three: "",
                        jquery_get_key_value_input_from: "",
                        query: {},
                        showCondition: false,
                        is_custom_field: false,
                        is_remove : true, 
                        is_update : true,
                    },
                ],

                auth: {
                    name: 1,
                    username: "",
                    password: "",
                    header: "",
                    aws_access_key: "",
                    aws_secret_access_key: "",
                },
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
            selectedEncoding: "",
            selected: null,
            actionOnNegativetList: [],
            actionOnPositivetList: [],
            actionOnDifferentList: [
                { value: "no_action", text: "No Action" },
                { value: "send_notification", text: "Send Notification" },
                { value: "block_send_notification", text: "Block and send notification" },
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
            import_period_list: [
                { value: 6, text: "6 hours" },
                { value: 12, text: "12 hours" },
                { value: 24, text: "24 hours" },
                { value: 48, text: "48 hours" },
                { value: 210, text: "7 days" },
                { value: 720, text: "30 days" },
            ],
            scrape_method_list: [
                { value: "normal", text: "Default" },
                { value: "headless_browser", text: "Headless Browser" },
                { value: "emulate_browser", text: "Browser" },
            ],
            scrape_from_list: [
                { value: null, text: "--Choose--" },
                { value: 1, text: "Other feed (XML or CSV)" },
                { value: 2, text: "All catalog products" },
                { value: 3, text: "From sitemap" },
                { value: 4, text: "Regex URL of product page" },
                { value: 5, text: "From Specific Pages" }
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
            xml_list: [],
            rule_option_list: [
                { value: null, text: "--Choose--" },
                { value: 1, text: "Specific single value" },
                { value: 2, text: "Specific value" },
                { value: 3, text: "JsonD value" },
                { value: 4, text: "From page DOM element" },
            ],
            jquery_path_get_list: [
                { value: null, text: "--Choose--" },
                { value: 1, text: "Text" },
                { value: 2, text: "Html" },
                { value: 3, text: "Src" },
                { value: 4, text: "Data-src" },
                { value: 5, text: "Href" },
                { value: 6, text: "Title" },
                { value: 7, text: "Other" },
                { value: 8, text: "Key-Value pair" },
            ],
            jquery_path_from_list: [
                { value: null, text: "--Choose--" },
                { value: 1, text: "First Matched Element" },
                { value: 2, text: "Last Matched Element" },
                { value: 3, text: "All Matched Elements" },
                { value: 4, text: "All Matched Elements without first one" },
                { value: 5, text: "All Matched Elements without last one" },
            ],
            from_specific_pages_list : [
                { value : null, text : '--Choose--' }, 
                { value : 1, text : 'jQuery Path' }, 
                { value : 2, text : 'Page Regex' }
            ]
        };
    },
    directives: {
        "b-modal": VBModal,
    },
    mounted() {
        this.project_db_name = this.$route.params.db_name;
        //this.mappingData.mapping_name = this.$route.params.db_name;
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
                _this.xml_list.push({
                    value: "do_not_import",
                    text: "Do Not Import",
                    id: 0,
                });
                response.data.forEach(function (item) {
                    _this.xml_list.push({
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
        locale(val) {
            this.$i18n.locale = val
        },
        mappingData: {
            handler(newData, oldData) {
                //Control Create button
                if (
                    newData.mapping_name == "undefined" ||
                    newData.mapping_name == null ||
                    newData.mapping_name == ""
                ) {
                    this.submitDisable = true;
                    this.disableMappingTab = true;
                } else {
                    if (newData.scrape_from === "" || newData.scrape_from === null) {
                        this.submitDisable = true;
                        this.disableMappingTab = true;
                    } else {
                        if (newData.scrape_from == 1) {
                            if (newData.csv_xml_import === "" || newData.csv_xml_import === null) {
                                this.submitDisable = true;
                                this.disableMappingTab = true;
                            } else {
                                this.submitDisable = false;
                                this.disableMappingTab = false;
                            }
                        } else if (newData.scrape_from == 3) {
                            newData.external_sitemap_url.forEach((item) => {
                                if (item.url === "") {
                                    this.submitDisable = true;
                                    this.disableMappingTab = true;
                                } else {
                                    this.submitDisable = false;
                                    this.disableMappingTab = false;
                                }
                            });
                        } else if (newData.scrape_from == 2) {
                            this.submitDisable = false;
                            this.disableMappingTab = false;
                        } else if (newData.scrape_from === 4) {
                            if (newData.regex_url === "") {
                                this.submitDisable = true;
                                this.disableMappingTab = true;
                            } else {
                                this.submitDisable = false;
                                this.disableMappingTab = false;
                            }
                        } else if ( newData.scrape_from == 5) {
                            newData.from_specific_page_url.forEach((item) => {
                                if (item.url === "") {
                                    this.submitDisable = true;
                                    this.disableMappingTab = true;
                                } else {
                                    this.submitDisable = false;
                                    this.disableMappingTab = false;
                                }
                            });
                        }

                        this.disableSafetyTab = false;
                    }
                }

                newData.mapping.forEach((rule) => {
                    if (rule.rule_name == "") {
                        this.submitDisable = true;
                    } else if (rule.title == "") {
                        this.submitDisable = true;
                    } else if (rule.rule_option == "" || rule.rule_option == null) {
                        this.submitDisable = true;
                    } else if (rule.rule_option == 1) {
                        if (rule.specifc_single_value == "") {
                            this.submitDisable = true;
                        }
                    } else if (rule.rule_option == 2) {
                        if (rule.specifc_value == "") {
                            this.submitDisable = true;
                        }
                    } else if (rule.rule_option == 3) {
                        if (rule.jsond_value == "") {
                            this.submitDisable = true;
                        }
                    } else if (rule.rule_option == 4) {
                        if (rule.jquery_path == "") {
                            this.submitDisable = true;
                        } else if (
                            rule.jquery_path_get_option == null ||
                            rule.jquery_path_get_option == ""
                        ) {
                            this.submitDisable = true;
                        } else if (rule.jquery_path_get_option in [1, 2, 3, 4, 5, 6]) {
                            if (
                                rule.jquery_path_from_option == "" ||
                                rule.jquery_path_from_option == null
                            ) {
                                this.submitDisable = true;
                            }
                        } else if (rule.jquery_path_get_option == 7) {
                            if (
                                rule.jquery_get_other_input == "" ||
                                rule.jquery_get_other_input_from == "" ||
                                rule.jquery_get_other_input_from == null
                            ) {
                                this.submitDisable = true;
                            }
                        } else if (rule.jquery_path_get_option == 8) {
                            if (
                                rule.jquery_get_key_value_input_one == "" ||
                                rule.jquery_get_key_value_input_two == "" ||
                                rule.jquery_get_key_value_input_three == "" ||
                                rule.jquery_get_key_value_input_from == "" ||
                                rule.jquery_get_key_value_input_from == null
                            ) {
                                this.submitDisable = true;
                            }
                        }
                    } else {
                        this.submitDisable = false;
                    }
                });
            },
            deep: true,
        },
    },
    methods: {
        handleRemoveMore(index) {
            this.mappingData.mapping.splice(index, 1);
            this.mappingData.mapping[index].rule_name = "Rule#" + this.mappingData.mapping.length;
        },
        handleAddMore() {
            let count = this.mappingData.mapping.length + 1;

            this.mappingData.mapping.push({
                rule_name: "Rule# " + count,
                rule_option: "",
                specifc_single_value: "",
                specifc_value: "",
                jsond_value: "",
                jquery_path: "",
                jquery_path_get_option: "",
                jquery_path_from_option: "",
                jquery_get_other_input: "",
                jquery_get_key_value_input_one: "",
                jquery_get_key_value_input_two: "",
                jquery_get_key_value_input_three: "",
                jquery_get_key_value_input_from: "",
                query: {},
                showCondition: false,
                is_remove : true, 
                is_update : true,
            });
        },
        handleAddCondition(index) {
            
            this.mappingData.mapping[index].showCondition =
                !this.mappingData.mapping[index].showCondition;
            if (!this.mappingData.mapping[index].showCondition) {
                this.mappingData.mapping[index].query = {};
            }
        },
        deleteRow(index, obj) {
            if (obj.length > 1) {
                obj.splice(index, 1);
            }
        },
        addNewRow(obj) {
            obj.push({
                url: "",
            });
        },
        handleScrapUrl(value) {
            // 1 = Ohter  feed ( XML/CSV)
            if (1 === value) {
                this.is_csv_xml_import_visible = true;
                this.is_external_sitemap_visible = false;
                this.is_regex_url_visible = false;
                this.is_from_specifc_pages = false;
                this.get_xml_csv_import();
            }
            // 2 = All catalog products
            else if (2 === value) {
                this.is_external_sitemap_visible = false;
                this.is_csv_xml_import_visible = false;
                this.is_regex_url_visible = false;
                this.is_from_specifc_pages = false;
            }
            // 3 = From sitemap
            else if (3 === value) {
                this.is_external_sitemap_visible = true;
                this.is_csv_xml_import_visible = false;
                this.is_regex_url_visible = false;
                this.is_from_specifc_pages = false;
            }
            // 4 = from regex
            else if (4 === value) {
                this.is_external_sitemap_visible = false;
                this.is_csv_xml_import_visible = false;
                this.is_regex_url_visible = true;
                this.is_from_specifc_pages = false;
            } 
            else if ( 5 == value ) {
                this.is_external_sitemap_visible = false;
                this.is_csv_xml_import_visible = false;
                this.is_regex_url_visible = false;
                this.is_from_specifc_pages = true;
            } else {
                this.is_external_sitemap_visible = false;
                this.is_csv_xml_import_visible = false;
                this.is_regex_url_visible = false;
                this.is_from_specifc_pages = false;
            }
        },
        get_xml_csv_import() {
            http.get(
                "projects/csv-xml-import/get/" +
                    this.mappingData.user_id +
                    "/" +
                    this.mappingData.token
            )
                .then((response) => {
                    if (response.data.length > 0) {
                        this.csv_xml_import_list = [];
                        response.data.forEach((item) => {
                            this.csv_xml_import_list.push({
                                value: item.id_feed,
                                text: item.feed_name + " (" + item.feed_type + ")",
                            });
                        });
                    }
                })
                .catch(() => {
                    this.showToast(
                        "danger",
                        5000,
                        "Alert!",
                        "Opps! Can't get CSV/XML import list: " + error
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
        tabActivated(newTabIndex, oldTabIndex, event) {
            if (1 == newTabIndex) {
                if (this.countHTTP === 0) {
                    this.callHttp();
                    this.countHTTP++;
                }
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
                this.btnLoading = true;
                this.$refs.validateSafety.validate().then((success) => {
                    http.post("projects/import/mapping", this.mappingData)
                        .then((response) => {
                            if (response.data.success) {
                                // Add newly added row to import list
                                this.importlist.unshift({
                                    feed_name: response.data.feed.feed_name,
                                    type: response.data.feed.feed_type,
                                    id_feed: response.data.feed.id_feed,
                                    last_notification: response.data.notification,
                                    status: true,
                                    running: 0,
                                    tpi: "",
                                });

                                // Tihs empty can be done in one for loop, I will check it later.
                                this.mappingData.projectFieldOptions = [];
                                this.mappingData.mapping_name = "";
                                this.mappingData.feed_file_url = "";
                                this.mappingData.encoding = "";
                                this.mappingData.import_period = "";

                                this.countHTTP = 0;
                                this.mapingDisabled = true;
                                this.safetyDisable = true;
                                this.submitDisable = false;
                                this.btnLoading = false;

                                // show notification message
                                this.showToast(
                                    "success",
                                    5000,
                                    "Success!",
                                    "Successfully imported a new feed."
                                );
                            } else {
                                // show notification message
                                this.showToast("danger", 5000, "Alert! ", "Something is wrong!.");
                            }

                            this.$bvModal.hide("web-scrape-modal");
                        })
                        .catch((error) => {
                            // show notification message
                            this.showToast(
                                "danger",
                                5000,
                                "Alert! ",
                                "Something is wrong!." + error
                            );
                        });
                });
            }
        },
    },
};
</script>
<style lang="scss" scoped>
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import "~@resources/scss/vue/libs/vue-select.scss";

.table tr {
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
}

.table tr td {
    border-top: 0;
    border-bottom: 1px solid #dee2e6;
}

.disableBtn {
    opacity: 0.5;
}

.nav-item .active {
    font-weight: bold !important;
    border-bottom-color: #000 !important;
}

.nav-tabs .nav-link:after {
    background: linear-gradient(30deg, #000000, rgb(0 0 0 / 50%)) !important;
}

table input {
    min-width: 100px;
    max-width: 300px;
}

table select {
    min-width: 100px;
    max-width: 300px;
}

table .input-group {
    max-width: 300px;
}

.no-border {
    border: 0 !important;
    box-shadow: none !important;
}

.set-line::before {
    position: absolute;
    content: "";
    height: 50px;
    width: 2px;
    top: 53%;
    margin-top : 7px; 
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 999;
    background: black;
}

.as-line::before {
    position: absolute;
    content: "";
    height: calc(100% - 24px);
    width: 2px;
    top: 53%;
    margin-top : 7px; 
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 999;
    background: black;
}
</style>
