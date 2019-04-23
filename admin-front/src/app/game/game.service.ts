import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Game } from '../model/game';
import { HTTP_OPTIONS, API_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class GameService {
            
    constructor(private httpClient: HttpClient) {}
    
    getGames(sort: string, order: string, page: number): Observable<Games> {
        return this.httpClient.get<Games>(API_URL+'/game/all?sort='+sort+'&order='+order+'&page='+page + 1)
            .pipe(catchError(this.handleError));
    }

    addGame(game: Game): Observable<Game> {
        return this.httpClient.post<Game>(API_URL+'/game/new', game, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }    

    private handleError(error: HttpErrorResponse) {
        if (error.error instanceof ErrorEvent) {
            // A client-side or network error occurred. Handle it accordingly.
            console.error('An error occurred:', error.error.message);
        } else {
            // The backend returned an unsuccessful response code.
            // The response body may contain clues as to what went wrong,
            console.error(
                `Backend returned code ${error.status}, ` +
                `body was: ${error.error}`
            );
        }
        // return an observable with a user-facing error message
        return throwError('Something bad happened; please try again later.');
    };    
                
}

// @TODO
export interface Games {
    games: Game[];
    total_count: number;
}