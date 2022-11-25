<template>
    <div
        class="main-menu menu-fixed menu-accordion menu-shadow"
        :class="[
            {
                expanded:
                    !isVerticalMenuCollapsed ||
                    (isVerticalMenuCollapsed && isMouseHovered),
            },
            skin === 'light' || skin === 'bordered'
                ? 'menu-light'
                : 'menu-dark',
        ]"
        @mouseenter="updateMouseHovered(true)"
        @mouseleave="updateMouseHovered(false)"
    >
        <!-- main menu header-->
        <div class="navbar-header expanded">
            <slot
                name="header"
                :toggleVerticalMenuActive="toggleVerticalMenuActive"
                :toggleCollapsed="toggleCollapsed"
                :collapseTogglerIcon="collapseTogglerIcon"
            >
                <ul class="nav navbar-nav flex-row">
                    <!-- Logo & Text -->
                    <li class="nav-item mr-auto">
                        <b-link class="navbar-brand" :to="'/dashboard/' + $route.params.token">
                            <span class="brand-logo">
                                <b-img :src="appLogoImage" alt="logo" :style="{maxWidth : 80 + 'px'}" />
                            </span>
                            <!-- <h2 class="brand-text">
                                {{ appName }}
                            </h2> -->
                        </b-link>
                    </li>

                    <!-- Toggler Button -->
                    <li class="nav-item nav-toggle">
                        <b-link class="nav-link modern-nav-toggle">
                            <feather-icon
                                icon="XIcon"
                                size="20"
                                class="d-block d-xl-none"
                                @click="toggleVerticalMenuActive"
                            />
                            <feather-icon
                                :icon="collapseTogglerIconFeather"
                                size="20"
                                class="d-none d-xl-block collapse-toggle-icon"
                                @click="toggleCollapsed"
                            />
                        </b-link>
                    </li>
                </ul>
            </slot>
        </div>
        <!-- / main menu header-->
        <div class="mobile-dropdown-project">
            <b-form inline>
                <v-select
                    v-model="selectedItem"
                    label="title"
                    :options="option" @input="chooseProject" :clearable="false"
                    append-to-body
                    :calculate-position="withPopper"
                />
                <!-- v-ripple.400="'rgba(255, 255, 255, 0.15)'" -->
                <b-button
                variant="primary"
                class="ml-3"
                v-b-modal.modal-lg
                >
                    +
                </b-button>
            </b-form>
        </div>

        <!-- Shadow -->
        <div :class="{ 'd-block': shallShadowBottom }" class="shadow-bottom" />

        <!-- main menu content-->
        <vue-perfect-scrollbar
            :settings="perfectScrollbarSettings"
            class="main-menu-content scroll-area"
            tagname="ul"
            @ps-scroll-y="
                (evt) => {
                    shallShadowBottom = evt.srcElement.scrollTop > 0;
                }
            "
        >
            <vertical-nav-menu-items
                :items="navMenuItems"
                class="navigation navigation-main"
            />
        </vue-perfect-scrollbar>
        <!-- /main menu content-->
    </div>
</template>

<script>
import VuePerfectScrollbar from "vue-perfect-scrollbar";
import { BLink, BImg } from "bootstrap-vue";
import { provide, computed, ref } from "@vue/composition-api";
import useAppConfig from "@core/app-config/useAppConfig";
import { $themeConfig } from "@themeConfig";
import VerticalNavMenuItems from "./components/vertical-nav-menu-items/VerticalNavMenuItems.vue";
import useVerticalNavMenu from "./useVerticalNavMenu";
import { http } from "@/services/requests";
import { createPopper } from '@popperjs/core'

export default {
    components: {
        VuePerfectScrollbar,
        VerticalNavMenuItems,
        BLink,
        BImg,
    },
    data () {
        return {
            selectedItem: "",
            option : [], 
            foundProject : 0,
            user_id : "",
            placement: 'top'
        }
    },
    methods : {
        withPopper(dropdownList, component, { width }) {
            /**
             * We need to explicitly define the dropdown width since
             * it is usually inherited from the parent with CSS.
             */
            dropdownList.style.width = width

            /**
             * Here we position the dropdownList relative to the $refs.toggle Element.
             *
             * The 'offset' modifier aligns the dropdown so that the $refs.toggle and
             * the dropdownList overlap by 1 pixel.
             *
             * The 'toggleClass' modifier adds a 'drop-up' class to the Vue Select
             * wrapper so that we can set some styles for when the dropdown is placed
             * above.
             */
            const popper = createPopper(component.$refs.toggle, dropdownList, {
            placement: this.placement,
            modifiers: [
                {
                name: 'offset',
                options: {
                    offset: [0, -1],
                },
                },
                {
                name: 'toggleClass',
                enabled: true,
                phase: 'write',
                fn({ state }) {
                    component.$el.classList.toggle(
                    'drop-up',
                    state.placement === 'top'
                    )
                },
                },
            ],
            })

            /**
             * To prevent memory leaks Popper needs to be destroyed.
             * If you return function, it will be called just before dropdown is removed from DOM.
             */
            return () => popper.destroy()
        },
        extractFirstText(str) {
            const matches = str.match(/\((.*?)\)/);
            return matches
                ? matches[1]
                : str;
        },
        chooseProject(e) {
            if( this.foundProject > 0 ) {
                let token = this.extractFirstText( e.title );
                let projectName = e.title.replace(/[^a-z ]/gi, '');
                let userId = this.user_id;
                let store = [];
                
                store[0] = projectName;
                store[1] = token;

                this.$router.push({
                    name : 'select-project',
                    params : { 
                        token : token
                    }
                });

                this.$router.resolve({
                    name: 'project-import',
                    params: { token: token }
                });

                
                localStorage.setItem( 'lastProjectToken_'+userId,  JSON.stringify(store) );
            }
        }
    },
    created () {
        let _this = this;
        let userData = JSON.parse(localStorage.getItem("userData"));
        let user_id = userData.id;
        this.user_id = userData.id; 
        let parsestoredItem = JSON.parse(localStorage.getItem('lastProjectToken_'+user_id));
        console.log( parsestoredItem[1] )
        
        http.get("projects/" + user_id)
            .then((response) => {
                if( Object.keys(response.data).length !== 0 ) {
                    let token = this.$route.params.token;
                    this.foundProject = 1;
                    response.data.forEach(function (entry, index) {
                        if( token == entry.token ) {
                            // Set the seelectd item
                            _this.selectedItem = {
                                title : entry.name
                            }
                        }
                        _this.option.push({
                            title : entry.name
                        }); 
                    });
                }
            })
            .catch((error) => {
                console.log(error);
            });
    },
    props: {
        isVerticalMenuActive: {
            type: Boolean,
            required: true,
        },
        toggleVerticalMenuActive: {
            type: Function,
            required: true,
        },
        navMenuItems: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const {
            isMouseHovered,
            isVerticalMenuCollapsed,
            collapseTogglerIcon,
            toggleCollapsed,
            updateMouseHovered,
        } = useVerticalNavMenu(props);

        const { skin } = useAppConfig();

        // Shadow bottom is UI specific and can be removed by user => It's not in `useVerticalNavMenu`
        const shallShadowBottom = ref(false);

        provide("isMouseHovered", isMouseHovered);

        const perfectScrollbarSettings = {
            maxScrollbarLength: 60,
            wheelPropagation: false,
        };

        const collapseTogglerIconFeather = computed(() =>
            collapseTogglerIcon.value === "unpinned" ? "CircleIcon" : "DiscIcon"
        );

        // App Name
        const { appName, appLogoImage } = $themeConfig.app;

        return {
            perfectScrollbarSettings,
            isVerticalMenuCollapsed,
            collapseTogglerIcon,
            toggleCollapsed,
            isMouseHovered,
            updateMouseHovered,
            collapseTogglerIconFeather,

            // Shadow Bottom
            shallShadowBottom,

            // Skin
            skin,

            // App Name
            appName,
            appLogoImage,
        };
    },
};
</script>

<style lang="scss">
@import "~@resources/scss/base/core/menu/menu-types/vertical-menu.scss";
.mobile-dropdown-project {
    display : none;
}
@media screen and ( max-width : 575px ) {
    .mobile-dropdown-project {
        display : block;
        position: absolute;
        left: 15px;
        bottom: 15px;
        z-index: 99999;
    }

    [dir] .vs__search, [dir] .vs__search:focus {
        width: 100%;
        margin-left: 37px;
    }

    [dir=ltr] .vs--single .vs__dropdown-toggle {
        background-color: #fff;
    }
    ul#vs2__listbox {
        z-index: 9999;
    }

    [dir="ltr"] .vs--single .vs__dropdown-toggle {
        padding-left: 6px;
        width: 186px;
    }

    [dir] .vs__selected-options {
        padding: 0 2px;
        width: 194px;
        height: 30px;
    }

    [dir="ltr"] .vs__actions {
        padding: 0px 6px 0 3px !important;
    }
}
</style>
