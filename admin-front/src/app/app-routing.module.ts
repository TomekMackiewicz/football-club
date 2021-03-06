import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { GameListComponent } from './game/game-list/game-list.component';
import { GameAddComponent } from './game/game-add/game-add.component';
import { GameEditComponent } from './game/game-edit/game-edit.component';
import { TrainerListComponent } from './trainer/trainer-list/trainer-list.component';
import { TrainerAddComponent } from './trainer/trainer-add/trainer-add.component';
import { TrainerEditComponent } from './trainer/trainer-edit/trainer-edit.component';
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
import { UserListComponent } from './user/user-list/user-list.component';
import { DashboardComponent } from './dashboard/dashboard.component';

import { AuthGuard } from './guards/auth.guard';

const routes: Routes = [
    {path: 'login', 
        component: LoginComponent,
        canActivate: [AuthGuard]                      
    },
    {path: 'logout', 
        component: LogoutComponent                      
    },
    {path: 'admin/dashboard',
        component: DashboardComponent,
        canActivate: [AuthGuard],
        data: {
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/profile', 
        component: ProfileComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                       
    },
    {path: 'admin/games/list', 
        component: GameListComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                       
    }, 
    {path: 'admin/games/add', 
        component: GameAddComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                      
    },
    {path: 'admin/games/edit/:id', 
        component: GameEditComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                      
    },
    {path: 'admin/trainers/list', 
        component: TrainerListComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                       
    }, 
    {path: 'admin/trainers/add', 
        component: TrainerAddComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                      
    },
    {path: 'admin/trainers/edit/:id', 
        component: TrainerEditComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                      
    },
    {path: 'admin/posts/list', 
        component: PostListComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    }, 
    {path: 'admin/posts/add', 
        component: PostAddComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/posts/edit/:id', 
        component: PostEditComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/categories/list', 
        component: CategoryListComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }                     
    }, 
    {path: 'admin/categories/add', 
        component: CategoryAddComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/categories/edit/:id', 
        component: CategoryEditComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/files', 
        component: FileComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_ADMIN'
        }
    },
    {path: 'admin/config', 
        component: ConfigComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_SUPER_ADMIN'
        }
    },
    {path: 'admin/users/list', 
        component: UserListComponent,
        canActivate: [AuthGuard],
        data: { 
          expectedRole: 'ROLE_SUPER_ADMIN'
        }
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
