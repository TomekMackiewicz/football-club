import { Component, ViewChild, AfterViewInit, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { MatPaginator, MatSort, MatDialog, MatDialogConfig } from '@angular/material';
import { merge, of as observableOf } from 'rxjs';
import { catchError, map, startWith, switchMap } from 'rxjs/operators';
import { SelectionModel } from '@angular/cdk/collections';
import { FormBuilder } from '@angular/forms';
import { PostService } from '../post.service';
import { AlertService } from '../../alert/alert.service';
import { Post } from '../../model/post';
import { ConfirmDialogComponent } from '../../dialogs/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'app-post-list',
    templateUrl: './post-list.component.html',
})
export class PostListComponent implements AfterViewInit {
    displayedColumns: string[] = ['select', 'publish_date', 'title'];
    data: Post[] = [];
    selection = new SelectionModel<Post>(true, []);
    resultsLength = 0;
    isLoadingResults = true;
    isRateLimitReached = false;
    
    filterForm = this.fb.group({
        dateFrom: [''],
        dateTo: [''],
        title: ['']
    });
    filterPanelOpenState = true;
    
    @ViewChild(MatPaginator) paginator: MatPaginator;
    @ViewChild(MatSort) sort: MatSort;

    constructor(
        private router: Router,
        private postService: PostService,
        private alertService: AlertService,
        public dialog: MatDialog,
        private fb: FormBuilder
    ) {}

    ngAfterViewInit() {       
        this.sort.sortChange.subscribe(() => this.paginator.pageIndex = 0);
        this.getPosts();
    }
    
    getPosts() {
        merge(this.sort.sortChange, this.paginator.page).pipe(
            startWith({}),
            switchMap(() => {
                this.isLoadingResults = true;
                return this.postService.getPosts(
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

                return data.posts;
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
        this.getPosts();
    }
    
    resetFilter() {
        this.filterForm.reset();
        this.getPosts();
    }
    
    redirectToEditPage() {
        var id = this.selection.selected.map(({ id }) => id);
        this.router.navigate(['/posts/edit/'+id[0]]);
    }
    
    deletePosts() {
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
                    this.postService.deletePosts(ids).subscribe(
                        success => {
                            this.alertService.success(success, true);
                            this.getPosts();
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
