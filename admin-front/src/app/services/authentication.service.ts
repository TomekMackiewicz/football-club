import { Injectable, EventEmitter } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import * as decode from 'jwt-decode';
import { Router, ActivatedRoute } from '@angular/router';

@Injectable()
export class AuthenticationService {

    adminUrl = this.route.snapshot.queryParams['adminUrl'] || '/admin/config';
    userUrl = this.route.snapshot.queryParams['userUrl'] || '/admin/games';
    loginUrl = this.route.snapshot.queryParams['loginUrl'] || '/login';
    currentUsername: BehaviorSubject<string> = new BehaviorSubject<string>('');
    admin: BehaviorSubject<boolean> = new BehaviorSubject<boolean>(false);
    loginError: EventEmitter<any> = new EventEmitter();
    
    getUsername(value: string) {
        this.currentUsername.next(value);
    }  

    isAdmin(value: boolean) {
        this.admin.next(value);
    }
        
    constructor(
        private http: HttpClient,
        private route: ActivatedRoute,
        private router: Router           
    ) {};
       
    login(username: string, password: string) { 
        return this.http.post<any>('http://localhost:8000/api/v1/login_check', { 
            username: username, 
            password: password
        }).subscribe(
            data => {
                var token: any = decode(data.token);
                if (token) {             
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('currentUsername', token.username);
                    localStorage.setItem('userId', token.userId);
                    localStorage.setItem('userRole', token.roles[0]);                    
                    this.getUsername(localStorage.getItem('currentUsername'));
                    if (token.roles[0] == 'ROLE_ADMIN' || token.roles[0] == 'ROLE_SUPER_ADMIN') {
                        this.isAdmin(true);
                        this.router.navigate([this.adminUrl]);
                    } else {
                        this.router.navigate([this.userUrl]);
                    }                
                }
            },
            error => {
                 this.loginError.emit(error.error);
            }
        );
    }

    logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('currentUsername');
        localStorage.removeItem('userId');
        localStorage.removeItem('userRole');
        this.isAdmin(false);
        this.getUsername('');
        this.router.navigate([this.loginUrl]);
    }
}