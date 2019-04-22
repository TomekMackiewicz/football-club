export const NAV_ITEMS = [{
    displayName: 'Players',
    iconName: 'people_outline',
    //route: 'players',
    children: [
        {
            displayName: 'List',
            iconName: 'group',
            route: 'players',
        },
        {
            displayName: 'Add',
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
            displayName: 'List',
            iconName: 'ballot',
            route: 'games/list'
        },
        {
            displayName: 'Add',
            iconName: 'create',
            route: 'games/add'
        }
    ]
}];
