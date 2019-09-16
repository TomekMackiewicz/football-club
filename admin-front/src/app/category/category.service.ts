import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { DatePipe } from '@angular/common';
import { Category } from '../model/category';
import { HTTP_OPTIONS, ADMIN_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class CategoryService {
            
    constructor(
        private httpClient: HttpClient,
        public datepipe: DatePipe
    ) {}

    getCategory(id: number): Observable<Category> {     
        return this.httpClient.get<Category>(ADMIN_URL+'/categories/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getCategories(sort: string = '', order: string = '', page: number = 0, size: number = 0, filters: any = []): Observable<CategoriesWithCount> { 
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters); 
               
        return this.httpClient.get<CategoriesWithCount>(ADMIN_URL+'/categories?'+params)
            .pipe(catchError(this.handleError));   
    }
        
    addCategory(category: Category): Observable<string> {
        return this.httpClient.post<string>(ADMIN_URL+'/categories', category, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    updateCategory(category: Category): Observable<string> {
        return this.httpClient.patch<string>(ADMIN_URL+'/categories/'+category.id, category, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
       
    deleteCategories(ids: Array<number>): Observable<string> {            
        return this.httpClient.request<string>('delete', ADMIN_URL+'/categories', { body: ids })
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
        console.log(errorMsgDev);
        
        return throwError('error');
    };
                 
}

export interface CategoriesWithCount {
    categories: Category[];
    total_count: number;
}