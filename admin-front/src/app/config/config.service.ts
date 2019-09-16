import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { Config } from '../model/config';
import { HTTP_OPTIONS, ADMIN_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class ConfigService {
            
    constructor(
        private httpClient: HttpClient
    ) {}

    getConfig(): Observable<Config> {     
        return this.httpClient.get<Config>(ADMIN_URL+'/config')
            .pipe(catchError(this.handleError));   
    }    

    createConfig(config: Config): Observable<string> {
        return this.httpClient.post<string>(ADMIN_URL+'/config', config, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }

    updateConfig(config: Config): Observable<string> {
        return this.httpClient.patch<string>(ADMIN_URL+'/config/'+config.id, config, HTTP_OPTIONS)
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
