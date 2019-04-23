export const NAV_ITEMS = [{
    displayName: 'players',
    iconName: 'people_outline',
    //route: 'players',
    children: [
        {
            displayName: 'list',
            iconName: 'group',
            route: 'players',
        },
        {
            displayName: 'add',
            iconName: 'person_add',
            route: 'players/add',
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
            route: 'games/list'
        },
        {
            displayName: 'add',
            iconName: 'create',
            route: 'games/add'
        }
    ]
}];
