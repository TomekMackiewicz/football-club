import { Component, ViewChild, AfterViewInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { MatPaginator, MatSort, MatDialog, MatDialogConfig } from '@angular/material';
import { merge, of as observableOf } from 'rxjs';
import { catchError, map, startWith, switchMap } from 'rxjs/operators';
import { SelectionModel } from '@angular/cdk/collections';
import { FormBuilder } from '@angular/forms';
import { UserService } from '../user.service';
import { AlertService } from '../../alert/alert.service';
import { User } from '../../model/user';
import { ConfirmDialogComponent } from '../../dialogs/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'app-user-list',
    templateUrl: './user-list.component.html',
})
export class UserListComponent implements AfterViewInit {
    displayedColumns: string[] = ['select', 'username', 'email'];
    data: User[] = [];
    selection = new SelectionModel<User>(true, []);
    resultsLength = 0;
    isLoadingResults = true;
    isRateLimitReached = false;
    
    filterForm = this.fb.group({
        username: [''],
        email: ['']
    });
    filterPanelOpenState = true;
    
    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(
        private router: Router,
        private userService: UserService,
        private alertService: AlertService,
        public dialog: MatDialog,
        private fb: FormBuilder
    ) {}

    ngAfterViewInit() {       
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
        this.getUsers();
    }
    
    getUsers() {
        merge(this.sort.sortChange, this.paginator.page).pipe(
            startWith({}),
            switchMap(() => {
                this.isLoadingResults = true;
                return this.userService.getUsers(
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

                return data.users;
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
        this.getUsers();
    }
    
    resetFilter() {
        this.filterForm.reset();
        this.getUsers();
    }
    
    redirectToEditPage() {
        var id = this.selection.selected.map(({ id }) => id);
        this.router.navigate(['/users/edit/'+id[0]]);
    }
    
    deleteUsers() {
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
                    this.userService.deleteUsers(ids).subscribe(
                        success => {
                            this.alertService.success(success, true);
                            this.getUsers();
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
