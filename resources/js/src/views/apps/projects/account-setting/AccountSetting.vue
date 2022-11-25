<template>
    <b-card>
         <!-- form -->
        <b-form class="mt-2">
            <!-- media -->
            <b-media no-body>
                <b-media-aside>
                    <b-link>
                        <b-img
                            ref="previewEl"
                            rounded
                            :src="optionsLocal.avatar"
                            height="80"
                        />
                    </b-link>
                    <!--/ avatar -->
                </b-media-aside>

                <b-media-body class="mt-75 ml-75">
                    <!-- upload button -->
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        size="sm"
                        class="mb-75 mr-75"
                        @click="$refs.refInputEl.$el.click()"
                    >
                        Upload
                    </b-button>
                    <b-form-file
                        ref="refInputEl"
                        v-model="profileFile"
                        accept=".jpg, .png, .gif"
                        :hidden="true"
                        plain
                        @input="inputImageRenderer"
                    />
                    <!--/ upload button -->

                    <!-- reset -->
                    <b-button
                        v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                        variant="outline-secondary"
                        size="sm"
                        class="mb-75 mr-75"
                    >
                        Reset
                    </b-button>
                    <!--/ reset -->
                    <b-card-text
                        >Allowed JPG, GIF or PNG. Max size of 800kB</b-card-text
                    >
                </b-media-body>
            </b-media>
            <!--/ media -->

            <b-row>
                <b-col sm="6">
                    <b-form-group label="name" label-for="account-name">
                        <b-form-input
                            v-model="optionsLocal.name"
                            placeholder="name"
                            name="name"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="6"> </b-col>
                <b-col sm="6">
                    <b-form-group label="E-mail" label-for="account-e-mail">
                        <b-form-input
                            v-model="optionsLocal.email"
                            name="email"
                            placeholder="Email"
                        />
                    </b-form-group>
                </b-col>
                <b-col sm="6"> </b-col>

                <b-col cols="12">
                    <b-button
                        v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                        variant="primary"
                        class="mt-2 mr-1"
                        @click.prevent="updateForm"
                    >
                        Update Profile
                    </b-button>
                    <b-button
                        v-ripple.400="'rgba(186, 191, 199, 0.15)'"
                        variant="outline-secondary"
                        type="reset"
                        class="mt-2"
                        @click.prevent="resetForm"
                    >
                        Reset
                    </b-button>
                </b-col>
            </b-row>
        </b-form>
    </b-card>
</template>

<script>
import {
    BFormFile,
    BButton,
    BForm,
    BFormGroup,
    BFormInput,
    BRow,
    BCol,
    BAlert,
    BCard,
    BCardText,
    BMedia,
    BMediaAside,
    BMediaBody,
    BLink,
    BImg,
} from "bootstrap-vue";
import Ripple from "vue-ripple-directive";
import { useInputImageRenderer } from "@core/comp-functions/forms/form-utils";
import { ref } from "@vue/composition-api";
import { http } from "@/services/requests";
import { getHomeRouteForLoggedInUser } from "@/auth/utils";
import jwt from "jsonwebtoken";
import useJwt from "@/auth/jwt/useJwt";

import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

const jwtConfig = {
    secret: "dd5f3089-40c3-403d-af14-d0c228b05cb4",
    refreshTokenSecret: "7c4c1c50-3230-45bf-9eae-c9b2e401c767",
    expireTime: "10m",
    refreshTokenExpireTime: "10m",
};

export default {
    components: {
        BButton,
        BForm,
        BImg,
        BFormFile,
        BFormGroup,
        BFormInput,
        BRow,
        BCol,
        BAlert,
        BCard,
        BCardText,
        BMedia,
        BMediaAside,
        BMediaBody,
        BLink,
    },
    directives: {
        Ripple,
    },
    props: {
        generalData: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            optionsLocal: JSON.parse(localStorage.getItem("userData")),
            profileFile: null,
        };
    },
    methods: {
        resetForm() {
            this.optionsLocal = JSON.parse(localStorage.getItem("userData"));
        },

        updateForm() {
            const userData = JSON.parse(localStorage.getItem("userData"));
            http.post("update", {
                oldname: userData.name,
                name: this.optionsLocal.name,
                email: this.optionsLocal.email,
            })
                .then((response) => {
                    console.log(response);
                    if (response.data.status != "success") {
                        return;
                    }
                    const user = response.data.user;
                    console.log(user);
                    user.ability = [
                        {
                            action: "manage",
                            subject: "all",
                        },
                    ];
                    (user.avatar = require("@/assets/images/avatars/13-small.png")),
                        (user.role = "admin"),
                        (user.extras = {
                            eCommerceCartItemsCount: 5,
                        });
                    const accessToken = jwt.sign(
                        { id: user.id },
                        jwtConfig.secret,
                        { expiresIn: jwtConfig.expireTime }
                    );
                    const refreshToken = jwt.sign(
                        { id: user.id },
                        jwtConfig.refreshTokenSecret,
                        {
                            expiresIn: jwtConfig.refreshTokenExpireTime,
                        }
                    );

                    useJwt.setToken(accessToken);
                    useJwt.setRefreshToken(refreshToken);
                    localStorage.setItem("userData", JSON.stringify(user));
                    this.$ability.update(user.ability);

                    // ? This is just for demo purpose. Don't think CASL is role based in this case, we used role in if condition just for ease
                    window.location.reload().then(() => {
                        this.$toast({
                            component: ToastificationContent,
                            position: "top-right",
                            props: {
                                title: `Welcome ${user.name}`,
                                icon: "CoffeeIcon",
                                variant: "success",
                                text: `You have successfully updated your profile!`,
                            },
                        });
                    });
                })
                .catch((error) => {
                    console.log(error)
                });
        },
    },
    setup() {
        const refInputEl = ref(null);
        const previewEl = ref(null);

        const { inputImageRenderer } = useInputImageRenderer(
            refInputEl,
            previewEl
        );

        return {
            refInputEl,
            previewEl,
            inputImageRenderer,
        };
    },
};
</script>
