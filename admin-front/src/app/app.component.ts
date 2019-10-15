import { Component, OnInit, AfterViewInit, ElementRef, ViewChild } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { NavService } from './services/nav.service';
import { NavItem } from './model/nav-item';
import { NAV_ITEMS } from './constants/nav-items';
import { indicatorRotate } from './constants/animations';
import { AuthGuard } from './guards/auth.guard';
import { AlertService } from './alert/alert.service';
import { SessionTrackerComponent } from './session-tracker/session-tracker.component';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    animations: [indicatorRotate]
})
export class AppComponent implements OnInit, AfterViewInit {
    
    @ViewChild('sidenav') sidenav: ElementRef;
    @ViewChild(SessionTrackerComponent) sessionTracker: SessionTrackerComponent;
    
    title = 'admin-front';
    sidenavOpened: boolean = true;
    readonly navItems: NavItem[] = NAV_ITEMS;
    isLoggedIn: boolean = false;
    
    constructor(
        private navService: NavService,
        private translate: TranslateService,
        private alertService: AlertService,
        private authGuard: AuthGuard
    ) {
        translate.setDefaultLang('en');
    }
    
    ngOnInit() {
        this.authGuard.loggedIn.subscribe((val: boolean) => {
            this.isLoggedIn = val;
        });
    }

    ngAfterViewInit() {
        this.navService.sidenav = this.sidenav;
    }

    useLanguage(language: string) {
        this.translate.use(language);
    } 
    
    refreshToken() {
        var refreshToken = localStorage.getItem('refreshToken');
        return this.navService.refreshToken(refreshToken).subscribe(
            data => {
                localStorage.setItem('token', data.token);
                localStorage.setItem('refreshToken', data.refresh_token);
                this.sessionTracker.trackSessionTime();
            },
            error => {
                this.alertService.error(error, true);
            }
        ); 
    }               
}
