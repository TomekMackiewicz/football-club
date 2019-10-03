import { Component, ViewChild, AfterViewInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { MatPaginator, MatSort, MatDialog, MatDialogConfig } from '@angular/material';
import { merge, of as observableOf } from 'rxjs';
import { catchError, map, startWith, switchMap } from 'rxjs/operators';
import { SelectionModel } from '@angular/cdk/collections';
import { FormBuilder } from '@angular/forms';
import { TrainerService } from '../trainer.service';
import { AlertService } from '../../alert/alert.service';
import { Trainer } from '../model/trainer';
import { ConfirmDialogComponent } from '../../dialogs/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'app-trainer-list',
    templateUrl: './trainer-list.component.html',
})
export class TrainerListComponent implements AfterViewInit {
    displayedColumns: string[] = ['select', 'firstName', 'lastName', 'email', 'status'];
    data: Trainer[] = [];
    selection = new SelectionModel<Trainer>(true, []);
    resultsLength = 0;
    isLoadingResults = true;
    isRateLimitReached = false;
    
    filterForm = this.fb.group({
        firstName: [''],
        lastName: [''],
        email: [''],
        status: ['']
    });
    filterPanelOpenState = true;
    
    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(
        private router: Router,
        private trainerService: TrainerService,
        private alertService: AlertService,
        public dialog: MatDialog,
        private fb: FormBuilder
    ) {}

    ngAfterViewInit() {       
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
        this.getTrainers();
    }

    getTrainers() {
        merge(this.sort.sortChange, this.paginator.page).pipe(
            startWith({}),
            switchMap(() => {
                this.isLoadingResults = true;
                return this.trainerService.getTrainers(
                    this.sort.active, 
                    this.sort.direction, 
                    this.paginator.pageIndex+1, 
                    this.paginator.pageSize,
                    this.filterForm.value
                );
            }),
            map(data => {
                this.isLoadingResults = false;
                this.isRateLimitReached = false;
                this.resultsLength = data.total_count;

                return data.trainers;
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
        this.getTrainers();
    }

    resetFilter() {
        this.filterForm.reset();
        this.getTrainers();
    }

    redirectToEditPage() {
        var id = this.selection.selected.map(({ id }) => id);
        this.router.navigate(['/admin/trainers/edit/'+id[0]]);
    }

    deleteTrainers() {
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
                    this.trainerService.deleteTrainers(ids).subscribe(
                        success => {
                            this.alertService.success(success, true);
                            this.getTrainers();
                            this.selection.clear();
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
