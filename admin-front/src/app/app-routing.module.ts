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
import { FrontComponent } from './front/front.component';
import { AccessDeniedComponent } from './denied/denied.component';
import { LoginComponent } from './login/login.component';
import { LogoutComponent } from './logout/logout.component';
import { ProfileComponent } from './profile/profile.component';

import { AuthGuard } from './guards/auth.guard';

const routes: Routes = [
    {path: 'login', 
        component: LoginComponent                      
    },
    {path: 'logout', 
        component: LogoutComponent                      
    },
    {path: 'admin/games/list', 
        component: GameListComponent,
        canActivate: [AuthGuard]                       
    },
    {path: 'admin/profile', 
        component: ProfileComponent,
        canActivate: [AuthGuard]                       
    }, 
    {path: 'admin/games/add', 
        component: GameAddComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'admin/games/edit/:id', 
        component: GameEditComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'admin/posts/list', 
        component: PostListComponent,
        canActivate: [AuthGuard]                      
    }, 
    {path: 'admin/posts/add', 
        component: PostAddComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'admin/posts/edit/:id', 
        component: PostEditComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'admin/categories/list', 
        component: CategoryListComponent,
        canActivate: [AuthGuard]                     
    }, 
    {path: 'admin/categories/add', 
        component: CategoryAddComponent,
        canActivate: [AuthGuard]                     
    },
    {path: 'admin/categories/edit/:id', 
        component: CategoryEditComponent,
        canActivate: [AuthGuard]                    
    },
    {path: 'admin/files', 
        component: FileComponent,
        canActivate: [AuthGuard]
    },
    {path: 'admin/config', 
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
