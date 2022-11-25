<template>
    <b-card>
        <b-form>
            <b-row>
                <b-col cols="12">
                    <div class="d-flex align-items-center mb-2">
                        <feather-icon icon="LinkIcon" size="18" />
                        <h4 class="mb-0 ml-75">Social Links</h4>
                    </div>
                </b-col>

                <!-- twitter -->
                <b-col md="6">
                    <b-form-group label-for="account-twitter" label="Twitter">
                        <b-form-input
                            id="account-twitter"
                            placeholder="Add link"
                        />
                    </b-form-group>
                </b-col>
                <!--/ twitter -->


                <!-- buttons -->
                <b-col cols="12">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mt-1 mr-1"
                    >
                        Save changes
                    </b-button>
                    <b-button
                        v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                        type="reset"
                        class="mt-1 ml-25"
                        variant="outline-secondary"
                        @click.prevent="resetForm"
                    >
                        Cancel
                    </b-button>
                </b-col>
                <!--/ buttons -->
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BCard,
    BCardText,
    BLink,
    BAvatar,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";

export default {
    components: {
        BButton,
        BForm,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BCard,
        BCardText,
        BLink,
        BAvatar,
    },
    directives: {
        Ripple,
    },
    props: {
        
    },
    data() {
        return {
            
        };
    },
    methods: {
        resetForm() {
           
        },
    },
};
</script>
