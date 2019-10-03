export const NAV_ITEMS = [
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
        iconName: 'games',
        children: [
            {
                displayName: 'list',
                iconName: 'ballot',
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
        iconName: 'trainers',
        children: [
            {
                displayName: 'list',
                iconName: 'ballot',
                route: 'admin/trainers/list'
            },
            {
                displayName: 'add',
                iconName: 'create',
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
                iconName: 'ballot',
                route: 'admin/posts/list'
            },
            {
                displayName: 'add',
                iconName: 'create',
                route: 'admin/posts/add'
            },
            {
                displayName: 'categories',
                iconName: 'ballot',
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
                iconName: 'ballot',
                route: 'admin/users/list'
            }
        ]
    }     
];
