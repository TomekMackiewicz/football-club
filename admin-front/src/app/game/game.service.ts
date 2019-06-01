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
        return this.httpClient.get<Game>(API_URL+'/games/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getGames(sort: string, order: string, page: number, size: number, filters: any): Observable<GamesWithCount> {
        filters.dateFrom = this.datepipe.transform(filters.dateFrom, 'yyyy-MM-dd');
        filters.dateTo = this.datepipe.transform(filters.dateTo, 'yyyy-MM-dd');  
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters); 
               
        return this.httpClient.get<GamesWithCount>(API_URL+'/games?'+params)
            .pipe(catchError(this.handleError));   
    }
    
    addGame(game: Game): Observable<string> {
        return this.httpClient.post<string>(API_URL+'/games', game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    updateGame(game: Game): Observable<string> {
        return this.httpClient.patch<string>(API_URL+'/games/'+game.id, game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
       
    deleteGames(ids: Array<number>): Observable<string> {            
        return this.httpClient.request<string>('delete', API_URL+'/games', { body: ids })
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

export interface GamesWithCount {
    games: Game[];
    total_count: number;
}