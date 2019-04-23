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
        game_type: ['', Validators.required],
        host_team: ['', Validators.required],
        guest_team: ['', Validators.required],
        host_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]],
        guest_score: ['', [Validators.required, Validators.pattern("^[0-9]*$")]]
    });

    constructor(
        private gameService: GameService,
        private fb: FormBuilder
    ) { }

    ngOnInit() {
    }

    addGame(game: Game) {
        return this.gameService.addGame(game);
    }

    onSubmit() {
      // TODO: Use EventEmitter with form value
      console.warn(this.gameForm.value);
    }

}
