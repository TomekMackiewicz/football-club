import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpRequest } from '@angular/common/http';
import { catchError } from 'rxjs/operators';
import { v4 } from 'uuid';
import { FileElement } from '../file/model/element';
import { Observable, BehaviorSubject, throwError } from 'rxjs';
import { HTTP_OPTIONS, ADMIN_URL } from '../constants/http';

@Injectable()
export class FileService {
    private map = new Map<string, FileElement>();

    constructor(
        private httpClient: HttpClient
    ) {}

    clearData() {
        this.map.clear();
    }

    load(fileElement: FileElement) {
        this.map.set(fileElement.id, this.clone(fileElement));
        return fileElement;
    }

    getFiles() {
        return this.httpClient.get(ADMIN_URL+'/files').pipe(catchError(this.handleError));
    } 

    add(fileElement: FileElement) {
        fileElement.id = v4();
        this.map.set(fileElement.id, this.clone(fileElement));

        return fileElement;
    }

    createFolder(fileElement: FileElement) {
        return this.httpClient.post<FileElement>(ADMIN_URL+'/files', { fileElement: fileElement }, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }

    updateFiles(elements: FileElement[], update: Partial<FileElement>, moveTo: any = null) {
        var files = {};
        elements.forEach((elem, index) => {
            let element = this.map.get(elem.id);
            let oldName = elem.name;
            element = Object.assign(element, update);
            var x = 'oldName'; 
            files[index] = element;
            files[index][x] = oldName;
            this.map.set(elem.id, elem);
        });
        
        return this.httpClient.patch<FileElement>(ADMIN_URL+'/files', { 
            files: files, 
            moveTo: moveTo 
        }, HTTP_OPTIONS).pipe(catchError(this.handleError));
    }
    
    renameFile(id: string, update: Partial<FileElement>) {
        let element = this.map.get(id);
        let oldName = element.name;
        element = Object.assign(element, update);
        this.map.set(element.id, element); // zob 1)
        return this.httpClient.patch<FileElement>(ADMIN_URL+'/files', { 
            file: element, 
            oldName: oldName 
        }, HTTP_OPTIONS).pipe(catchError(this.handleError));
    }

    deleteFiles(elements: FileElement[]) {
        const req = new HttpRequest('DELETE', ADMIN_URL+'/files', elements, {
            reportProgress: true
        });
        return this.httpClient.request(req).pipe(catchError(this.handleError));
    } 

    removeFiles(files: FileElement[]) {
        files.forEach((file) => {
            this.map.delete(file.id);
        })
    }

    private querySubject: BehaviorSubject<FileElement[]>;
    
    queryInFolder(folderId: string) {
        const result: FileElement[] = [];
        this.map.forEach(element => {
            // Root element
            if (element.parent == folderId) {
                result.push(this.clone(element));
                return;
            }
            if (element.parent.id === folderId) {
                result.push(this.clone(element));
            }
        });
        if (!this.querySubject) {
            this.querySubject = new BehaviorSubject(result);
        } else {
            this.querySubject.next(result);
        }
        
        return this.querySubject.asObservable();
    }

    get(id: string) {
        return this.map.get(id);
    }

    clone(element: FileElement) {
        return JSON.parse(JSON.stringify(element));
    }
    
    private handleError(error: HttpErrorResponse) {
        var errorMsgDev;

        if (error.error instanceof ErrorEvent) {
            // A client-side or network error occurred.
            errorMsgDev = error.error.message;
        } else {
            // The backend returned an unsuccessful response code.
            errorMsgDev = error.status + ' ' + error.error;
        }
        console.log(errorMsgDev);
        
        return throwError('error');
    };
}
