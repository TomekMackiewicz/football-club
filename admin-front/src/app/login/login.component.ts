import { Component, ChangeDetectorRef } from '@angular/core';
import { AuthenticationService } from '../services/authentication.service';
import { AlertService } from '../alert/alert.service';
import { User } from '../model/user';

@Component({
    selector: 'login',
    templateUrl: './login.component.html',       
})

export class LoginComponent {
    
    user: User = new User();
    
    constructor(
        private authenticationService: AuthenticationService,
        private ref: ChangeDetectorRef,
        private alertService: AlertService
    ) {}

    login() {
        this.authenticationService.login(this.user.username, this.user.password);
        this.authenticationService.loginError.subscribe(
            (error) => {
                this.alertService.error(error.message);
                this.ref.markForCheck();// ?
            }
        );
    }

}
