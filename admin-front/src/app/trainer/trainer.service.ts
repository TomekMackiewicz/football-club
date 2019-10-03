import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { DatePipe } from '@angular/common';
import { Trainer } from './model/trainer';
import { HTTP_OPTIONS, BASE_URL } from '../constants/http';

@Injectable({
    providedIn: 'root'
})
export class TrainerService {

    constructor(
        private httpClient: HttpClient,
        public datepipe: DatePipe
    ) {}

    getTrainer(id: number): Observable<Trainer> { 
        return this.httpClient.get<Trainer>(BASE_URL+'/trainers/'+id)
            .pipe(catchError(this.handleError));
    }

    getTrainers(sort: string, order: string, page: number, size: number, filters: any): Observable<TrainersWithCount> { 
        let params = 'sort='+sort+'&order='+order+'&page='+page+'&size='+size+'&filters='+JSON.stringify(filters);

        return this.httpClient.get<TrainersWithCount>(BASE_URL+'/trainers?'+params)
            .pipe(catchError(this.handleError));
    }

    addTrainer(trainer: Trainer): Observable<string> {
        return this.httpClient.post<string>(BASE_URL+'/trainers', trainer, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }

    updateTrainer(trainer: Trainer): Observable<string> {
        console.log(trainer);
        return this.httpClient.patch<string>(BASE_URL+'/trainers/'+trainer.id, trainer, HTTP_OPTIONS)
            .pipe(catchError(this.handleError));
    }

    deleteTrainers(ids: Array<number>): Observable<string> {
        return this.httpClient.request<string>('delete', BASE_URL+'/trainers', { body: ids })
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
        console.log(error);

        return throwError('error');
    };

}

export interface TrainersWithCount {
    trainers: Trainer[];
    total_count: number;
}