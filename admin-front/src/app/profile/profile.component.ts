import { Component, OnInit } from '@angular/core';
import { AlertService } from '../alert/alert.service';
import { ProfileService } from './profile.service';
import { User } from '../model/user';

@Component({
    selector: 'profile',
    templateUrl: './profile.component.html',       
})

export class ProfileComponent implements OnInit {
    
    public user: User;
    
    constructor(
        private profileService: ProfileService,
        private alertService: AlertService
    ) {}

    ngOnInit(): void {
        this.profileService.getUser(parseInt(localStorage.getItem('userId')))
            .subscribe(
                (data: User) => {
                    this.user = data;
                },
                error => {
                    this.alertService.error("Error loading user profile! " + error);
                }                
            );
    }

}
