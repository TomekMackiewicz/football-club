import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { DatePipe } from '@angular/common';
import { Game } from '../model/game';
import { HTTP_OPTIONS, API_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class GameService {
            
    constructor(
        private httpClient: HttpClient,
        public datepipe: DatePipe
    ) {}

    getGame(id: number): Observable<Game> {     
        return this.httpClient.get<Game>(API_URL+'/game/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getGames(sort: string, order: string, page: number, size: number, filters: any): Observable<Games> {
        filters.dateFrom = this.datepipe.transform(filters.dateFrom, 'yyyy-MM-dd');
        filters.dateTo = this.datepipe.transform(filters.dateTo, 'yyyy-MM-dd');  
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters); 
               
        return this.httpClient.get<Games>(API_URL+'/game/all?'+params)
            .pipe(catchError(this.handleError));   
    }
    
    // @TODO or object?
    addGame(game: Game): Observable<string> {
        return this.httpClient.post<string>(API_URL+'/game/new', game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    // @TODO or object?
    updateGame(game: Game): Observable<string> {
        return this.httpClient.patch<string>(API_URL+'/game/update', game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    // @TODO pass id's as flat array    
    deleteGames(ids: Array<number>): Observable<string> {            
        return this.httpClient.request<string>('delete', API_URL+'/game/delete', { body: { ids }})
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

// @TODO
export interface Games {
    games: Game[];
    total_count: number;
}