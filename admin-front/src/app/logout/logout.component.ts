import { Component } from '@angular/core';
import { AuthenticationService } from '../services/authentication.service';
import { AlertService } from '../alert/alert.service';
import { AuthGuard } from '../guards/auth.guard';

@Component({
    selector: 'logout',
    template: ''
})

export class LogoutComponent {

    constructor(
        private authenticationService: AuthenticationService,
        private alertService: AlertService,
        private authGuard: AuthGuard
    ) {}

    ngOnInit() {
        this.authenticationService.logout();
        this.alertService.success('user.logged_out');
        this.authGuard.isLoggedIn(false);         
    }
   
}
