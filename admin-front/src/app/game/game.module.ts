import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';

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

import { GameService } from './game.service';

@NgModule({
    declarations: [
        GameListComponent,
        GameAddComponent
    ],
    imports: [
        CommonModule,
        HttpClientModule,
        MatPaginatorModule,
        MatProgressSpinnerModule,
        MatTableModule,
        MatSortModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatInputModule
    ],
    providers: [GameService]
})
export class GameModule { }
