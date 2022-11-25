<template>
    <b-card-code no-body title="All Projects">
        <b-table responsive="sm" :items="items" />
    </b-card-code>
</template>

<script>
import BCardCode from "@core/components/b-card-code/BCardCode.vue";
import { BTable } from "bootstrap-vue";
import { http } from "@/services/requests";

export default {
    components: {
        BCardCode,
        BTable,
    },
    data() {
        return {
            items: [],
            projectsData: [],
            user_id : ''
        };
    },
    mounted() {
        let userData = JSON.parse(localStorage.getItem('userData'));
        this.user_id = userData.id;
        this.getProjects();
    },
    methods: {
        getProjects() {
            let _this = this;
            let user_id = this.user_id;
            http.get("projects/"+user_id)
            .then((response) => {
                 console.log(response);
                _this.projectsData = response.data;
                
                response.data.forEach(function (entry) {
                    _this.items.push(entry);
                });
            })
            .catch((error) => {
                console.log(error);
            });
        },
    },
};
</script>
