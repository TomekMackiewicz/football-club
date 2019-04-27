import { Component, AfterViewInit, ElementRef, ViewChild } from '@angular/core';
import { animate, state, style, transition, trigger } from '@angular/animations';
import { TranslateService } from '@ngx-translate/core';
import { NavService } from './services/nav.service';
import { NavItem } from './model/nav-item';
import { NAV_ITEMS } from './constants/nav-items';
import { fadeAnimation, indicatorRotate } from './constants/animations';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    animations: [
        fadeAnimation,
        indicatorRotate
    ]
})
export class AppComponent implements AfterViewInit {
    
    @ViewChild('sidenav') sidenav: ElementRef;
    
    title = 'admin-front';
    sidenavOpened: boolean = true;
    readonly navItems: NavItem[] = NAV_ITEMS;
    
    constructor(
        private navService: NavService,
        private translate: TranslateService
    ) {
        translate.setDefaultLang('en');
    }

    ngAfterViewInit() {
        this.navService.sidenav = this.sidenav;
    }

    useLanguage(language: string) {
        this.translate.use(language);
    }    
       
}
