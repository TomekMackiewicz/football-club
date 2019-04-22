import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GameListComponent } from './game-list/game-list.component';
import { GameAddComponent } from './game-add/game-add.component';

import { 
    MatPaginatorModule,
    MatProgressSpinnerModule,
    MatTableModule,
    MatSortModule,
    MatCheckboxModule,
    MatFormFieldModule,
    MatInputModule
} from '@angular/material';

@NgModule({
    declarations: [
        GameListComponent,
        GameAddComponent
    ],
    imports: [
        CommonModule,
        MatPaginatorModule,
        MatProgressSpinnerModule,
        MatTableModule,
        MatSortModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatInputModule
    ]
})
export class GameModule { }
