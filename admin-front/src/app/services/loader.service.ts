import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable()
export class LoaderService {
    isLoading = new Subject<boolean>();
    show() {
        this.isLoading.next(true);
    }
    hide() {
        setTimeout(() => { 
            this.isLoading.next(false);
        }, 200);
    }
}
