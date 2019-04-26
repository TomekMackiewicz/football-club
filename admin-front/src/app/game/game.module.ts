import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';

import { 
    MatPaginatorModule,
    MatProgressSpinnerModule,
    MatTableModule,
    MatSortModule,
    MatCheckboxModule,
    MatFormFieldModule,
    MatInputModule,
    MatDatepickerModule,
    MatNativeDateModule,
    MatIconModule,
    MatSelectModule,
    MatButtonModule,
    MatCardModule,
    MatToolbarModule,
    MatDialogModule
} from '@angular/material';
import { FlexLayoutModule } from '@angular/flex-layout';

import { ApplicationPipesModule } from '../pipes/application-pipes.module';

import { GameListComponent } from './game-list/game-list.component';
import { GameAddComponent } from './game-add/game-add.component';
import { ConfirmDialogComponent } from '../dialogs/confirm-dialog/confirm-dialog.component';

import { GameService } from './game.service';

@NgModule({
    declarations: [
        GameListComponent,
        GameAddComponent,
        ConfirmDialogComponent
    ],
    imports: [
        CommonModule,
        HttpClientModule,
        ReactiveFormsModule,
        MatPaginatorModule,
        MatProgressSpinnerModule,
        MatTableModule,
        MatSortModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatInputModule,
        MatDatepickerModule,
        MatNativeDateModule,
        MatIconModule,
        MatSelectModule,
        MatButtonModule,
        MatCardModule,
        MatToolbarModule,
        MatDialogModule,
        FlexLayoutModule,
        HttpClientModule,
        ApplicationPipesModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })
    ],
    providers: [GameService],
    entryComponents: [ConfirmDialogComponent]
})
export class GameModule { }

export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http);
}