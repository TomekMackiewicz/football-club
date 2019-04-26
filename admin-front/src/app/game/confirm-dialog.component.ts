import { Component, OnInit, Inject } from '@angular/core';
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material";

@Component({
    selector: 'confirm-dialog',
    templateUrl: './confirm-dialog.component.html'
})
export class ConfirmDialogComponent implements OnInit {

    title: string;
    description: string;

    constructor(
        private dialogRef: MatDialogRef<ConfirmDialogComponent>,
        @Inject(MAT_DIALOG_DATA) data: any) {

        this.title = data.title;
        this.description = data.description;
    }

    ngOnInit() {

    }

    save() {
        this.dialogRef.close();
    }

    close() {
        this.dialogRef.close();
    }
}
