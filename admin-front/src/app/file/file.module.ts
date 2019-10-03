import { NgModule } from '@angular/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { FlexLayoutModule } from '@angular/flex-layout';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import {
    MatDialogModule,
    MatToolbarModule,
    MatIconModule,
    MatTableModule,
    MatGridListModule,
    MatMenuModule,
    MatInputModule,
    MatButtonModule,
    MatCardModule,
    MatButtonToggleModule,
    MatCheckboxModule,
    MatTooltipModule,
    MatSliderModule
} from '@angular/material';

import { FormsModule } from '@angular/forms';
import { DragDropModule } from '@angular/cdk/drag-drop';
import { DragToSelectModule } from 'ngx-drag-to-select';

import { FileComponent } from './file.component';
import { FileExplorerComponent } from './file-explorer/file-explorer.component';
import { NewFolderDialogComponent } from './modals/new-folder-dialog/new-folder-dialog.component';
import { RenameDialogComponent } from './modals/rename-dialog/rename-dialog.component';

import { FileService } from './file.service';

import { ApplicationPipesModule } from '../pipes/application-pipes.module';

@NgModule({
    imports: [
        CommonModule,
        HttpClientModule,
        MatToolbarModule,
        FlexLayoutModule,
        MatIconModule,
        MatTableModule,
        MatGridListModule,
        MatMenuModule,
        BrowserAnimationsModule,
        MatDialogModule,
        MatInputModule,
        FormsModule,
        DragDropModule,
        DragToSelectModule,
        MatButtonModule,
        MatCardModule,
        MatButtonToggleModule,
        MatCheckboxModule,
        MatTooltipModule,
        MatSliderModule,
        ApplicationPipesModule,
        TranslateModule.forRoot({
            loader: {
                provide: TranslateLoader,
                useFactory: HttpLoaderFactory,
                deps: [HttpClient]
            }
        })
    ],
    declarations: [
        FileComponent,
        FileExplorerComponent, 
        NewFolderDialogComponent, 
        RenameDialogComponent
    ],
    providers: [FileService],
    exports: [
        FileComponent,
        FileExplorerComponent
    ],
    entryComponents: [NewFolderDialogComponent, RenameDialogComponent],
})
export class FileModule {}

export function HttpLoaderFactory(http: HttpClient) {
    return new TranslateHttpLoader(http);
}