import { Injectable } from '@angular/core';
import { Event, NavigationEnd, Router } from '@angular/router';
import { BehaviorSubject } from 'rxjs';
import { Observable, throwError } from 'rxjs';
import { HTTP_OPTIONS, BASE_URL } from '../constants/http';
import { catchError } from 'rxjs/operators';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';

@Injectable()
export class NavService {
    public sidenav: any;
    public currentUrl = new BehaviorSubject<string>(undefined);

    constructor(
        private router: Router,
        private httpClient: HttpClient
    ) {
        this.router.events.subscribe((event: Event) => {
            if (event instanceof NavigationEnd) {
                this.currentUrl.next(event.urlAfterRedirects);
            }
        });
    }

    public closeNav() {
        //this.sidenav.close();
    }

    public openNav() {
        //this.sidenav.open();
    }

    refreshToken(refreshToken: any): Observable<any> {
        return this.httpClient.post<any>(BASE_URL+'/token/refresh', {refresh_token: refreshToken}, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }    

    private handleError(error: HttpErrorResponse) {
        var errorMsgDev;

        if (error.error instanceof ErrorEvent) {
            // A client-side or network error occurred.
            errorMsgDev = error.error.message;
        } else {
            // The backend returned an unsuccessful response code.
            errorMsgDev = error.status + ' ' + error.error;
        }
        console.log(errorMsgDev);

        return throwError('error');
    };

}
