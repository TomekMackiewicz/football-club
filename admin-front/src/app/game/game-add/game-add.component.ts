import { Component, AfterViewInit } from '@angular/core';
import { Router } from "@angular/router"
import { FormBuilder, Validators } from '@angular/forms';

import { GameService } from '../game.service';
import { AlertService } from '../../alert/alert.service';

@Component({
    selector: 'app-game-add',
    templateUrl: './game-add.component.html'
})
export class GameAddComponent {

    gameForm = this.fb.group({
        date: ['', Validators.required],
        location: ['', Validators.required],
        gameType: ['', Validators.required],
        hostTeam: ['', Validators.required],
        guestTeam: ['', Validators.required],
        hostScore: ['', [Validators.required, Validators.pattern("^[0-9]*$")]],
        guestScore: ['', [Validators.required, Validators.pattern("^[0-9]*$")]]
    });

    constructor(
        private router: Router,
        private gameService: GameService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    addGame() {
        return this.gameService.addGame(this.gameForm.value).subscribe(
            success => {
                this.alertService.success('game.added', true);
                this.router.navigate(['/games/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
