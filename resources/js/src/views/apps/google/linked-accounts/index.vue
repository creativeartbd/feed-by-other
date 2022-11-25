<template>
    <div>
        <b-row>
            <b-col md="6">
                <h3>Linked Accounts</h3>
            </b-col>
            <b-col md="6 text-right">
                <b-button variant="success" v-b-modal.linked-accounts>
                    <feather-icon icon="UploadCloudIcon" class="mr-50" />
                    <span class="align-middle">Link new account</span>
                </b-button>
            </b-col>
        </b-row>

        <!-- Acount modal type -->
        <b-modal id="linked-accounts" cancel-variant="secondary" hide-footer centered size="lg">
            <template #modal-title class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">
                Select account Type
            </template>
            <LinkedAccounts />
        </b-modal>

        <!-- Import google merchant modal -->
        <b-modal
            id="import-google-merchant"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="Import Google Merchant"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/google/google-merchant.png')"
                    width="34"
                    alt="New Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">New Google Merchant Import</h5>
            </template>
            <ImportGoogleMerchant />
        </b-modal>

        <!-- Import google ads modal -->
        <b-modal
            id="import-google-ads"
            cancel-variant="secondary"
            hide-footer
            centered
            size="lg"
            title="Import Google Ads"
            @shown="hideImportTypeModal"
        >
            <template #modal-title>
                <b-img
                    :src="require('@/assets/images/google/adwords.png')"
                    width="34"
                    alt="New Import"
                    class="d-inline"
                />
                <h5 class="mr-auto ml-2 mt-2 d-inline" vertical-align="bottom">New Google Ads Import</h5>
            </template>
            <ImportGoogleAds />
        </b-modal>
    </div>
</template>

<script>
import LinkedAccounts from "./LinkedAccounts.vue";
import ImportGoogleMerchant from "./ImportGoogleMerchant.vue";
import ImportGoogleAds from "./ImportGoogleAds.vue";

export default {
    components : {
        LinkedAccounts,
        ImportGoogleMerchant,
        ImportGoogleAds
    },
    methods: {
        hideImportTypeModal() {
            this.$bvModal.hide("linked-accounts");
        },
    },
}
</script>

<style scoped>

</style>