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
        const folderA = this.fileService.add({ name: 'Folder A', isFolder: true, parent: 'root', path: '' });
        this.fileService.add({ name: 'Folder B', isFolder: true, parent: 'root', path: '' });
        this.fileService.add({ name: 'Folder C', isFolder: true, parent: folderA.id, path: '' });
        this.fileService.add({ name: 'File A', isFolder: false, parent: 'root', path: '' });
        this.fileService.add({ name: 'File B', isFolder: false, parent: 'root', path: '' });

        this.updateFileElementQuery();        
    }

    addFolder(folder: FileElement) {
        this.fileService.createFolder({ 
            isFolder: true, 
            name: folder.name, 
            parent: this.currentRoot ? this.currentRoot.id : 'root', 
            path: this.currentPath ? this.currentPath : ''
        }).subscribe(
            success => {
                console.log(success);
            },
            error => {
                console.log(error);
            }
        );
        this.updateFileElementQuery();
    }

    removeElement(element: FileElement) {
        this.fileService.delete(element.id);
        this.updateFileElementQuery();
    }

    moveElement(event: { element: FileElement; moveTo: FileElement }) {
        this.fileService.updateFolder(event.element.id, { parent: event.moveTo.id });
        this.updateFileElementQuery();
    }

    renameElement(element: FileElement) {
        this.fileService.updateFolder(element.id, { name: element.name }).subscribe(
            success => {
                console.log(success);
            },
            error => {
                console.log(error);
            }
        );
        this.updateFileElementQuery();
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
