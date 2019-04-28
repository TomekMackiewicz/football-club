import { trigger, transition, style, query, group, animate, animateChild, state } from '@angular/animations';

export const indicatorRotate = trigger('indicatorRotate', [
    state('collapsed', style({transform: 'rotate(0deg)'})),
    state('expanded', style({transform: 'rotate(180deg)'})),
    transition('expanded <=> collapsed',
        animate('225ms cubic-bezier(0.4,0.0,0.2,1)')
    )
]);
  
//export const fadeAnimation = trigger('fadeAnimation', [
//    transition('* => *', [
//        query(
//            ':enter',
//            [style({ opacity: 0 })],
//            { optional: true }
//        ),
//        query(
//            ':leave',
//            [style({ opacity: 1 }), animate('0.5s', style({ opacity: 0 }))],
//            { optional: true }
//        ),
//        query(
//            ':enter',
//            [style({ opacity: 0 }), animate('0.5s', style({ opacity: 1 }))],
//            { optional: true }
//        )
//    ])
//]);

export const fadeAnimation =
  trigger('fadeAnimation', [
    transition('* <=> *', [
      query(':enter, :leave', [
        style({
          position: 'absolute',
          right: '16px',
          left: '16px',
          opacity: 0,
          transform: 'scale(0) translateY(100%)',
        }),
      ], { optional: true }),
      query(':enter', [
        animate('600ms ease', style({ opacity: 1, transform: 'scale(1) translateY(0)' })),
      ], { optional: true })
    ]),
]);