<template>
    <section id="dashboard-ecommerce">
        <b-card-code title="Project Dashboard">
            <b-nav class="wrap-border">
                <b-nav-item active :to="{ path : 'settings/' + this.$route.params.token }"> Settings</b-nav-item>
            </b-nav>
        </b-card-code>

        <b-row class="match-height">
            <b-col xl="4" md="6">
                <ecommerce-medal :data="data.congratulations" />
            </b-col>
            <b-col xl="8" md="6">
                <ecommerce-statistics :data="data.statisticsItems" />
            </b-col>
        </b-row>

        <b-row class="match-height">
            <b-col lg="4">
                <b-row class="match-height">
                    <!-- Bar Chart - Orders -->
                    <b-col lg="6" md="3" cols="6">
                        <ecommerce-order-chart :data="data.statisticsOrder" />
                    </b-col>
                    <!--/ Bar Chart - Orders -->
                    <b-col lg="6" md="3" cols="6">
                        <ecommerce-profit-chart :data="data.statisticsProfit" />
                    </b-col>
                    <b-col lg="12" md="6">
                        <ecommerce-earnings-chart :data="data.earningsChart" />
                    </b-col>
                </b-row>
            </b-col>

            <!-- Revenue Report Card -->
            <b-col lg="8">
                <ecommerce-revenue-report :data="data.revenue" />
            </b-col>
            <!--/ Revenue Report Card -->
        </b-row>

        <b-row class="match-height">
            <!-- Company Table Card -->
            <b-col lg="8">
                <ecommerce-company-table :table-data="data.companyTable" />
            </b-col>
            <!--/ Company Table Card -->

            <!-- Developer Meetup Card -->
            <b-col lg="4" md="6">
                <ecommerce-meetup :data="data.meetup" />
            </b-col>
            <!--/ Developer Meetup Card -->

            <!-- Browser States Card -->
            <b-col lg="4" md="6">
                <ecommerce-browser-states />
            </b-col>
            <!--/ Browser States Card -->

            <!-- Goal Overview Card -->
            <b-col lg="4" md="6">
                <ecommerce-goal-overview :data="data.goalOverview" />
            </b-col>
            <!--/ Goal Overview Card -->

            <!-- Transaction Card -->
            <b-col lg="4" md="6">
                <ecommerce-transactions :data="data.transactionData" />
            </b-col>
            <!--/ Transaction Card -->
        </b-row>
    </section>
</template>

<script>
import { BRow, BCol } from "bootstrap-vue";
import BCardCode from "@core/components/b-card-code";
import { getUserData } from "@/auth/utils";
import EcommerceMedal from "./EcommerceMedal.vue";
import EcommerceStatistics from "./EcommerceStatistics.vue";
import EcommerceRevenueReport from "./EcommerceRevenueReport.vue";
import EcommerceOrderChart from "./EcommerceOrderChart.vue";
import EcommerceProfitChart from "./EcommerceProfitChart.vue";
import EcommerceEarningsChart from "./EcommerceEarningsChart.vue";
import EcommerceCompanyTable from "./EcommerceCompanyTable.vue";
import EcommerceMeetup from "./EcommerceMeetup.vue";
import EcommerceBrowserStates from "./EcommerceBrowserStates.vue";
import EcommerceGoalOverview from "./EcommerceGoalOverview.vue";
import EcommerceTransactions from "./EcommerceTransactions.vue";
import { useRoute } from 'vue-router';

export default {
    components: {
        BRow,
        BCol,
        BCardCode,
        EcommerceMedal,
        EcommerceStatistics,
        EcommerceRevenueReport,
        EcommerceOrderChart,
        EcommerceProfitChart,
        EcommerceEarningsChart,
        EcommerceCompanyTable,
        EcommerceMeetup,
        EcommerceBrowserStates,
        EcommerceGoalOverview,
        EcommerceTransactions,
    },
    data() {
        return {
            projectToken : '',
            data : {

            }
        };
    },
    methods : {
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
        document.title = this.capitalizeFirstLetter( projectName ) + ' - Dashboard';

        if( this.$route.params.token === 'undefined' ) {
            this.$router.push("/");
        }
        // data
        this.$http.get("/ecommerce/data").then((response) => {
            this.data = response.data;

            // ? Your API will return name of logged in user or you might just directly get name of logged in user
            // ? This is just for demo purpose
            const userData = getUserData();
            this.data.congratulations.name = userData.username;
        });
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/pages/dashboard-ecommerce.scss";
@import "~@resources/scss/vue/libs/chart-apex.scss";
</style>
