import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { GameListComponent } from './game/game-list/game-list.component';
import { GameAddComponent } from './game/game-add/game-add.component';
import { GameEditComponent } from './game/game-edit/game-edit.component';

const routes: Routes = [
    {path: 'games/list', 
        component: GameListComponent                      
    }, 
    {path: 'games/add', 
        component: GameAddComponent                      
    },
    {path: 'games/edit/:id', 
        component: GameEditComponent                      
    },         
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
