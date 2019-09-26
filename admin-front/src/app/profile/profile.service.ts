import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BASE_URL } from '../constants/http';

@Injectable()
export class ProfileService {

    constructor(private http: HttpClient) {}

    getUser(id: number) {
        return this.http.get(BASE_URL+'/profile/'+id);
    }

    patchUser(user: any) {
        return this.http.patch(BASE_URL+'/profile/'+user.id, { 
            email: user.email,
            current_password: user.currentPassword
        });
    }
}
