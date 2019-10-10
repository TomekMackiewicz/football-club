import { Component, Output, EventEmitter } from '@angular/core';
import { AuthGuard } from '../guards/auth.guard';

@Component({
    selector: 'app-session-tracker',
    templateUrl: './session-tracker.component.html'
})
export class SessionTrackerComponent {

    @Output() voted: EventEmitter<string> = new EventEmitter<string>();
    tokenExpires: string;
    isLoggedIn: boolean = false;

    constructor(private authGuard: AuthGuard) {
        this.trackSessionTime();
    }

    ngOnInit() {
        this.authGuard.loggedIn.subscribe((val: boolean) => {
            this.isLoggedIn = val;
        });
    }

    trackSessionTime() {
        var token = localStorage.getItem('token');
        if (null !== token) {
            var time = this.authGuard.getTokenExpirationTime(token);
            let intervalId = setInterval(() => {
                this.tokenExpires = this.displayTime(time);
                this.voted.emit(this.tokenExpires);
                time = time - 1000;
                if(time === 0 || this.isLoggedIn === false) clearInterval(intervalId);
            }, 1000);
        }
    }

    displayTime(millisec: number) {
        const normalizeTime = (time: string): string => (time.length === 1) ? time.padStart(2, '0') : time;

        let seconds: string = (millisec / 1000).toFixed(0);
        let minutes: string = Math.floor(parseInt(seconds) / 60).toString();
        let hours: string = '';

        if (parseInt(minutes) > 59) {
          hours = normalizeTime(Math.floor(parseInt(minutes) / 60).toString());
          minutes = normalizeTime((parseInt(minutes) - (parseInt(hours) * 60)).toString());
        }
        seconds = normalizeTime(Math.floor(parseInt(seconds) % 60).toString());

        if (hours !== '') {
            return `${hours} h. ${minutes} min. ${seconds} s.`;
        }
        
        return `${minutes} min. ${seconds} s.`;
    }
               
}

