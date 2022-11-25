export default [{
        header: "Catalog",
    },
    {
        title: "Products",
        route: {
            name: "project-all-import",
        },
        icon: "ShoppingBagIcon",
    },
    {
        title: "Import",
        route: {
            name: "project-import",
        },
        icon: "UploadCloudIcon",
    },

    {
        title: "Export",
        route: "export",
        icon: "SlidersIcon",
    },
    {
        title: "Master Rules",
        route: "master-rules",
        icon: "CircleIcon",
    },
    {
        title: "Feeds",
        route: "feeds",
        icon: "CircleIcon",
    },
    {
        title: "Google",
        route: "google",
        icon: "CircleIcon",
        children: [{
            title: 'Linked accounts',
            route: 'linked-accounts',
        }, ],
    },
];