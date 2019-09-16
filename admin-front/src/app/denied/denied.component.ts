import { Component, OnInit } from '@angular/core';
import { AlertService } from '../alert/alert.service';

@Component({
    templateUrl: './denied.component.html'
})

export class AccessDeniedComponent implements OnInit {

    constructor(
        private alertService: AlertService
    ) {}
    
    ngOnInit() {
        this.alertService.error('access.denied', true);
    }
}
