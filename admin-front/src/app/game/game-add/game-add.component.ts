import { Component } from '@angular/core';
import { Router } from '@angular/router';
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
        game_type: ['', Validators.required],
        host_team: ['', Validators.required],
        guest_team: ['', Validators.required],
        host_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]],
        guest_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]]
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
                this.alertService.success(success, true);
                this.router.navigate(['/games/list']);
            },
            error => {
                this.alertService.error(error, true);
            }
        );
    }

}
