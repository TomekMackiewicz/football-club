import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { GameListComponent } from './game/game-list/game-list.component';
import { GameAddComponent } from './game/game-add/game-add.component';
import { GameEditComponent } from './game/game-edit/game-edit.component';
import { PostListComponent } from './post/post-list/post-list.component';
import { PostAddComponent } from './post/post-add/post-add.component';
import { PostEditComponent } from './post/post-edit/post-edit.component';
import { CategoryListComponent } from './category/category-list/category-list.component';
import { CategoryAddComponent } from './category/category-add/category-add.component';
import { CategoryEditComponent } from './category/category-edit/category-edit.component';
import { FileComponent } from './file/file.component';
import { ConfigComponent } from './config/config.component';

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
    {path: 'posts/list', 
        component: PostListComponent                      
    }, 
    {path: 'posts/add', 
        component: PostAddComponent                      
    },
    {path: 'posts/edit/:id', 
        component: PostEditComponent                      
    },
    {path: 'categories/list', 
        component: CategoryListComponent                      
    }, 
    {path: 'categories/add', 
        component: CategoryAddComponent                      
    },
    {path: 'categories/edit/:id', 
        component: CategoryEditComponent                      
    },
    {path: 'files', 
        component: FileComponent                      
    },
    {path: 'config', 
        component: ConfigComponent                      
    },          
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
