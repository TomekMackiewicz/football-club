import { Component, ViewChild, AfterViewInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { MatPaginator, MatSort, MatDialog, MatDialogConfig } from '@angular/material';
import { merge, of as observableOf } from 'rxjs';
import { catchError, map, startWith, switchMap } from 'rxjs/operators';
import { SelectionModel } from '@angular/cdk/collections';
import { FormBuilder } from '@angular/forms';
import { GameService } from '../game.service';
import { AlertService } from '../../alert/alert.service';
import { Game } from '../../model/game';
import { ConfirmDialogComponent } from '../../dialogs/confirm-dialog/confirm-dialog.component';

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
    
    filterForm = this.fb.group({
        dateFrom: [''],
        dateTo: [''],
        location: [''],
        gameType: [''],
        team: ['']
    });
    filterPanelOpenState = true;
    
    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(
        private router: Router,
        private gameService: GameService,
        private alertService: AlertService,
        public dialog: MatDialog,
        private fb: FormBuilder
    ) {}

    ngAfterViewInit() {       
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
        this.getGames();
    }
    
    getGames() {
        merge(this.sort.sortChange, this.paginator.page).pipe(
            startWith({}),
            switchMap(() => {
                this.isLoadingResults = true;
                return this.gameService.getGames(
                    this.sort.active, this.sort.direction, this.paginator.pageIndex+1, this.paginator.pageSize
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

    applyFilter() {
        this.getGames();
    }
    
    resetFilter() {
        this.filterForm.reset();
        this.getGames();
    }
    
    redirectToEditPage() {
        var id = this.selection.selected.map(({ id }) => id);
        this.router.navigate(['/games/edit/'+id[0]]);
    }
    
    deleteGames() {
        this.openConfirmDeleteDialog();
    }

    openConfirmDeleteDialog(): void {
        const dialogConfig = new MatDialogConfig();
        dialogConfig.disableClose = true;
        dialogConfig.autoFocus = true;
        
        dialogConfig.data = {
            title: 'delete.confirm.title',
            description: 'delete.confirm.description'
        };        

        const dialogRef = this.dialog.open(ConfirmDialogComponent, dialogConfig);
       
        dialogRef.afterClosed().subscribe(
            data => {
                if (data === true) {
                    var ids = this.selection.selected.map(({ id }) => id);
                    this.gameService.deleteGames(ids).subscribe(
                        success => {
                            this.alertService.success(success, true);
                            this.getGames();
                        },
                        error => {
                            this.alertService.error(error, true);
                        }
                    );
                }
            }
        );
    }    
            
}

// @TODO
export interface Games {
    games: Game[];
    total_count: number;
}
