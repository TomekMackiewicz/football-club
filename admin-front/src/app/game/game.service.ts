import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Game } from '../model/game';

@Injectable({
    providedIn: 'root'
})
export class GameService {
    href = 'http://localhost:8000/api/game';
            
    constructor(private httpClient: HttpClient) {}
    
    public getGames(sort: string, order: string, page: number): Observable<Games> {
        return this.httpClient.get<Games>(this.href+'/all?sort='+sort+'&order='+order+'&page='+page + 1);
    }
    
}

export interface Games {
    games: Game[];
    total_count: number;
}