import { HttpHeaders } from '@angular/common/http';

export const HTTP_OPTIONS = {
    headers: new HttpHeaders({
        'Content-Type':  'application/json'
    })
};

export const API_URL = 'http://localhost:8000/api';
