import { HttpClient } from '@angular/common/http';
import { Component, ViewChild, AfterViewInit } from '@angular/core';
import { MatPaginator, MatSort, MatTableDataSource } from '@angular/material';
import { merge, Observable, of as observableOf } from 'rxjs';
import { catchError, map, startWith, switchMap } from 'rxjs/operators';
import { SelectionModel } from '@angular/cdk/collections';
import { GameService } from '../game.service';
import { Game } from '../../model/game';

@Component({
    selector: 'app-game-list',
    templateUrl: './game-list.component.html',
})
export class GameListComponent implements AfterViewInit {
    displayedColumns: string[] = ['select', 'date', 'location', 'game_type', 'host_team', 'guest_team', 'host_score', 'guest_score'];
    games: Array<Game>;
    selection = new SelectionModel<Game>(true, []);
    dataSource: MatTableDataSource<Game>;
    total: number = 0;

    resultsLength = 0;
    isLoadingResults = true;
    isRateLimitReached = false;

    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(
        private http: HttpClient,
        private gameService: GameService
    ) {}

    ngAfterViewInit() {
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
        this.getGames(this.sort.active, this.sort.direction, this.paginator.pageIndex);
    }

    isAllSelected() {
        const numSelected = this.selection.selected.length;
        const numRows = this.games.length;
        return numSelected === numRows;
    }

    masterToggle() {
        this.isAllSelected() ?
            this.selection.clear() :
            this.games.forEach(row => this.selection.select(row));
    }

    applyFilter(filterValue: string) {
        //this.data.filter = filterValue.trim().toLowerCase();

        if (this.paginator) {
            this.paginator.firstPage();
        }
    }

    getGames(sort: string, order: string, page: number): Observable<Games> {
        this.gameService.getGames(sort, order, page).subscribe(
            (data: Games) => {
                this.games = data.games;
                this.total = data.total_count;
                this.dataSource = new MatTableDataSource(this.games);
                this.isLoadingResults = false;
            },
            error => {
                console.log(error);
            }
        );
        
        return;
    }
          
}
// @TODO
export interface Games {
    games: Game[];
    total_count: number;
}