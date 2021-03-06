import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { FileElement } from './model/element';
import { FileService } from './file.service';

@Component({
    selector: 'file',
    templateUrl: './file.component.html'
})

export class FileComponent implements OnInit {
    fileElements: Observable<FileElement[]>;
    currentRoot: FileElement;
    currentPath: string;
    canNavigateUp = false;

    constructor(
        private fileService: FileService
    ) { }
    
    ngOnInit() {
        this.nullData();
        this.getFiles();        
    }

    nullData() {
        this.fileService.clearData();         
    }

    getFiles() {
        this.fileService.getFiles().subscribe(
            (data: any) => {               
                for (let file of data.files) {
                    this.fileService.load(file);                   
                }
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );        
    }

    addFolder(fileElement: FileElement) {
        this.fileService.createFolder({ 
            isFolder: true, 
            name: fileElement.name, 
            parent: this.currentRoot ? this.currentRoot.id : 'root', 
            path: this.currentPath ? this.currentPath : ''
        }).subscribe(
            data => {
                this.fileService.add({
                    isFolder: true, 
                    name: fileElement.name, 
                    parent: this.currentRoot ? this.currentRoot.id : 'root', 
                    path: this.currentPath ? this.currentPath : ''
                });
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );
    }

    removeElement(event: { elements: FileElement[] }) {
        this.fileService.deleteFiles(event.elements).subscribe(
            data => {
                this.fileService.removeFiles(event.elements);
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );
    }

    moveElement(event: { elements: FileElement[]; moveTo: FileElement }) {
        this.fileService.updateFiles(event.elements, { parent: event.moveTo }, event.moveTo).subscribe(
            data => {
                this.updateMovedFilesPath(event.elements, event.moveTo);
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );
    }

    updateMovedFilesPath(files: FileElement[], moveTo: FileElement) {
        files.forEach((file: FileElement) => {
            file.path = (typeof file.parent.parent !== "undefined" && file.parent.parent.id === moveTo.id) ? 
                this.popFromPath(this.currentPath) : this.pushToPath(this.currentPath, moveTo.name);
            file.parent = moveTo;
        });
    }

    renameElement(element: FileElement) {
        this.fileService.renameFile(element.id, { name: element.name }).subscribe(
            data => {
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );
    }
    
    updateFileElementQuery() {
        this.fileElements = this.fileService.queryInFolder(this.currentRoot ? this.currentRoot.id : 'root');
    }  

    navigateUp() {
        if (this.currentRoot && this.currentRoot.parent === 'root') {
            this.currentRoot = null;
            this.canNavigateUp = false;
            this.updateFileElementQuery();
        } else {
            this.currentRoot = this.fileService.get(this.currentRoot.parent.id);
            this.updateFileElementQuery();
        }
        this.currentPath = this.popFromPath(this.currentPath);
    }

    navigateToFolder(element: FileElement) {
        this.currentRoot = element;
        this.updateFileElementQuery();
        this.currentPath = this.pushToPath(this.currentPath, element.name);
        this.canNavigateUp = true;
    }

    pushToPath(path: string, folderName: string) {
        let p = path ? path : '';
        p += `${folderName}/`;
        return p;
    }

    popFromPath(path: string) {
        let p = path ? path : '';
        let split = p.split('/');
        split.splice(split.length - 2, 1);
        p = split.join('/');
        return p;
    }       
}
