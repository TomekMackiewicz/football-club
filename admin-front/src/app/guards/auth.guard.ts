import { Injectable, EventEmitter } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import * as jwt_decode from 'jwt-decode';
import { BehaviorSubject } from 'rxjs';

@Injectable()
export class AuthGuard implements CanActivate {

    loggedIn: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);

    constructor(
        private router: Router
    ) {}

    isLoggedIn(value: boolean) {
        this.loggedIn.next(value);
    }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (!this.isTokenExpired()) {
            this.isLoggedIn(true);
            return true;
        }
        this.isLoggedIn(false);
        this.router.navigate(['/denied'], {queryParams: {returnUrl: state.url}});
               
        return false;
    }
    
    getTokenExpirationDate(token: string): Date {
        var decoded: any = jwt_decode(token);
        
        if (decoded.exp === undefined) {
            return null;
        }
        
        const date = new Date(0); 
        date.setUTCSeconds(decoded.exp);
        
        return date;
    }

    getToken(): string {        
        return localStorage.getItem('token');        
    }

    isTokenExpired(): boolean {
        var token = this.getToken();
              
        if(!token) {
            return true;
        }
        
        const date = this.getTokenExpirationDate(token);
        
        if(date === undefined) {
            return false;
        }
        
        return !(date.valueOf() > new Date().valueOf());
    } 
   
}
