import { Component, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { Router, 
         Event, 
         NavigationStart, 
         NavigationEnd, 
         NavigationCancel, 
         NavigationError 
} from '@angular/router';
import { LoaderService } from '../../services/loader.service';
    
@Component({
    selector: 'app-loader',
    templateUrl: './loader.component.html'
})
export class LoaderComponent {
        
    isLoading: Subject<boolean> = this.loaderService.isLoading;
    routeChanged: boolean = false;
    
    constructor(
        private loaderService: LoaderService,
        private router: Router
    ) {
        this.router.events.subscribe((event: Event) => {
            switch (true) {
                case event instanceof NavigationStart: {
                    this.routeChanged = true;
                    break;
                }

                case event instanceof NavigationEnd:
                case event instanceof NavigationCancel:
                case event instanceof NavigationError: {
                    setTimeout(() => { this.routeChanged = false; }, 200);
                    break;
                }
                default: {
                    break;
                }
            }
        });
    }
      
}
