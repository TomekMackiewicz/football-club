import { Component, ViewChild, AfterViewInit } from '@angular/core';
import { MatPaginator, MatSort } from '@angular/material';
import { merge, of as observableOf } from 'rxjs';
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
    data: Game[] = [];
    selection = new SelectionModel<Game>(true, []);
    resultsLength = 0;
    isLoadingResults = true;
    isRateLimitReached = false;

    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(private gameService: GameService) {}

    ngAfterViewInit() {       
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);

    merge(this.sort.sortChange, this.paginator.page)
        .pipe(
            startWith({}),
            switchMap(() => {
                this.isLoadingResults = true;
                return this.gameService.getGames(
                    this.sort.active, this.sort.direction, this.paginator.pageIndex, this.paginator.pageSize
                );
            }),
            map(data => {
                this.isLoadingResults = false;
                this.isRateLimitReached = false;
                this.resultsLength = data.total_count;

                return data.games;
            }),
            catchError(() => {
                this.isLoadingResults = false;
                this.isRateLimitReached = true;
                return observableOf([]);
            })
        ).subscribe(data => this.data = data);
    }

    isAllSelected() {
        const numSelected = this.selection.selected.length;
        const numRows = this.data.length;
        return numSelected === numRows;
    }

    masterToggle() {
        this.isAllSelected() ?
            this.selection.clear() :
            this.data.forEach(row => this.selection.select(row));
    }

    applyFilter(filterValue: string) {
        //this.data.filter = filterValue.trim().toLowerCase();

        if (this.paginator) {
            this.paginator.firstPage();
        }
    }
        
}
// @TODO
export interface Games {
    games: Game[];
    total_count: number;
}