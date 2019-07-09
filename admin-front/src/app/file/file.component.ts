import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { FileElement } from './model/element';
import { FileService } from '../services/file.service';

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

    removeElement(element: FileElement) {
        this.fileService.delete(element.id);
        this.updateFileElementQuery();
    }

    moveElement(event: { element: FileElement; moveTo: FileElement }) {
        this.fileService.updateFolder(event.element.id, { parent: event.moveTo.id }, event.moveTo).subscribe(
            data => {
                this.updateFileElementQuery();
            },
            error => {
                console.log(error);
            }
        );
    }
//    moveElement(event: { elements: FileElement[]; moveTo: FileElement }) {
//        for (let selected of event.elements) {
//            this.fileService.updateFolder(selected.id, { parent: event.moveTo.id }).subscribe(
//                data => {
//                    console.log(data);
//                },
//                error => {
//                    console.log(error);
//                }
//            );
//        }
//        this.updateFileElementQuery();
//    }
    renameElement(element: FileElement) {
        this.fileService.updateFolder(element.id, { name: element.name }).subscribe(
            data => {
                console.log(data);
            },
            error => {
                console.log(error);
            }
        );
        this.updateFileElementQuery(); // ?
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
            this.currentRoot = this.fileService.get(this.currentRoot.parent);
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
