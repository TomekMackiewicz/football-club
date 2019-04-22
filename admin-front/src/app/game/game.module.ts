import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { GameListComponent } from './game-list/game-list.component';
import { GameAddComponent } from './game-add/game-add.component';

@NgModule({
  declarations: [
      GameListComponent,
      GameAddComponent
  ],
  imports: [
    CommonModule
  ]
})
export class GameModule { }
