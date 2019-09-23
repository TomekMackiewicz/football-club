import { HttpHeaders } from '@angular/common/http';

export const HTTP_OPTIONS = {
    headers: new HttpHeaders({
        'Content-Type':  'application/json'
    })
};

export const BASE_URL = 'http://localhost:8000/api/v1';

export const ADMIN_URL = 'http://localhost:8000/api/v1/admin';
