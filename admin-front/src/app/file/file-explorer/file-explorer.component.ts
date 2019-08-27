import { Component, Input, Output, EventEmitter } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { MatDialog, MatMenuTrigger } from '@angular/material';
import { SelectionModel } from '@angular/cdk/collections';
import { FileElement } from '../model/element';
import { NewFolderDialogComponent } from '../modals/new-folder-dialog/new-folder-dialog.component';
import { RenameDialogComponent } from '../modals/rename-dialog/rename-dialog.component';

@Component({
    selector: 'file-explorer',
    templateUrl: './file-explorer.component.html',
    styleUrls: ['./file-explorer.component.css'],
})

export class FileExplorerComponent {
    @Input() fileElements: FileElement[];
    @Input() canNavigateUp: string;
    @Input() path: string;

    @Output() folderAdded = new EventEmitter<{ name: string }>();
    @Output() elementRemoved = new EventEmitter<{
        elements: FileElement[]
    }>();
    @Output() elementRenamed = new EventEmitter<FileElement>();
    @Output() elementMoved = new EventEmitter<{
        elements: FileElement[]
        moveTo: FileElement
    }>();
    @Output() elementMovedUp = new EventEmitter<{
        elements: FileElement[]
        moveTo: string
    }>();
    @Output() navigatedDown = new EventEmitter<FileElement>();
    @Output() navigatedUp = new EventEmitter();

    displayedColumns: string[] = ['select', 'preview', 'name', 'type', 'size'];
    selection = new SelectionModel<FileElement>(true, []);
    selectedFiles: Array<FileElement> = [];
    filesView: string = 'grid';
    fileSize = 50;

    constructor(
        public dialog: MatDialog,
        private sanitizer: DomSanitizer
    ) {}

    switchFilesView(view: string) {
        this.filesView = view;
    }

    selectFiles($event: FileElement[]) {
        const fileSelected = roleParam => $event.some( ({name}) => name == roleParam);
        if (this.fileElements !== null) {
            this.fileElements.forEach((file) => {
                file.selected = true === fileSelected(file.name) ? true : false;
            });
        }
    }

    deleteElement(element: FileElement) {
        // One or many (if selected)
        if (this.selectedFiles.length > 0) {
            var selection = this.selectedFiles;
        } else {
            var selection: FileElement[] = [element];
        }
        this.elementRemoved.emit({ elements: selection });
    }

    navigate(element: FileElement) {
        if (element.isFolder) {
            this.navigatedDown.emit(element);
        }
    }

    navigateUp() {
        this.navigatedUp.emit();
    }

    moveElement(element: FileElement, moveTo: FileElement) {
        // One or many (if selected)
        if (this.selectedFiles.length > 0) {
            var selection = this.selectedFiles;
        } else {
            var selection: FileElement[] = [element];
        }

        this.elementMoved.emit({ elements: selection, moveTo: moveTo });
    }

    openNewFolderDialog() {
        let dialogRef = this.dialog.open(NewFolderDialogComponent);
        dialogRef.afterClosed().subscribe(res => {
            if (res) {
                this.folderAdded.emit({ name: res });
            }
        });
    }

    openRenameDialog(element: FileElement) {
        let dialogRef = this.dialog.open(RenameDialogComponent);
        dialogRef.afterClosed().subscribe(res => {
            if (res) {
                element.name = res;
                this.elementRenamed.emit(element);
            }
        });
    }

    openMenu(event: MouseEvent, viewChild: MatMenuTrigger) {
        event.preventDefault();
        viewChild.openMenu();
    }

    isAllSelected() {
        const numSelected = this.selection.selected.length;
        const numRows = this.fileElements.length;
        return numSelected === numRows;
    }

    masterToggle() {
        this.isAllSelected() ?
            this.selection.clear() :
            this.fileElements.forEach(row => this.selection.select(row));
    }

    formatLabel(value: number | null) {
        if (!value) {
            return 0;
        }

        if (value === 50) {
            return 'S';
        }
        if (value === 100) {
            return 'M';
        }
        if (value === 150) {
            return 'L';
        }

        return value;
    }

    getFileWidth() {
        return this.sanitizer.bypassSecurityTrustStyle(String(this.fileSize));
    }

    getFileHeight() {
        return this.sanitizer.bypassSecurityTrustStyle(String(this.fileSize));
    }

    getFileFontSize() {
        return this.sanitizer.bypassSecurityTrustStyle(String(this.fileSize));
    }

    getRowHeight() {
        let height = String(this.fileSize * 3);
        return this.sanitizer.bypassSecurityTrustStyle(height);
    }

}
