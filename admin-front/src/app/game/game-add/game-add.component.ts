import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';

import { GameService } from '../game.service';
import { Game } from '../../model/game';

@Component({
    selector: 'app-game-add',
    templateUrl: './game-add.component.html'
})
export class GameAddComponent implements OnInit {

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
        private fb: FormBuilder
    ) { }

    ngOnInit() {
    }

    addGame() {
        return this.gameService.addGame(this.gameForm.value)
        .subscribe(success => {
            console.log(success);
        });
    }

}
