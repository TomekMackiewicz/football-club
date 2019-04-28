import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Game } from '../model/game';
import { HTTP_OPTIONS, API_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class GameService {
            
    constructor(private httpClient: HttpClient) {}

    getGame(id: number): Observable<Game> {     
        return this.httpClient.get<Game>(API_URL+'/game/'+id)
            .pipe(catchError(this.handleError));   
    }    
        
    getGames(sort: string, order: string, page: number, size: number): Observable<Games> {     
        return this.httpClient.get<Games>(API_URL+'/game/all?sort='+sort+'&order='+order+'&page='+page+'&size='+size)
            .pipe(catchError(this.handleError));   
    }

    addGame(game: Game): Observable<string> {
        return this.httpClient.post<string>(API_URL+'/game/new', game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }
    
    editGame() {
        
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