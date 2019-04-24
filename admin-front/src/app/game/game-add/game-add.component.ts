import { Component, AfterViewInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';

import { GameService } from '../game.service';
import { AlertService } from '../../alert/alert.service';

@Component({
    selector: 'app-game-add',
    templateUrl: './game-add.component.html'
})
export class GameAddComponent implements AfterViewInit {

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
        private gameService: GameService,
        private alertService: AlertService,
        private fb: FormBuilder
    ) { }

    ngAfterViewInit() {
        ////this.alertService.success('game.added', true);
    }

    addGame() {
        return this.gameService.addGame(this.gameForm.value)
        .subscribe(success => {
            this.alertService.success('game.added', true);
        });
    }

}
