import { NgModule } from '@angular/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { CommonModule } from '@angular/common';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { FlexLayoutModule } from '@angular/flex-layout';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatDialogModule } from '@angular/material/dialog';
import {
    MatToolbarModule,
    MatIconModule, 
    MatListModule,
    MatGridListModule,
    MatMenuModule,
    MatInputModule,
    MatButtonModule,
    MatCardModule,
    MatButtonToggleModule
} from '@angular/material';

import { FormsModule } from '@angular/forms';
import { DragDropModule } from '@angular/cdk/drag-drop';
//import { DragulaModule } from 'ng2-dragula';

import { FileComponent } from './file.component';
import { FileExplorerComponent } from './file-explorer/file-explorer.component';
import { NewFolderDialogComponent } from './modals/new-folder-dialog/new-folder-dialog.component';
import { RenameDialogComponent } from './modals/rename-dialog/rename-dialog.component';

import { ApplicationPipesModule } from '../pipes/application-pipes.module';

@NgModule({
    imports: [
        CommonModule,
        HttpClientModule,
        MatToolbarModule,
        FlexLayoutModule,
        MatIconModule,
        MatListModule,
        MatGridListModule,
        MatMenuModule,
        BrowserAnimationsModule,
        MatDialogModule,
        MatInputModule,
        FormsModule,
        DragDropModule,
        //DragulaModule.forRoot(),
        MatButtonModule,
        MatCardModule,
        MatButtonToggleModule,
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