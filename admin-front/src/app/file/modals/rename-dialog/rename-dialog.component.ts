import { Component, OnInit, Input } from '@angular/core';

@Component({
    selector: 'app-rename-dialog',
    templateUrl: './rename-dialog.component.html',
    styleUrls: ['./rename-dialog.component.css']
})

export class RenameDialogComponent implements OnInit {
    //@Input('name') folderName: string;
    folderName: string;
    
    constructor() { }

    ngOnInit() {
    }

}
