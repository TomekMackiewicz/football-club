import { Component, AfterViewInit, ElementRef, ViewChild } from '@angular/core';
import { animate, state, style, transition, trigger } from '@angular/animations';
import { TranslateService } from '@ngx-translate/core';
import { NavService } from './services/nav.service';
import { NavItem } from './model/nav-item';
import { NAV_ITEMS } from './constants/nav-items';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    animations: [
        trigger('indicatorRotate', [
            state('collapsed', style({transform: 'rotate(0deg)'})),
            state('expanded', style({transform: 'rotate(180deg)'})),
            transition('expanded <=> collapsed',
                animate('225ms cubic-bezier(0.4,0.0,0.2,1)')
            )
        ])
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
