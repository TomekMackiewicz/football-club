import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { DatePipe } from '@angular/common';
import { Post } from '../model/post';
import { HTTP_OPTIONS, ADMIN_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class PostService {
            
    constructor(
        private httpClient: HttpClient,
        public datepipe: DatePipe
    ) {}

    getPost(id: number): Observable<Post> {     
        return this.httpClient.get<Post>(ADMIN_URL+'/posts/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getPosts(sort: string, order: string, page: number, size: number, filters: any): Observable<PostsWithCount> {
        filters.dateFrom = this.datepipe.transform(filters.dateFrom, 'yyyy-MM-dd');
        filters.dateTo = this.datepipe.transform(filters.dateTo, 'yyyy-MM-dd');  
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters); 
               
        return this.httpClient.get<PostsWithCount>(ADMIN_URL+'/posts?'+params)
            .pipe(catchError(this.handleError));   
    }
    
    addPost(post: Post): Observable<string> {
        return this.httpClient.post<string>(ADMIN_URL+'/posts', post, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    updatePost(post: Post): Observable<string> {
        return this.httpClient.patch<string>(ADMIN_URL+'/posts/'+post.id, post, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
       
    deletePosts(ids: Array<number>): Observable<string> {            
        return this.httpClient.request<string>('delete', ADMIN_URL+'/posts', { body: ids })
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

export interface PostsWithCount {
    posts: Post[];
    total_count: number;
}