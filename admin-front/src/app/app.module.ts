import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { HttpClient, HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { DragToSelectModule } from 'ngx-drag-to-select';
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
    MatDialogModule
} from '@angular/material';

import { AppRoutingModule } from './app-routing.module';
import { AlertModule } from './alert/alert.module';
import { GameModule } from './game/game.module';
import { PostModule } from './post/post.module';
import { CategoryModule } from './category/category.module';
import { FileModule } from './file/file.module';

import { AppComponent } from './app.component';
import { MenuListItemComponent } from './menu-list-item/menu-list-item.component';
import { LoaderComponent } from './shared/loader/loader.component';
import { ConfirmDialogComponent } from './dialogs/confirm-dialog/confirm-dialog.component';

import { NavService } from './services/nav.service';
import { LoaderService } from './services/loader.service';
import { FileService } from './services/file.service';

import { ApplicationPipesModule } from './pipes/application-pipes.module';

import { LoaderInterceptor } from './interceptors/loader.interceptor';

@NgModule({
    declarations: [
        AppComponent,
        MenuListItemComponent,
        LoaderComponent,
        ConfirmDialogComponent
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        AppRoutingModule,
        AlertModule,
        FormsModule,
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
        PostModule,
        CategoryModule,
        FileModule
    ],
    providers: [
        NavService, 
        FileService, 
        LoaderService, 
        { provide: HTTP_INTERCEPTORS, useClass: LoaderInterceptor, multi: true }
    ],
    entryComponents: [ConfirmDialogComponent],
    bootstrap: [AppComponent]
})
export class AppModule { }

export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http);
}