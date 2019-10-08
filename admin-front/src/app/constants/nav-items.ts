export const NAV_ITEMS = [
    {
        displayName: 'dashboard',
        iconName: 'dashboard',
        route: 'admin/dashboard'
    },
    {
        displayName: 'players',
        iconName: 'people_outline',
        children: [
            {
                displayName: 'list',
                iconName: 'group',
                route: 'admin/players',
            },
            {
                displayName: 'add',
                iconName: 'person_add',
                route: 'admin/players/add',
            }
        ]
    },
    {
        displayName: 'games',
        iconName: 'sports_soccer',
        children: [
            {
                displayName: 'list',
                iconName: 'list_alt',
                route: 'admin/games/list'
            },
            {
                displayName: 'add',
                iconName: 'create',
                route: 'admin/games/add'
            }
        ]
    },
    {
        displayName: 'trainers',
        iconName: 'sports',
        children: [
            {
                displayName: 'list',
                iconName: 'group',
                route: 'admin/trainers/list'
            },
            {
                displayName: 'add',
                iconName: 'person_add',
                route: 'admin/trainers/add'
            }
        ]
    },
    {
        displayName: 'posts',
        iconName: 'create',
        children: [
            {
                displayName: 'list',
                iconName: 'list_alt',
                route: 'admin/posts/list'
            },
            {
                displayName: 'add',
                iconName: 'create',
                route: 'admin/posts/add'
            },
            {
                displayName: 'categories',
                iconName: 'assignment',
                route: 'admin/categories/list'
            },
            {
                displayName: 'add category',
                iconName: 'create',
                route: 'admin/categories/add'
            }
        ]
    },
    {
        displayName: 'files',
        iconName: 'perm_media',
        route: 'admin/files'
    },
    {
        displayName: 'config',
        iconName: 'settings_applications',
        route: 'admin/config'
    }, 
    {
        displayName: 'users',
        iconName: 'people_outline',
        children: [
            {
                displayName: 'list',
                iconName: 'list_alt',
                route: 'admin/users/list'
            }
        ]
    }     
];
