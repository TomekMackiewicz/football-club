import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { DragToSelectModule } from 'ngx-drag-to-select';
import { FlexLayoutModule } from '@angular/flex-layout';
import { 
    MatSidenavModule, 
    MatCheckboxModule, 
    MatToolbarModule, 
    MatButtonModule, 
    MatIconModule, 
    MatMenuModule,
    MatListModule,
    MatProgressSpinnerModule,
    MatProgressBarModule,
    MatDialogModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule
} from '@angular/material';

import { AppRoutingModule } from './app-routing.module';
import { AlertModule } from './alert/alert.module';
import { GameModule } from './game/game.module';
import { TrainerModule } from './trainer/trainer.module';
import { UserModule } from './user/user.module';
import { PostModule } from './post/post.module';
import { CategoryModule } from './category/category.module';
import { FileModule } from './file/file.module';
import { ConfigModule } from './config/config.module';

import { AppComponent } from './app.component';
import { MenuListItemComponent } from './menu-list-item/menu-list-item.component';
import { LoaderComponent } from './shared/loader/loader.component';
import { ConfirmDialogComponent } from './dialogs/confirm-dialog/confirm-dialog.component';
import { FrontComponent } from './front/front.component';
import { AccessDeniedComponent } from './denied/denied.component';
import { LoginComponent } from './login/login.component';
import { LogoutComponent } from './logout/logout.component';
import { ProfileComponent } from './profile/profile.component';

import { AuthGuard } from './guards/auth.guard';

import { NavService } from './services/nav.service';
import { LoaderService } from './services/loader.service';
import { AuthenticationService } from './services/authentication.service';
import { ProfileService } from './profile/profile.service';

import { ApplicationPipesModule } from './pipes/application-pipes.module';

import { LoaderInterceptor } from './interceptors/loader.interceptor';
import { TokenInterceptor } from './interceptors/token.interceptor';

@NgModule({
    declarations: [
        AppComponent,
        MenuListItemComponent,
        LoaderComponent,
        ConfirmDialogComponent,
        FrontComponent,
        AccessDeniedComponent,
        LoginComponent,
        LogoutComponent,
        ProfileComponent
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        AppRoutingModule,
        AlertModule,
        FormsModule,
        FlexLayoutModule,
        MatSidenavModule,
        MatCheckboxModule,
        MatToolbarModule,
        MatButtonModule,
        MatIconModule,
        MatMenuModule,
        MatListModule,
        MatProgressSpinnerModule,
        MatProgressBarModule,
        MatDialogModule,
        MatCardModule,
        MatFormFieldModule,
        MatInputModule,
        HttpClientModule,
        ApplicationPipesModule,
        DragToSelectModule.forRoot(),
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        }),
        GameModule,
        TrainerModule,
        PostModule,
        CategoryModule,
        FileModule,
        ConfigModule,
        UserModule
    ],
    providers: [
        AuthGuard,
        TokenInterceptor,
        NavService, 
        LoaderService,
        AuthenticationService, 
        ProfileService,
        { 
            provide: HTTP_INTERCEPTORS, 
            useClass: LoaderInterceptor, 
            multi: true 
        },
        {
            provide: HTTP_INTERCEPTORS,
            useClass: TokenInterceptor,
            multi: true
        }
    ],
    entryComponents: [ConfirmDialogComponent],
    bootstrap: [AppComponent]
})
export class AppModule { }

export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http);
}