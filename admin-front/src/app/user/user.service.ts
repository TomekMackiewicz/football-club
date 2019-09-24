import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { DatePipe } from '@angular/common';
import { User } from '../model/user';
import { HTTP_OPTIONS, ADMIN_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class UserService {
            
    constructor(
        private httpClient: HttpClient,
        public datepipe: DatePipe
    ) {}

    getUser(id: number): Observable<User> {     
        return this.httpClient.get<User>(ADMIN_URL+'/users/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getUsers(sort: string, order: string, page: number, size: number, filters: any): Observable<UsersWithCount> {
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters); 
               
        return this.httpClient.get<UsersWithCount>(ADMIN_URL+'/users?'+params)
            .pipe(catchError(this.handleError));   
    }
    
    addUser(user: User): Observable<string> {
        return this.httpClient.post<string>(ADMIN_URL+'/users', user, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    updateUser(user: User): Observable<string> {
        return this.httpClient.patch<string>(ADMIN_URL+'/users/'+user.id, user, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
       
    deleteUsers(ids: Array<number>): Observable<string> {            
        return this.httpClient.request<string>('delete', ADMIN_URL+'/users', { body: ids })
            .pipe(catchError(this.handleError));
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
        console.log(error);
        
        return throwError('error');
    };
                 
}

export interface UsersWithCount {
    users: User[];
    total_count: number;
}