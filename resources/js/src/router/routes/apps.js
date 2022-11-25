export default [{
        path: "/apps/calendar",
        name: "apps-calendar",
        component: () =>
            import ("@/views/apps/calendar/Calendar.vue"),
    },

    // *===============================================---*
    // *--------- EMAIL & IT'S FILTERS N LABELS -------------------------------*
    // *===============================================---*
    {
        path: "/apps/email",
        name: "apps-email",
        component: () =>
            import ("@/views/apps/email/Email.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "email-application",
        },
    },
    {
        path: "/apps/email/:folder",
        name: "apps-email-folder",
        component: () =>
            import ("@/views/apps/email/Email.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "email-application",
            navActiveLink: "apps-email",
        },
        beforeEnter(to, _, next) {
            if (["sent", "draft", "starred", "spam", "trash"].includes(to.params.folder)) next();
            else next({ name: "error-404" });
        },
    },
    {
        path: "/apps/email/label/:label",
        name: "apps-email-label",
        component: () =>
            import ("@/views/apps/email/Email.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "email-application",
            navActiveLink: "apps-email",
        },
        beforeEnter(to, _, next) {
            if (["personal", "company", "important", "private"].includes(to.params.label)) next();
            else next({ name: "error-404" });
        },
    },

    // *===============================================---*
    // *--------- TODO & IT'S FILTERS N TAGS ---------------------------------------*
    // *===============================================---*
    {
        path: "/apps/todo",
        name: "apps-todo",
        component: () =>
            import ("@/views/apps/todo/Todo.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "todo-application",
        },
    },
    {
        path: "/apps/todo/:filter",
        name: "apps-todo-filter",
        component: () =>
            import ("@/views/apps/todo/Todo.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "todo-application",
            navActiveLink: "apps-todo",
        },
        beforeEnter(to, _, next) {
            if (["important", "completed", "deleted"].includes(to.params.filter)) next();
            else next({ name: "error-404" });
        },
    },
    {
        path: "/apps/todo/tag/:tag",
        name: "apps-todo-tag",
        component: () =>
            import ("@/views/apps/todo/Todo.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "todo-application",
            navActiveLink: "apps-todo",
        },
        beforeEnter(to, _, next) {
            if (["team", "low", "medium", "high", "update"].includes(to.params.tag)) next();
            else next({ name: "error-404" });
        },
    },

    // *===============================================---*
    // *--------- CHAT  ---------------------------------------*
    // *===============================================---*
    {
        path: "/apps/chat",
        name: "apps-chat",
        component: () =>
            import ("@/views/apps/chat/Chat.vue"),
        meta: {
            contentRenderer: "sidebar-left",
            contentClass: "chat-application",
        },
    },

    // *===============================================---*
    // *--------- ECOMMERCE  ---------------------------------------*
    // *===============================================---*
    {
        path: "/apps/e-commerce/shop",
        name: "apps-e-commerce-shop",
        component: () =>
            import ("@/views/apps/e-commerce/e-commerce-shop/ECommerceShop.vue"),
        meta: {
            contentRenderer: "sidebar-left-detached",
            contentClass: "ecommerce-application",
            pageTitle: "Shop",
            breadcrumb: [{
                    text: "ECommerce",
                },
                {
                    text: "Shop",
                    active: true,
                },
            ],
        },
    },
    {
        path: "/apps/e-commerce/wishlist",
        name: "apps-e-commerce-wishlist",
        component: () =>
            import ("@/views/apps/e-commerce/e-commerce-wishlist/ECommerceWishlist.vue"),
        meta: {
            pageTitle: "Wishlist",
            contentClass: "ecommerce-application",
            breadcrumb: [{
                    text: "ECommerce",
                },
                {
                    text: "Wishlist",
                    active: true,
                },
            ],
        },
    },
    {
        path: "/apps/e-commerce/checkout",
        name: "apps-e-commerce-checkout",
        component: () =>
            import ("@/views/apps/e-commerce/e-commerce-checkout/ECommerceCheckout.vue"),
        meta: {
            pageTitle: "Checkout",
            contentClass: "ecommerce-application",
            breadcrumb: [{
                    text: "ECommerce",
                },
                {
                    text: "Checkout",
                    active: true,
                },
            ],
        },
    },
    {
        path: "/apps/e-commerce/:slug",
        name: "apps-e-commerce-product-details",
        component: () =>
            import (
                "@/views/apps/e-commerce/e-commerce-product-details/ECommerceProductDetails.vue"
            ),
        meta: {
            pageTitle: "Product Details",
            contentClass: "ecommerce-application",
            breadcrumb: [{
                    text: "ECommerce",
                },
                {
                    text: "Shop",
                    active: true,
                },
                {
                    text: "Product Details",
                    active: true,
                },
            ],
        },
    },

    // *===============================================---*
    // *--------- USER ---- ---------------------------------------*
    // *===============================================---*
    {
        path: "/apps/users/list",
        name: "apps-users-list",
        component: () =>
            import ("@/views/apps/user/users-list/UsersList.vue"),
    },
    {
        path: "/apps/users/view/:id",
        name: "apps-users-view",
        component: () =>
            import ("@/views/apps/user/users-view/UsersView.vue"),
    },
    {
        path: "/apps/users/edit/:id",
        name: "apps-users-edit",
        component: () =>
            import ("@/views/apps/user/users-edit/UsersEdit.vue"),
    },
    // Project
    {
        path: "/project/all-import/:token",
        name: "project-all-import",
        component: () =>
            import ("@/views/apps/projects/project-all-import/ProjectAllImport.vue"),
    },
    {
        path: "/project/import/view/:token",
        name: "project-import",
        // props : {
        //     token : 'my token'
        // },
        component: () =>
            import ("@/views/apps/projects/project-import/ProjectImport.vue"),
    },
    {
        path: "/project/import/new/:token",
        name: "project-import-new",
        component: () =>
            import ("@/views/apps/projects/project-import/ProjectImportNew.vue"),
    },
    {
        path: "/dashboard/projects/add",
        name: "add-project",
        component: () =>
            import ("@/views/apps/projects/project-add/ProjectAdd.vue"),
    },
    {
        path: "/apps/projects/all",
        name: "all-projects",
        component: () =>
            import ("@/views/apps/projects/project-all/ProjectAll.vue"),
    },
    {
        path: "/apps/projects/update/:id",
        name: "update-projects",
        component: () =>
            import ("@/views/apps/projects/project-edit/UpdateProject.vue"),
    },
    {
        path: "/apps/projects/delete/:id",
        name: "delete-projects",
        component: () =>
            import ("@/views/apps/projects/project-delete/DeleteProject.vue"),
    },
    {
        path: "/dashboard/:token",
        name: "select-project",
        props: true,
        component: () =>
            import ("@/views/apps/projects/project-select/SelectProject.vue"),
    },
    {
        path: "/dashboard/settings/:token",
        name: "project-settings",
        component: () =>
            import ("@/views/apps/projects/project-select/ProjectSettings.vue"),
    },

    // Invoice
    {
        path: "/apps/invoice/list",
        name: "apps-invoice-list",
        component: () =>
            import ("@/views/apps/invoice/invoice-list/InvoiceList.vue"),
    },
    {
        path: "/apps/invoice/preview/:id",
        name: "apps-invoice-preview",
        component: () =>
            import ("@/views/apps/invoice/invoice-preview/InvoicePreview.vue"),
    },
    {
        path: "/apps/invoice/add/",
        name: "apps-invoice-add",
        component: () =>
            import ("@/views/apps/invoice/invoice-add/InvoiceAdd.vue"),
    },
    {
        path: "/apps/invoice/edit/:id",
        name: "apps-invoice-edit",
        component: () =>
            import ("@/views/apps/invoice/invoice-edit/InvoiceEdit.vue"),
    },
    // Linked Accounts
    {
        path: "/google/linked-accounts",
        name: "linked-accounts",
        component: () =>
            import ("@/views/apps/google/linked-accounts/index.vue"),
    },
    {
        path: "/auth/callback",
        name: "auth-callback",
        component: () =>
            import ("@/views/apps/google/linked-accounts/authCallback.vue"),
    }
];