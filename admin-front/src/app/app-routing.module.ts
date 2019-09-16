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
import { FrontComponent } from './front_temp/front.component';
import { AccessDeniedComponent } from './denied/denied.component';

import { AuthGuard } from './guards/auth.guard';

const routes: Routes = [
    {path: 'admin/games/list', 
        component: GameListComponent,
        canActivate: [AuthGuard]                       
    }, 
    {path: 'games/add', 
        component: GameAddComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'games/edit/:id', 
        component: GameEditComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'posts/list', 
        component: PostListComponent,
        canActivate: [AuthGuard]                      
    }, 
    {path: 'posts/add', 
        component: PostAddComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'posts/edit/:id', 
        component: PostEditComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'categories/list', 
        component: CategoryListComponent,
        canActivate: [AuthGuard]                     
    }, 
    {path: 'categories/add', 
        component: CategoryAddComponent,
        canActivate: [AuthGuard]                     
    },
    {path: 'categories/edit/:id', 
        component: CategoryEditComponent,
        canActivate: [AuthGuard]                    
    },
    {path: 'files', 
        component: FileComponent,
        canActivate: [AuthGuard]
    },
    {path: 'config', 
        component: ConfigComponent,
        canActivate: [AuthGuard]
    }, 
    {path: 'front', 
        component: FrontComponent                      
    },
    {path: 'denied', 
        component: AccessDeniedComponent
    }         
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
