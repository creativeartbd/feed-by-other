<template>
    <b-card-code title="Select Import Type">
        <div class="media-list media-bordered bg-white p-3 border">
            <b-media vertical-align="top" class="p-0 border-bottom pb-5">
                <template #aside>
                    <b-img
                        :src="require('@/assets/images/projects/xml.png')"
                        blank-color="#ccc"
                        width="64"
                        alt="placeholder"
                    />
                </template>
                <h5>XML Import</h5>
                <b-card-text>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Voluptate iure non quasi qui a mollitia unde blanditiis
                    impedit dolorem in!
                </b-card-text>
                <div class="demo-inline-spacing">
                    <b-button block variant="primary" v-b-modal.import-xml>
                        Import XML
                    </b-button>
                </div>
            </b-media>

            <b-media vertical-align="top" class="p-0 mt-5 border-0 pb-5">
                <template #aside>
                    <b-img
                        :src="require('@/assets/images/projects/csv.png')"
                        blank-color="#ccc"
                        width="64"
                        alt="placeholder"
                    />
                </template>
                <h5>CSV Import</h5>
                <b-card-text>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Voluptate iure non quasi qui a mollitia unde blanditiis
                    impedit dolorem in!
                </b-card-text>
                <div class="demo-inline-spacing">
                    <b-button block variant="primary"> Import CSV </b-button>
                </div>
            </b-media>

            <!-- modal -->
            <b-modal
                id="import-xml"
                cancel-variant="secondary"
                hide-footer
                centered
                size="xl"
                title="Import XML file"
            >
                <b-card-text>
                    <form-wizard
                        color="#7367F0"
                        :title="null"
                        :subtitle="null"
                        shape="square"
                        finish-button-text="Submit"
                        back-button-text="Previous"
                        class="mb-3 shadow-none"
                        @on-complete="formSubmitted"
                    >
                        <!-- XML Details tab -->
                        <tab-content
                            title="Settings"
                            :before-change="validationForm"
                        >
                            <validation-observer ref="accountRules" tag="form">
                                <b-row>
                                    <b-col cols="12" class="mb-2">
                                        <h5 class="mb-0">Enter XML Details</h5>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-group
                                            label="Name"
                                            label-for="name"
                                        >
                                            <validation-provider
                                                #default="{ errors }"
                                                name="name"
                                                rules="required"
                                            >
                                                <b-form-input
                                                    id="name"
                                                    v-model="name"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    placeholder="Enter name"
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                                        <b-form-group
                                            label="XML file URL"
                                            label-for="XML file URL"
                                        >
                                            <validation-provider
                                                #default="{ errors }"
                                                name="xml_file_url"
                                                rules="required|url"
                                            >
                                                <b-form-input
                                                    id="xml_file_url"
                                                    v-model="xml_file_url"
                                                    type="url"
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                    placeholder=""
                                                />
                                                <small class="text-danger">{{
                                                    errors[0]
                                                }}</small>
                                            </validation-provider>
                                        </b-form-group>
                                    </b-col>
                                    <b-col md="6">
                                        <validation-provider
                                            #default="{ errors }"
                                            name="Encoding"
                                            rules="required"
                                        >
                                            <b-form-group
                                                label="Encoding"
                                                label-for="encoding"
                                                :state="
                                                    errors.length > 0
                                                        ? false
                                                        : null
                                                "
                                            >
                                                <v-select
                                                    id="encoding"
                                                    v-model="selectedContry"
                                                    :options="countryName"
                                                    :selectable="
                                                        (option) =>
                                                            !option.value.includes(
                                                                'select_value'
                                                            )
                                                    "
                                                    label="text"
                                                />
                                                <b-form-invalid-feedback
                                                    :state="
                                                        errors.length > 0
                                                            ? false
                                                            : null
                                                    "
                                                >
                                                    {{ errors[0] }}
                                                </b-form-invalid-feedback>
                                            </b-form-group>
                                        </validation-provider>
                                    </b-col>
                                </b-row>
                            </validation-observer>
                        </tab-content>

                        <!-- Edit Mapping tab tab -->
                        <tab-content
                            title="Edit Mapping"
                            :before-change="validationFormInfo"
                        >
                            
                        </tab-content>

                        <!-- Safety Tab  -->
                        <tab-content
                            title="Safety"
                            :before-change="validationFormAddress"
                        >
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Deserunt autem animi assumenda ratione, repellendus earum sapiente veritatis dolorum nisi sit minus accusantium. Repellat laborum consequuntur dolorem expedita dolore odio cupiditate.</p>
                        </tab-content>
                    </form-wizard>
                </b-card-text>
            </b-modal>
        </div>
    </b-card-code>
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
} from "bootstrap-vue";
import { FormWizard, TabContent } from "vue-form-wizard";
import vSelect from "vue-select";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { required, email } from "@validations";
export default {
    components: {
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
        BFormInvalidFeedback,
        // eslint-disable-next-line vue/no-unused-components
        ToastificationContent,
    },
    data() {
        return {};
    },
    directives: {
        "b-modal": VBModal,
    },
    methods: {
        formSubmitted() {},
    },
};
</script>
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-wizard.scss";
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
